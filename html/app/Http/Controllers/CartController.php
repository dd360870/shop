<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Merchandise;
use App\Order;
use Validator;
use Exception;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $str = "已新增商品至購物車中";
        Validator::make($request->all(), [
            'method' => 'required | in:add,set',
            'Mid' => 'required | digits_between:1,10',
            'amount' => 'required | integer | between:0,100',
        ])->validate();

        $cart = collect($request->session()->get('cart', null))->keyBy('Mid');
        if(!$cart->isEmpty() && $cart->contains('Mid', $request->Mid)) {
            if($request->method == 'set' && $request->amount == 0) {
                $cart->forget($request->Mid);
                $str = "已從購物車中移除商品";
            } else {
                $m = $cart->pull($request->Mid);
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
                'Mid' => $request->Mid,
                'amount' => $request->amount,
                'time' => time(),
            ]);
        }

        /*$cart = $request->session()->get('cart', null);
        if(!empty($cart) && collect($cart)->contains('Mid', $request->Mid)) {
            $cart = collect($cart);
            $cart->transform(function($item, $key) use ($request) {
                if($item['Mid'] == $request->Mid) {
                    if($request->method == 'add')
                        $item['amount'] += $request->amount;
                    else
                        $item['amount'] = $request->amount;
                }
                return $item;
            });
            $request->session()->put('cart', $cart->all());
        }
        else {
            $request->session()->push('cart', [
                'Mid' => $request->Mid,
                'amount' => $request->amount,
            ]);
        }*/
        return $str;
    }

    public function show(Request $request)
    {
        //$request->session()->forget('cart');
        $total = 0;
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('Mid');
            $merchandises = Merchandise::select('id', 'name', 'price', 'amount')->whereIn('id', $ids)->get();
            $cart = $cart->keyBy('Mid');
            $merchandises = $merchandises->keyBy('id');

            foreach ($merchandises as $id => $m) {
                $m->Mid = $m->id;
                $m->time = $cart->get($id)['time'];
                $m->stockEnough = ($m->amount >= $cart->get($id)['amount']) ? true : false;
                $m->buyAmount = $cart->get($id)['amount'];
                $total += $m->price * $m->buyAmount;
            }
            

        }
        $binding = [
            'cart' => isset($merchandises) ? $merchandises->sortBy('time')->values()->all() : null,
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
            'pay_method' => 'required|in:D,C,L,S',
            'delivery_method' => 'required|in:S,D',
            'delivery_name' => 'required',
            'delivery_phone' => 'required|regex:/^09[0-9]{8}$/',
            'delivery_address' => 'required',
        ])->validate();

        $total = 0;
        $merchandises = collect([]);
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('Mid');
            $merchandises = Merchandise::select('id', 'name', 'price', 'amount')->whereIn('id', $ids)->get();

            $cart = $cart->keyBy('Mid');
            $merchandises = $merchandises->keyBy('id');

            foreach ($merchandises as $key => $value) {
                $value->stockEnough = ($value->amount >= $cart->get($key)['amount']) ? true : false;
                $value->amount = $cart->get($key)['amount'];
                $total += $value->price * $value->amount;
            }
        }

        $binding = [
            'cart' => isset($merchandises) ? $merchandises->all() : null,
            'total' => $total,
            'pay_method' => $request->pay_method,
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
            'pay_method' => 'required|in:D,C,L,S',
            'delivery_method' => 'required|in:S,D',
            'delivery_name' => 'required',
            'delivery_phone' => 'required|regex:/^09[0-9]{8}$/',
            'delivery_address' => 'required',
        ])->validate();
        try {
            /* BEGIN TRANSACTION */
            DB::beginTransaction();
            $merchandises = collect([]);
            $total = 0;
            if($request->session()->has('cart') && count($request->session()->get('cart')) > 0) {
                $stockEnough = true;
                $cart = collect($request->session()->get('cart'));
                $ids = $cart->pluck('Mid');
                $merchandises = Merchandise::select('id', 'name', 'price', 'amount')->whereIn('id', $ids)->get();

                $cart = $cart->keyBy('Mid');
                $merchandises = $merchandises->keyBy('id');

                foreach ($merchandises as $id => $m) {
                    $buy_amount = $cart->get($id)['amount'];
                    if ($m->amount < $buy_amount) {
                        $stockEnough = false;
                    }
                    $total += $buy_amount * $m->price;
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
                'pay_method' => $request->pay_method,
                'paid' => true, //已付款
                'delivery_method' => $request->delivery_method,
                'delivery_name' => $request->delivery_name,
                'delivery_address' => $request->delivery_address,
                'delivery_phone' => $request->delivery_phone,
                'status' => '處理中',
                'total' => $total,
                'delivery_traceID' => Str::uuid(),
            ]);
            /* Updating merchandises' amount */
            foreach($merchandises as $id => $m) {
                $m->amount -= $cart->get($id)['amount'];
                $m->save();
            }
            /* Create Order_items */
            foreach($merchandises as $id => $m) {
                $m->amount = $cart->get($id)['amount'];
                $m->merchandise_id = $m->id;
                $m->order_id = $Order->id;
                $m->price = $m->price;
            }
            $merchandises = $merchandises->values()->toArray();
            $Order->items()->createMany($merchandises);

            
            
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
        $total = 0;
        $merchandises = collect([]);
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('Mid');
            $merchandises = Merchandise::select('id', 'name', 'price', 'amount')->whereIn('id', $ids)->get();

            $cart = $cart->keyBy('Mid');
            $merchandises = $merchandises->keyBy('id');

            foreach ($merchandises as $id => $m) {
                $m->Mid = $m->id;
                $m->buyAmount = $cart->get($id)['amount'];
                $m->time = $cart->get($id)['time'];
                $m->stockEnough = ($m->amount >= $m->buyAmount) ? true : false;
                $total += $m->price * $m->buyAmount;
            }
        }
        $merchandises = $merchandises->sortBy('time')->values()->toArray();
        return response()->json([
            'count' => count($merchandises),
            'total' => $total,
            'detail' => $merchandises,
        ]);
    }
}
