<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Tax;
use App\Models\ItemTax;
use Illuminate\Support\Facades\DB;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
class ItemController extends Controller
{
    //
    public function index(){
        $itemTaxes = ItemTax::with(['item.taxes'])->paginate();

        if ($itemTaxes->total() > 0) {
            $taxArray = $itemTaxes->toArray();
            $data = $taxArray['data'];
            $fractal = new Manager();
            $resource = new Collection($data, function(array $t) {

                $tax = $t['item']['taxes'];
                $allowed = ['id', 'name', 'rate'];
                $filtered = app('array.helper')->reduceArrayMultidimensional($tax, $allowed);

                return [
                    'id' => $t['id'],
                    'name' => $t['item']['name'],
                    'rate' => $filtered
                ];
            });

            $array = $fractal->createData($resource)->toArray();
            return $array['data'];
        }


        return response()->json([
            'data' => $array,
            'code' => 200,
            'message' => 'data items'
        ], 200);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|min:3'
        ]);
        try{
            $item = new Item;
            $item->name = $request->name;
            if ($item->save()) {
                $taxesId = $request->tax;
                foreach ($taxesId as $taxId) {
                    $item->taxes()->attach([$taxId]);
                }
            }
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
