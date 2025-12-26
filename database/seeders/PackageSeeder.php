<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PackageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ambil semua studio id yang sudah ada
    $studioIds = DB::table('studios')->pluck('id');

    $now = Carbon::now();

    $packages = [];

    foreach ($studioIds as $studioId) {
      $packages[] = [
        'studio_id' => $studioId,
        'name' => 'Paket Basic',
        'description' => 'Sesi foto basic 30 menit',
        'duration_minutes' => 30,
        'price' => 150000,
        'is_active' => 1,
        'created_at' => $now,
        'updated_at' => $now,
      ];

      $packages[] = [
        'studio_id' => $studioId,
        'name' => 'Paket Standard',
        'description' => 'Sesi foto 60 menit + 5 foto edit',
        'duration_minutes' => 60,
        'price' => 300000,
        'is_active' => 1,
        'created_at' => $now,
        'updated_at' => $now,
      ];

      $packages[] = [
        'studio_id' => $studioId,
        'name' => 'Paket Premium',
        'description' => 'Sesi foto 90 menit + semua foto edit',
        'duration_minutes' => 90,
        'price' => 500000,
        'is_active' => 1,
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }

    DB::table('packages')->insert($packages);
  }
}
