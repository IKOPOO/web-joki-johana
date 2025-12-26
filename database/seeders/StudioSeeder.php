<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $faker = Faker::create('id_ID');

    $studios = [];

    for ($i = 1; $i <= 5; $i++) {
      $studios[] = [
        'name' => 'Studio ' . $faker->company(),
        'address' => $faker->address(),
        'city' => $faker->city(),
        'status' => rand(0, 1) ? 'active' : 'inactive',
        'created_at' => now(),
        'updated_at' => now(),
      ];
    }

    DB::table('studios')->insert($studios);
  }
}
