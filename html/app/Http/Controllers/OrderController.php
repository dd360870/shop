<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;

class OrderController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $orders = Order::where('user_id', Auth::user()->id)->get();

        $binding = [
            'orders' => $orders,
        ];
        return view('order.index', $binding);
    }

    public function detail($id) {
        $Order = Order::findOrFail($id);
        
        $binding = [
            'Order' => $Order,
        ];
        return view('order.detail', $binding);
    }
}
