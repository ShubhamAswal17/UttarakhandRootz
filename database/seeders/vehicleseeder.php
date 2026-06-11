<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

class vehicleseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
    for($i=1; $i<=10; $i++){
        $vehicle = new Vehicle();
    $vehicle->user_id = 1;
    $vehicle->vehicle_name = $faker->name();
    $vehicle->vehicle_type = $faker->randomElement(['Maruti', 'Thar Roxx', 'Himaliyan', 'Ntorq', 'Activa', 'Splender']);
    $vehicle->seating_capacity = $faker->randomElement([2, 5, 7]);
    $vehicle->additional_features = $faker->randomElement(['GPS',  'Bluetooth','Air Conditioning', 'Disk Brake']);
    $vehicle->registration_number = $faker->unique()->bothify('??##??####');
    $vehicle->brand =$faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Audi']);
    $vehicle->model =$faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Royal Enfield', 'Scooter']);
    $vehicle->fuel_type = $faker->randomElement(['Petrol', 'Diesel', 'Electric']);
    $vehicle->rate_per_hour = $faker->numberBetween(100, 500);
    $vehicle->rate_max_8hour = $faker->numberBetween(500, 2000);
    $vehicle->rate_per_day = $faker->numberBetween(1500, 3000);
    $vehicle->vehicle_image = $faker->imageUrl(640, 480, 'transport', true);
    $vehicle->description = $faker->paragraph();
    $vehicle->branch = $faker->randomElement(['Nainital', 'Ramnagar', 'Haldwani', 'Dehradun', 'Haridwar']);
    $vehicle->insurance_upto = $faker->dateTimeBetween('now', '+1 year');
    $vehicle->save();
    }
    }
}
