<?php

namespace App\Http\Controllers\API;
use App\Models\Tax;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TaxController extends Controller
{
    //
    public function index(){
        $tax = Tax::get();
        return response()->json([
            'data' => $tax,
            'code' => 200,
            'message' => 'data tax'
        ], 200);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=> 'required|min:3',
                'rate' => 'required',
        ]);
        DB::beginTransaction();
        if(!empty($request->name && $request->rate)){
            $tax = Tax::create([
                'name' =>$request->name,
                'rate' =>$request->rate,
            ]);
            DB::commit();
            return response()->json([
                'data' => $tax,
                'code' => 200,
                'message' => 'Berhasil simpan data'
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'code' => 400,
                'message' => 'Gagal simpan data'
            ], 400);
        }
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name'=> 'required|min:3',
            'rate' => 'required',
        ]);
        $tax = Tax::where('id',$id)->first();
        if(!empty($tax)){
            DB::beginTransaction();
            $tax->name = $request->name;
            $tax->rate = $request->rate;
            $tax->save();
            DB::commit();
            return response()->json([
                'data' => $tax,
                'code' => 200,
                'message' => 'Berhasil update data'
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'code' => 400,
                'message' => 'Data tidak ditemukan'
            ], 400);
        }
    }

    public function delete($id){
        $tax = Tax::where('id',$id)->first();
        if($tax->items()->exists()){
            return response()->json([
                'data' => $tax,
                'code' => 400,
                'message' => 'Gagal hapus data karena memiliki relasi yang diperlukan'
            ], 400);
        }
        $tax->delete();
        return response()->json([
            'data' => $tax,
            'code' => 200,
            'message' => 'Berhasil hapus data'
        ], 200);
    }
}
