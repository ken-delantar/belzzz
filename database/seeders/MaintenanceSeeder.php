<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample maintenance records
        $maintenances = [
            [
                'vehicle_id' => 1, // Make sure this vehicle exists
                'created_by' => 1, // Make sure this user exists
                'description' => 'Regular oil change',
                'maintenance_date' => Carbon::now()->subDays(10),
                'maintenance_type' => 'oil change',
                'cost' => 1500.00,
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_id' => 2,
                'created_by' => 2,
                'description' => 'Brake system check and replacement',
                'maintenance_date' => Carbon::now()->subDays(5),
                'maintenance_type' => 'brake check',
                'cost' => 3200.00,
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_id' => 3,
                'created_by' => 1,
                'description' => 'Tire replacement (all 4 tires)',
                'maintenance_date' => Carbon::now()->subDays(2),
                'maintenance_type' => 'tire replacement',
                'cost' => 8000.00,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('maintenances')->insert($maintenances);
    }
}
