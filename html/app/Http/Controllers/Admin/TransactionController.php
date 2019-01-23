<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $orders = Order::all();
        $binding = [
            'orders' => $orders,
        ];
        return view('admin.transaction.index', $binding);
    }
}
