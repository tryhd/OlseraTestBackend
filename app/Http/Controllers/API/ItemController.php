<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Tax;
use App\Models\ItemTax;
use Illuminate\Support\Facades\DB;
class ItemController extends Controller
{
    //
    public function index(){
        $item = Item::get();
        return response()->json([
            'data' => $item,
            'code' => 200,
            'message' => 'data items'
        ], 200);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|min:3'
        ]);

        try{
            DB::beginTransaction();
            $item = Item::create([
                'name' => $request->name,
            ]);
            $item_id = $item->id;
            $tax = Tax::get();
            foreach ($tax as $row){
                $itemTax = new  ItemTax;
                $itemTax->item_id = $item_id;
                $itemTax->tax_id = $row->id;
                $itemTax->save();
            }
            DB:Commit();
            return response()->json([
                'code' => 200,
                'message' => 'berhasil simpan data',
                'data' => $item,
            ], 200);
        }catch(\Exception $e){
            DB::Rollback();
            return response()->json([
                'code' => 400,
                'message' => 'Gagal simpan data',
                'data' => $item,
            ], 400);
        }
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => 'required|min:3'
        ]);
       try{
           DB::beginTransaction();
           $item = Item::where('id',$id)->first();
           if(!empty($item)){
               $item->name = $request->name;
               $item->save();
               DB::Commit();
               return response()->json([
                   'code' => 200,
                   'message' => 'Berhasil update data',
                   'data' => $item,
            ], 200);
           }else{
               DB::Rollback();
               return response()->json([
                   'code' => 400,
                   'message' => 'Data tidak ditemukan',
                   'data' => $item,
                ], 400);
            }
        }catch(\Exception $e){
           DB::Rollback();
            return response()->json([
                'code' => 400,
                'message' => 'Gagal update data',
                'data' => $item,
            ], 400);
       }

    }

    public function delete($id){
        $item = Item::where('id',$id)->first();
        if($item->taxes()->exists()){
            return response()->json([
                'code' => 400,
                'message' => 'Gagal hapus data karena memiliki relasi yang diperlukan',
                'data' => $item,
            ], 400);
        }
        $item->delete();
        return response()->json([
            'code' => 200,
            'message' => 'Berhasil hapus data',
            'data' => $item,
        ], 200);
    }
}
