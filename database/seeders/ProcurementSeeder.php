<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->where('id', 5)->update(['name' => 'SHILIDUO']);
        DB::table('users')->where('id', 2)->update(['name' => 'YOKOHAMA']);
        DB::table('users')->where('id', 4)->update(['name' => 'MOTOLITE EXPRESS']);

        $products = [
            [
                'vendor_id' => 2,
                'name' => 'BLUEARTH ES ES32 TIRES',
                'type' => 'items',
                'price' => 3895,
                'stock' => 20,
                'description' => 'BLUEARTH ES ES32* TIRES',
            ],
            [
                'vendor_id' => 4,
                'name' => 'MOTOLITE ENDURO BATTERIES',
                'type' => 'items',
                'price' => 5250,
                'stock' => 3,
                'description' => 'MOTOLITE ENDURO* BATTERIES',
            ],
            [
                'vendor_id' => 5,
                'name' => 'SW2628 WIPER BLADE',
                'type' => 'items',
                'price' => 260,
                'stock' => 10,
                'description' => 'SW2628* WIPER BLADE',
            ],
        ];

        DB::table('products')->insert($products);
    }
}
