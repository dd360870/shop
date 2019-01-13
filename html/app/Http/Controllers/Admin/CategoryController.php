<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
    }

    public function index(Request $request) {
        $categories = Category::select('t1.name as lev1', 't2.name as lev2', 't2.id as lev2_id', 't3.name as lev3', 't3.id as lev3_id', 't4.name as lev4', 't4.id as lev4_id')
            ->from('category as t1')
            ->leftJoin('category as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('category as t3', 't3.parent', '=', 't2.id')
            ->leftJoin('category as t4', 't4.parent', '=', 't3.id')
            ->where('t1.name', '=', 'HEAD')
            ->get();
        $binding = [
            'categories' => $categories,
        ];
        return view('admin.category.index', $binding);
    }

    public function add(Request $request) {
        $Category = Category::create([
            'name' => $request->name,
            'parent' => $request->parent
        ]);
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Category '.$Category->name.' [ '.$Category->id.' ] has created successfully.',
        ]);
        return redirect('/admin/category');
    }

    public function edit(Request $request) {
        $Category = Category::findOrFail($request->id);
        $Category->name = $request->name;
        $Category->save();
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Category '.$Category->name.' [ '.$Category->id.' ] has renamed successfully.',
        ]);
        return redirect('/admin/category');
    }
}
