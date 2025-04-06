<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10), // Random user_id (adjust as needed)
            'firstname' => $this->faker->firstName(),
            'middlename' => $this->faker->optional()->firstName(),  // Optional middle name
            'lastname' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'contact_info' => $this->faker->phoneNumber(),
        ];
    }
}
