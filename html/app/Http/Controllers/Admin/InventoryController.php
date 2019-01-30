<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MerchandiseInventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
    }

    public function index(Request $request) {
        if($request->has('method')) {
            Validator::make($request->all(), [
                'method' => 'required | in:product_id,name',
                'input' => 'required'
            ])->validate();

            $inventories = MerchandiseInventory::where($request->method,'like','%'.$request->input.'%')
                ->join('merchandises', 'merchandise_inventory.merchandise_id', 'merchandises.id')->get();
        } else {
            $inventories = MerchandiseInventory::all();
        }

        $binding = [
            'inventories' => $inventories,
        ];
        return view('admin.inventory.index', $binding);
    }

    public function inStock(Request $request, $id) {
        Validator::make($request->all(), [
            'amount' => 'required | min:1 | integer',
        ])->validate();
        $Inventory = MerchandiseInventory::where('product_id', $id)->firstOrFail();
        try {
            DB::beginTransaction();
            $Inventory->amount += $request->amount;
            $Inventory->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);
            return "failed";
        }
        return "success";
    }
}
