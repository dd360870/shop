<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Merchandise;
use App\Category;

class MerchandiseController extends Controller
{
    //
    public function show(Request $request, $id) {
        $Merchandise = Merchandise::findOrFail($id);
        $binding = [
            'Merchandise' => $Merchandise,
            'categories' => Category::tree($Merchandise->category->type)->get(),
            'category' => $Merchandise->category->id,
            'type' => $Merchandise->category->type,
        ];
        return view('merchandise.show', $binding);
    }
}
