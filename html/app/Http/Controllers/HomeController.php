<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Merchandise;
use App\Category;
use App\OrderItem;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$orderItems = OrderItem::whereBetween('created_at', [date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y"))), date("Y-m-d H:i:s")])->get();
        $_30d_ago = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")-30, date("Y")));
        $today = date("Y-m-d H:i:s");
        $binding = [
            /* 最新品項4個 */
            'new_items' => Merchandise::orderBy('created_at', 'desc')->take(4)->get(),
            /* 30日內最熱銷品項 */
            'hot_items' => Merchandise::rightJoin(
                DB::raw('(
                    SELECT merchandise_id, SUM(amount) AS sold_total
                    FROM order_items
                    WHERE created_at BETWEEN "'.$_30d_ago.'" AND "'.$today.'"
                    GROUP BY merchandise_id
                ) as order_items'),
                'merchandises.id', '=', 'order_items.merchandise_id')->orderBy('sold_total', 'desc')->take(4)->get(),
        ];
        return view('home', $binding);
    }

    public function men(Request $request)
    {
        return $this->subview($request, 1);
    }

    public function women(Request $request)
    {
        return $this->subview($request, 2);
    }

    private function subview($request, $type) {
        $cate = Category::where('type', $type)->get()->pluck('id');

        $binding = [
            'merchandises' => $request->category ? Merchandise::selling()->ofCategory($request->category)->get() : Merchandise::selling()->inCategory($cate)->get(),
            'categories' => Category::tree($type)->get(),
            'category' => $request->category,
            'type' => $type,
        ];
        return view('merchandise.subview', $binding);
    }
}
