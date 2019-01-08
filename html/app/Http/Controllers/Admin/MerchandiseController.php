<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Merchandise;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
        $binding = [
            'merchandises' => Merchandise::all(),
        ];
        return view('admin.merchandise.index', $binding);
    }

    public function new(Request $request)
    {
        return view('admin.merchandise.new');
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
            $path = $request->file('photo')->store('resources/img', 'public');
            $data['photo'] = $path;
        }
        return asset($path);
        $Merchandise = Merchandise::create($data);
        return redirect('/admin/merchandise/'.$Merchandise->id.'/show');
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
            Storage::disk('public')->delete($Merchandise->photo);
        }
        $Merchandise->delete();
        
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Item "'.$Merchandise->name.'"[id='.$Merchandise->id.'] deleted successfully',
        ]);

        return redirect('/admin/merchandise');
    }
}
