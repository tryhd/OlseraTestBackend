<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use App\Models\Item;
use App\Models\ItemTax;
use Illuminate\Support\Facades\DB;
class ItemTaxController extends Controller
{
    //
    public function show($id){
        // $item = Item::where('items.id',$id)
        // ->Join('item_taxes', 'items.id', '=','item_taxes.item_id')
        // ->Join('taxes', 'taxes.id', '=', 'item_taxes.tax_id')
        // ->Select('items.id','items.name')->SelectRaw('GROUP_CONCAT(taxes.id,taxes.name,taxes.rate) as tax')
        // ->get();


        $item = DB::select("SELECT items.`id`, items.`name`,
        GROUP_CONCAT(JSON_OBJECT('id', taxes.`id`, 'name', taxes.`name`, 'rate', taxes.`rate`))AS Taxes
        FROM item_taxes
        JOIN items ON items.`id` = item_taxes.`item_id`
        JOIN taxes ON taxes.`id` = item_taxes.`tax_id`
        WHERE items.`id` = ".$id."
        GROUP BY items.`id`, items.`name`");
        // dd($item);
        $test=[];
        if($item){
            return response()->json([
                'code' => 200,
                'message' => 'data item tax',
                'data' => $item,
            ],200);
        }else{
            return response()->json([
                'code' => 400,
                'message' => 'Data tidak ditemukan',
                'data' => $item,
            ],400);
        }
    }
}
