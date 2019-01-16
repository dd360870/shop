<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Merchandise;
use App\Category;

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
        return view('home');
    }

    public function men(Request $request)
    {
        $temp = [2];
        //$all = collect([]);
        do {
            $last = $temp;
            $temp = Category::whereIn('parent', $temp)->get();
            //$all = $all->concat($temp);
            $temp = $temp->pluck('id');
        } while(!$temp->isEmpty());

        //return var_dump($last);

        $binding = [
            'merchandises' => $request->category ? Merchandise::selling()->category($request->category)->get() : Merchandise::selling()->whereIn('category', $last)->get(),
            'categories' => Category::tree('men')->get(),
            'category' => $request->category,
        ];
        return view('merchandise.subview', $binding);
    }

    public function women(Request $request)
    {
        $temp = [3];
        do {
            $last = $temp;
            $temp = Category::whereIn('parent', $temp)->get();
            $temp = $temp->pluck('id');
        } while(!$temp->isEmpty());

        $binding = [
            'merchandises' => $request->category ? Merchandise::selling()->category($request->category)->get() : Merchandise::selling()->whereIn('category', $last)->get(),
            'categories' => Category::tree('women')->get(),
            'category' => $request->category,
        ];
        return view('merchandise.subview', $binding);
    }
}
