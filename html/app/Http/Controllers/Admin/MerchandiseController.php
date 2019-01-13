<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Merchandise;
use App\Category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MerchandiseController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index(Request $request)
    {
        $categories = Category::select('t1.name as lev1', 't2.name as lev2', 't2.id as lev2_id', 't3.name as lev3', 't3.id as lev3_id', 't4.name as lev4', 't4.id as lev4_id')
            ->from('category as t1')
            ->leftJoin('category as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('category as t3', 't3.parent', '=', 't2.id')
            ->leftJoin('category as t4', 't4.parent', '=', 't3.id')
            ->where('t1.name', '=', 'HEAD')
            ->get();
        $binding = [
            'merchandises' => $request->category ? Merchandise::where('category', '=', $request->category)->get() : Merchandise::all(),
            'categories' => $categories,
            'category' => $request->category,
        ];
        return view('admin.merchandise.index', $binding);
    }

    public function new(Request $request)
    {
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
        return view('admin.merchandise.new', $binding);
    }

    public function newProcess(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'intro' => 'required',
            'category' => 'required',
            'price' => 'required | integer | min:0',
            'amount' => 'required | integer | min:0',
            'status' => 'required | in:C,S',
            'photo' => 'image | max:10240'
        ])->validate();
        $data = $request->all();
        //image process
        if($request->hasFile('photo')) {
            $path = Storage::disk('s3')->put('resources/img', $request->file('photo'));
            $data['photo'] = $path;
        }
        $Merchandise = Merchandise::create($data);
        return redirect('/admin/merchandise/'.$Merchandise->id);
    }

    public function show($id, Request $request)
    {
        $Merchandise = Merchandise::findOrFail($id);
        $binding = [
            'Merchandise' => $Merchandise,
        ];
        return view('admin.merchandise.show', $binding);
    }

    public function delete($id, Request $request)
    {
        $Merchandise = Merchandise::findOrFail($id);
        if(!is_null($Merchandise->photo)) {
            Storage::disk('s3')->delete($Merchandise->photo);
        }
        $Merchandise->delete();
        
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Item "'.$Merchandise->name.'"[id='.$Merchandise->id.'] deleted successfully',
        ]);

        return redirect('/admin/merchandise');
    }

    public function edit($id, Request $request)
    {
        $Merchandise = Merchandise::findOrFail($id);
        $categories = Category::select('t1.name as lev1', 't2.name as lev2', 't2.id as lev2_id', 't3.name as lev3', 't3.id as lev3_id', 't4.name as lev4', 't4.id as lev4_id')
            ->from('category as t1')
            ->leftJoin('category as t2', 't2.parent', '=', 't1.id')
            ->leftJoin('category as t3', 't3.parent', '=', 't2.id')
            ->leftJoin('category as t4', 't4.parent', '=', 't3.id')
            ->where('t1.name', '=', 'HEAD')
            ->get();
        $binding = [
            'Merchandise' => $Merchandise,
            'categories' => $categories,
        ];
        return view('admin.merchandise.edit', $binding);
    }

    public function editProcess($id, Request $request)
    {
        $Merchandise = Merchandise::findOrFail($id);
        $Merchandise->name = $request->name;
        $Merchandise->intro = $request->intro;
        $Merchandise->category = $request->category;
        $Merchandise->price = $request->price;
        $Merchandise->amount = $request->amount;
        $Merchandise->status = $request->status;
        $Merchandise->barcode_ean = $request->barcode_ean;
        if($request->hasFile('photo')) {
            $path = Storage::disk('s3')->put('resources/img', $request->file('photo'));
            Storage::disk('s3')->delete($Merchandise->photo);
            $Merchandise->photo = $path;
        }
        $Merchandise->save();

        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Updated successfully',
        ]);

        return redirect('/admin/merchandise/'.$id);
    }
}
