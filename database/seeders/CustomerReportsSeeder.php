<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerReportsSeeder extends Seeder
{
    public function run()
    {
        DB::table('reports')->insert([
            [
                'report_by' => 1,
                'comment' => 'The bus was late by 30 minutes.',
                'rating' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'report_by' => 2,
                'comment' => 'Excellent service! The driver was very professional.',
                'rating' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'report_by' => 1,
                'comment' => 'The bus was clean and comfortable.',
                'rating' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'report_by' => 2,
                'comment' => 'The online booking system was confusing.',
                'rating' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
