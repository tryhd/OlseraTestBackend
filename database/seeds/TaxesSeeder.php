<?php

use Illuminate\Database\Seeder;
use App\Models\Tax;
class TaxesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tax = Tax::create([
            'name' => 'pph',
            'rate' => 5,
        ]);

        $tax = Tax::create([
            'name' => 'pajak toko',
            'rate' => 10,
        ]);
    }
}
