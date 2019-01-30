<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Merchandise;
use App\Order;
use App\MerchandiseInventory;
use Validator;
use Exception;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $str = "已新增商品至購物車中";
        Validator::make($request->all(), [
            'method' => 'required | in:add,set',
            'amount' => 'required | integer | between:0,100',
            'product_id' => 'required | string | size:9',
        ])->validate();

        $cart = collect($request->session()->get('cart', null))->keyBy('product_id');
        if(!$cart->isEmpty() && $cart->contains('product_id', $request->product_id)) {
            if($request->method == 'set' && $request->amount == 0) {
                $cart->forget($request->product_id);
                $str = "已從購物車中移除商品";
            } else {
                $m = $cart->pull($request->product_id);
                if($request->method == 'add')
                    $m['amount'] += $request->amount;
                else
                    $m['amount'] = $request->amount;
                $cart->push($m);
                $str = "已更改商品數量";
            }
            $request->session()->put('cart', $cart->values()->all());
        } else {
            $request->session()->push('cart', [
                'product_id' => $request->product_id,
                'amount' => $request->amount,
                'time' => time(),
            ]);
        }
        return $str;
    }

    public function show(Request $request)
    {
        //$request->session()->forget('cart');
        $total = 0;
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('product_id');
            $Inventories = MerchandiseInventory::whereIn('product_id', $ids)->get();
            $cart = $cart->keyBy('product_id');
            $Inventories = $Inventories->keyBy('product_id');

            foreach ($Inventories as $Pid => $i) {
                $i->time = $cart->get($Pid)['time'];
                $i->stockEnough = ($i->amount >= $cart->get($Pid)['amount']) ? true : false;
                $i->buyAmount = $cart->get($Pid)['amount'];
                $i->price = $i->merchandise->price;
                $i->name = $i->merchandise->name;
                $total += $i->price * $i->buyAmount;
            }
            

        }
        $binding = [
            'cart' => isset($Inventories) ? $Inventories->sortBy('time')->values()->all() : null,
            //'raw' => print_r($request->session()->get('cart')),
            'total' => $total,
        ];
        return view('transaction.cart', $binding);
    }

    public function checkout(Request $request)
    {
        if($request->session()->has('cart') && count($request->session()->get('cart')) > 0)
            return view('transaction.checkout');
        return redirect('/shopping-cart');
    }

    public function confirm(Request $request)
    {
        /* validate */
        Validator::make($request->all(), [
            'payment_method' => 'required|in:D,C,L,S',
            'delivery_method' => 'required|in:S,D',
            'delivery_name' => 'required',
            'delivery_phone' => 'required|regex:/^09[0-9]{8}$/',
            'delivery_address' => 'required',
        ])->validate();

        $total = 0;
        $inventories = collect([]);
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('product_id');
            $inventories = MerchandiseInventory::whereIn('product_id', $ids)->get();

            $cart = $cart->keyBy('product_id');
            $inventories = $inventories->keyBy('product_id');

            foreach ($inventories as $pid => $inventory) {
                $inventory->stockEnough = ($inventory->amount >= $cart->get($pid)['amount']) ? true : false;
                $inventory->buyAmount = $cart->get($pid)['amount'];
                $inventory->name = $inventory->merchandise->name;
                $inventory->price = $inventory->merchandise->price;
                $total += $inventory->price * $inventory->buyAmount;
            }
        }

        $binding = [
            'cart' => isset($inventories) ? $inventories->all() : null,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'delivery_method' => $request->delivery_method,
            'delivery_address' => $request->delivery_address,
            'delivery_name' => $request->delivery_name,
            'delivery_phone' => $request->delivery_phone,
        ];
        return view('transaction.confirm', $binding);
    }

    public function checkoutProcess(Request $request) {
        /* validate */
        Validator::make($request->all(), [
            'payment_method' => 'required|in:D,C,L,S',
            'delivery_method' => 'required|in:S,D',
            'delivery_name' => 'required',
            'delivery_phone' => 'required|regex:/^09[0-9]{8}$/',
            'delivery_address' => 'required',
        ])->validate();
        try {
            /* BEGIN TRANSACTION */
            DB::beginTransaction();
            $inventories = collect([]);
            $total = 0;
            if($request->session()->has('cart') && count($request->session()->get('cart')) > 0) {
                $stockEnough = true;
                $cart = collect($request->session()->get('cart'));
                $ids = $cart->pluck('product_id');
                $inventories = MerchandiseInventory::whereIn('product_id', $ids)->get();

                $cart = $cart->keyBy('product_id');
                $inventories = $inventories->keyBy('product_id');

                foreach ($inventories as $pid => $inventory) {
                    $buy_amount = $cart->get($pid)['amount'];
                    if ($inventory->amount < $buy_amount) {
                        $stockEnough = false;
                        break;
                    }
                    $total += $buy_amount * $inventory->merchandise->price;
                    //$cart->get($key)['amount'];
                }
                if(!$stockEnough)
                    throw new Exception('Stock is not enough.');
            } else {
                throw new Exception('Nothing in cart.');
            }
            /* Create Order */
            $Order = Order::create([
                'user_id' => Auth::user()->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'N', //付款
                'delivery_method' => $request->delivery_method,
                'delivery_name' => $request->delivery_name,
                'delivery_address' => $request->delivery_address,
                'delivery_phone' => $request->delivery_phone,
                'status' => '處理中',
                'total' => $total,
                'delivery_traceID' => Str::uuid(),
            ]);
            /* Updating merchandises' amount */
            foreach($inventories as $pid => $inventory) {
                $inventory->amount -= $cart->get($pid)['amount'];
                $inventory->save();
            }
            /* Create Order_items */
            foreach($inventories as $pid => $inventory) {
                $inventory->amount = $cart->get($pid)['amount'];
                $inventory->order_id = $Order->id;
                $inventory->price = $inventory->merchandise->price;
            }
            $inventories = $inventories->values()->toArray();
            $Order->items()->createMany($inventories);

            
            
            /* Clear Shopping Cart */
            $request->session()->forget('cart');
            /* END TRANSACTION */
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return "<h1>".$e->getMessage()."</h1>";
        }
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Ordered completely. We\'ll deal with your order soon.'
        ]);
        return redirect()->route('order');
    }

    public function jsonDetail(Request $request)
    {
        //$request->session()->forget('cart');
        $total = 0;
        $merchandiseInventory = collect([]);
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('product_id');
            $merchandiseInventory = MerchandiseInventory::whereIn('product_id', $ids)->get();

            $cart = $cart->keyBy('product_id');
            $merchandiseInventory = $merchandiseInventory->keyBy('product_id');

            foreach ($merchandiseInventory as $Pid => $MI) {
                $MI->buyAmount = $cart->get($Pid)['amount'];
                $MI->time = $cart->get($Pid)['time'];
                $MI->stockEnough = ($MI->amount >= $MI->buyAmount) ? true : false;
                $MI->name = $MI->merchandise->name;
                $MI->price = $MI->merchandise->price;
                $total += $MI->price * $MI->buyAmount;
            }
        }
        $merchandiseInventory = $merchandiseInventory->sortBy('time')->values()->toArray();
        return response()->json([
            'count' => count($merchandiseInventory),
            'total' => $total,
            'detail' => $merchandiseInventory,
        ]);
    }
}
