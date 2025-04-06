<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Vendor User 1',
        //     'email' => 'vendor1@gmail.com',
        //     'role' => 'Vendor'
        // ]);

        // User::factory()->create([
        //     'name' => 'Vendor User 2',
        //     'email' => 'vendor2@gmail.com',
        //     'role' => 'Vendor'
        // ]);

        // User::factory()->create([
        //     'name' => 'Fariñas Admin',
        //     'email' => 'admin@logistic.com',
        //     'password' => 'W5`D[8wqu04I',
        //     'role' => 'Admin'
        // ]);

        User::factory()->create([
            'name' => 'Ms. Gloria Bennett S. Charity',
            'email' => 'secretary@logistic.com',
            'password' => 'n4*9o#I1eWR1zOw5?id-',
            'role' => 'Secretary'
        ]);

        // Vendor::factory()->create([
        //     'user_id' => 1,
        //     'firstname' => 'Vendor',
        //     'middlename' => '',  // Optional middle name
        //     'lastname' => 'User 1',
        //     'address' => 'West',
        //     'contact_info' => '09099876652',
        // ]);
        // Vendor::factory()->create([
        //     'user_id' => 2,
        //     'firstname' => 'Vendor',
        //     'middlename' => '',  // Optional middle name
        //     'lastname' => 'User 2',
        //     'address' => 'Taga Looban',
        //     'contact_info' => '09099876654',
        // ]);


        // $this->call([
        //     VehicleSeeder::class,
        // ]);
        // $this->call([
        //     VehicleInventorySeeder::class,
        // ]);

        // $this->call([
        //     MaintenanceSeeder::class,
        // ]);
    }
}
