<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */

  public function run(): void
  {
    $faker = Faker::create('id_ID');

    /*ADMIN DEFAULT*/
    User::updateOrCreate(
      [
        'email' => 'admin@lensia.com',
      ],
      [
        'name' => 'Lensia Admin',
        'phone' => '081234567890',
        'role' => 'LENSIA_ADMIN',
        'studio_id' => null,
        'password' => Hash::make('admin123'),
        'status' => 'ACTIVE',
        'created_at' => now(),
        'updated_at' => now(),
      ]
    );

    /*USER DUMMY RANDOM*/
    $roles = ['CUSTOMER', 'STUDIO_STAF', 'LENSIA_ADMIN'];
    $statuses = ['ACTIVE', 'SUSPENDED'];

    $studioIds = DB::table('studios')->pluck('id')->toArray();

    for ($i = 1; $i <= 20; $i++) {

      $role = $roles[array_rand($roles)];

      User::create([
        'name' => $faker->name(),
        'email' => $faker->unique()->safeEmail(),
        'phone' => $faker->phoneNumber(),
        'role' => $role,

        'studio_id' => $role === 'STUDIO_STAF'
          ? $studioIds[array_rand($studioIds)]
          : null,

        'password' => Hash::make('password'),
        'status' => $statuses[array_rand($statuses)],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
