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
        return $this->subview($request, 2);
    }

    public function women(Request $request)
    {
        return $this->subview($request, 3);
    }

    private function subview($request, $type) {
        $cate = Category::where('type', $type)->get()->pluck('id');

        $binding = [
            'merchandises' => $request->category ? Merchandise::selling()->category($request->category)->get() : Merchandise::selling()->whereIn('category', $cate)->get(),
            'categories' => Category::tree($type)->get(),
            'category' => $request->category,
            'type' => $type,
            'type_name' => Category::findOrFail($type)->name,
        ];
        return view('merchandise.subview', $binding);
    }
}
