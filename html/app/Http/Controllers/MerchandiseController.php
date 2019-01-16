<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Merchandise;
use App\Category;

class MerchandiseController extends Controller
{
    //
    public function show(Request $request, $id) {
        $Merchandise = Merchandise::findId($id);
        $binding = [
            'Merchandise' => $Merchandise,
            'categories' => Category::tree($Merchandise->type)->get(),
            'category' => $Merchandise->category,
            'type' => $Merchandise->type,
        ];
        return view('merchandise.show', $binding);
    }
}
