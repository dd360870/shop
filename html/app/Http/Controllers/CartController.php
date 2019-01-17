<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Merchandise;

class CartController extends Controller
{
    public function add(Request $request) {
        $cart = $request->session()->get('cart', null);
        if(!empty($cart) && collect($cart)->contains('Mid', $request->Mid)) {
            $cart = collect($cart);
            $cart->transform(function($item, $key) use ($request) {
                if($item['Mid'] == $request->Mid) {
                    $item['amount'] += $request->amount;
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
        }
        return '已新增至購物車中';
    }

    public function show(Request $request) {
        //$request->session()->forget('cart');
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('Mid');
            $merchandises = Merchandise::select('id', 'name', 'price')->whereIn('id', $ids)->get();

            $cart = $cart->keyBy('Mid');
            $merchandises = $merchandises->keyBy('id');

            foreach ($merchandises as $key => $value) {
                $value->amount = $cart->get($key)['amount'];
            }
            

        }
        //return var_dump($request->session()->get('cart'));
        $binding = [
            'cart' => isset($merchandises) ? $merchandises->all() : null,
            'raw' => print_r($request->session()->get('cart')),
        ];
        //return var_dump($merchandises->all());
        return view('transaction.cart', $binding);
    }

    public function jsonDetail(Request $request) {
        $total = 0;
        $merchandises = collect([]);
        if($request->session()->has('cart')) {
            $cart = collect($request->session()->get('cart'));
            $ids = $cart->pluck('Mid');
            $merchandises = Merchandise::select('id', 'name', 'price')->whereIn('id', $ids)->get();

            $cart = $cart->keyBy('Mid');
            $merchandises = $merchandises->keyBy('id');

            foreach ($merchandises as $key => $value) {
                $value->amount = $cart->get($key)['amount'];
                $total += $value->price * $value->amount;
            }
        }
        $merchandises = $merchandises->values()->toArray();
        return response()->json([
            'count' => count($merchandises),
            'total' => $total,
            'detail' => $merchandises,
        ]);
    }
}
