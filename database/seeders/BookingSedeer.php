<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Studio;
use App\Models\Package;
use Carbon\Carbon;

class BookingSedeer extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ambil ID yang valid
    $userIds = User::pluck('id')->toArray();
    $studioIds = Studio::pluck('id')->toArray();

    // Generate 50 booking (bebas mau berapa)
    for ($i = 0; $i < 50; $i++) {

      // 1️⃣ Pilih studio dulu
      $studioId = fake()->randomElement($studioIds);

      // 2️⃣ Ambil package yang MILIK studio tersebut
      $package = Package::where('studio_id', $studioId)
        ->inRandomOrder()
        ->first();

      // Kalau studio belum punya package → skip
      if (!$package) {
        continue;
      }

      DB::table('tbl_booking')->insert([
        'user_id' => fake()->randomElement($userIds),
        'studio_id' => $studioId,
        'package_id' => $package->id,
        'booking_datetime' => Carbon::now()->addDays(rand(1, 30)),
        'note' => fake()->optional()->sentence(),
        'status' => fake()->randomElement([
          'PENDING',
          'CONFIRMED',
          'DONE',
          'CANCELLED'
        ]),
        'total_price' => $package->price,
        'payment_status' => fake()->randomElement([
          'PAID',
          'UNPAID'
        ]),
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
