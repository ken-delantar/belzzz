<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample vehicle records
        $vehicles = [
            [
                'vehicle_number' => 'TRK-001',
                'truck_type' => 'Cargo Truck',
                'route_from' => 'Manila',
                'route_to' => 'Laog',
                'total_capacity' => 10000,
                'available_capacity' => 8000,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Brake pads, Oil filter',
                'maintenance_record' => 'Oil changed on 2025-03-10',
                'fuel_consumption' => '15 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'TRK-002',
                'truck_type' => 'Refrigerated Truck',
                'route_from' => 'Laog',
                'route_to' => 'Manila',
                'total_capacity' => 8000,
                'available_capacity' => 5000,
                'status' => 'maintenance',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Coolant, Tires',
                'maintenance_record' => 'Brake check on 2025-03-15',
                'fuel_consumption' => '12 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'vehicle_number' => 'TRK-003',
                'truck_type' => 'Tanker Truck',
                'route_from' => 'Manila',
                'route_to' => 'Loag',
                'total_capacity' => 12000,
                'available_capacity' => 10000,
                'status' => 'ready',
                'last_updated' => Carbon::now(),
                'available_parts' => 'Fuel pump, Radiator',
                'maintenance_record' => 'Tire replacement on 2025-03-18',
                'fuel_consumption' => '10 km/l',
                'isDeleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}
