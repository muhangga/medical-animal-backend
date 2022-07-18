<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            DB::table('clinic')->insert([
                'clinic_name' => $faker->name,
                'address' => $faker->address,
                'phone_number' => $faker->phoneNumber,
                'path_image' => $faker->imageUrl(640, 480, 'business'),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]);
        }
    }
}
