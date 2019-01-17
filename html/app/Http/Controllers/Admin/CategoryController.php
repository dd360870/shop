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
        $binding = [
            'categories' => Category::tree()->get(),
        ];
        return view('admin.category.index', $binding);
    }

    public function add(Request $request) {
        $Category = Category::create([
            'name' => $request->name,
            'parent' => $request->parent,
            'type' => $request->type,
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
