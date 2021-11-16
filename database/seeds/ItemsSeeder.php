<?php

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemTax;
class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $create1 = Item::create([
            'name' => "Baju Batik",
        ]);

        for ($i=1; $i<=2; $i++){
            $itemTaxes1 =ItemTax::create([
                'item_id' => $create1->id,
                'tax_id' => $i,
            ]);
        }

        $create2 = Item::create([
            'name' => "Kemeja",
        ]);

        for ($i=1; $i<=2; $i++){
            $itemTaxes2 = ItemTax::create([
                'item_id' => $create2->id,
                'tax_id' => $i,
            ]);
        }
    }
}
