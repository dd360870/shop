<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Merchandise;
use App\Category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\MerchandiseInventory;

use Exception;

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
            'merchandises' => $request->category_id ? Merchandise::ofCategory($request->category_id)->get() : Merchandise::all(),
            'categories' => Category::tree()->get(),
        ];
        return view('admin.merchandise.index', $binding);
    }

    public function new(Request $request)
    {
        $binding = [
            'categories' => Category::tree()->get(),
        ];
        return view('admin.merchandise.new', $binding);
    }

    public function newProcess(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'intro' => 'required',
            'category_id' => 'required',
            'price' => 'required | integer | min:0',
            'is_selling' => 'required | in:0,1',
            'color_id' => 'nullable',
            'size_min' => 'required | integer | lte:size_max',
            'size_max' => 'required | integer'
            //'photo' => 'image | max:10240'
        ])->validate();
        $data = $request->all();
        /*var_dump($request->color_id);
        die();*/
        

        /*if($request->hasFile('photo')) {
            $path = Storage::disk('s3')->put('resources/img', $request->file('photo'));
            $data['photo'] = $path;
        }*/
        $Merchandise = Merchandise::create($data);

        $sizes = collect(Config::get('constants.size'));
        if(!$sizes->has([$request->size_min, $request->size_max])) {
            throw new Exception('Size ID is not right.');
        }
        //var_dump($request->color_id);die();
        $colors = collect(Config::get('constants.color'));
        if($request->color_id && !$colors->has($request->color_id)) {
            throw new Exception('Color ID is not right');
        }
        if ($request->color_id) {
            foreach ($request->color_id as $key => $color) {
                for ($size_id = $request->size_min; $size_id <= $request->size_max; $size_id++) { // XS - XXL
                    MerchandiseInventory::create([
                        'merchandise_id' => $Merchandise->id,
                        'color_id' => $color,
                        'size_id' => $size_id,
                    ]);
                }
            }
        }
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
        /* Delete photos */
        foreach($Merchandise->inventoryByColors as $inventory) {
            if(Storage::disk('s3')->exists($inventory->photoPath)) {
                Storage::disk('s3')->delete($inventory->photoPath);
            }
        }
        /* Delete Inventories */
        foreach($Merchandise->inventory() as $inventory) {
            $inventory->delete();
        }
        /* Delete Merchandise */
        $Merchandise->delete();
        
        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Item "'.$Merchandise->name.'"[id='.$Merchandise->id.'] deleted successfully',
        ]);

        return redirect('/admin/merchandise');
    }

    public function edit($id, Request $request)
    {
        /*$Merchandise = Merchandise::findOrFail($id);
        var_dump(Storage::disk('s3')->files($Merchandise->photoDirectory));
        die();*/
        $binding = [
            'Merchandise' => Merchandise::findOrFail($id),
            'categories' => Category::tree()->get(),
        ];
        return view('admin.merchandise.edit', $binding);
    }

    public function editProcess($id, Request $request)
    {
        $Merchandise = Merchandise::findOrFail($id);
        $Merchandise->name = $request->name;
        $Merchandise->intro = $request->intro;
        $Merchandise->category_id = $request->category_id;
        $Merchandise->price = $request->price;
        $Merchandise->is_selling = $request->is_selling;

        /* Set Main Photo */
        if(isset($request->main_photo) && $request->main_photo != $Merchandise->photoPath) {
            if(Storage::disk('s3')->exists($Merchandise->photoPath)) {
                Storage::disk('s3')->delete($Merchandise->photoPath);
            }
            Storage::disk('s3')->copy($request->main_photo, $Merchandise->photoPath);
        }

        $add = collect($request->color_id)->keyBy(null)->except($Merchandise->colors)->values();
        $remove = collect($Merchandise->colors)->keyBy(null)->except($request->color_id)->values();

        /* Create added Inventories */
        foreach ($add as $color_id) {
            for ($sid = $Merchandise->size_min; $sid <= $Merchandise->size_max; $sid++) {
                MerchandiseInventory::create([
                    'merchandise_id' => $Merchandise->id,
                    'color_id' => $color_id,
                    'size_id' => $sid,
                ]);
            }   
        }

        /* Delete removed Inventories */
        $removedInventories = MerchandiseInventory::where('merchandise_id', $Merchandise->id)->whereIn('color_id', $remove)->get();
        foreach ($removedInventories as $inventory) {
            $inventory->delete();
        }

        /* Delete photos on S3 */
        foreach($remove as $cid) {
            $mid_name = sprintf("%06d", $Merchandise->id);
            $cid_name = sprintf("%02d", $cid);
            $path = '/i/'.$mid_name.'/'.$mid_name.$cid_name.'.jpeg';
            if(Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        }

        /* Store uploaded photos */
        foreach($Merchandise->inventoryByColors as $key => $inventory) {
            if($request->hasFile('photo_'.$inventory->color_id)) {
                $mid = sprintf("%06d", $Merchandise->id);
                $cid = sprintf("%02d", $inventory->color_id);
                Storage::disk('s3')->putFileAs('/i/'.$mid, $request->file('photo_'.$inventory->color_id), $mid.$cid.'.jpeg');
            }
        }
        
        $Merchandise->save();

        $request->session()->flash('alert', [
            'type' => 'success',
            'message' => 'Updated successfully',
        ]);

        return redirect('/admin/merchandise/'.$id.'/edit');
    }
}
