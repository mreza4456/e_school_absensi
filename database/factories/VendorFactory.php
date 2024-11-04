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
            'id' => fake()->uuid(),
            'nama' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'no_telp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'provinsi_code' => 35, // Sesuaikan dengan kode provinsi yang ada
            'kota_code' => 3578, // Sesuaikan dengan kode kota yang ada
            'status' => true,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the vendor is inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => false,
            ];
        });
    }
}
