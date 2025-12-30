<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\OperationalHour;
use Illuminate\Http\Request;

class OperationalController extends Controller
{
  public function index()
  {
    $user = auth()->user();
    $studioId = $user->studio_id;

    if (!$studioId) {
      abort(403, 'Anda tidak terdaftar di studio manapun.');
    }

    // Get operational hours, if empty, seed with defaults
    $operationalHours = OperationalHour::where('studio_id', $studioId)
      ->orderBy('day_of_week')
      ->get();

    if ($operationalHours->isEmpty()) {
      for ($i = 0; $i <= 6; $i++) {
        OperationalHour::create([
          'studio_id' => $studioId,
          'day_of_week' => $i,
          'opening_time' => '09:00:00',
          'closing_time' => '21:00:00',
          'is_closed' => false,
        ]);
      }
      $operationalHours = OperationalHour::where('studio_id', $studioId)
        ->orderBy('day_of_week')
        ->get();
    }

    return view('staff.operational.index', compact('operationalHours'));
  }

  public function update(Request $request)
  {
    $user = auth()->user();
    $studioId = $user->studio_id;

    $request->validate([
      'hours' => 'required|array',
      'hours.*.opening_time' => ['nullable', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
      'hours.*.closing_time' => ['nullable', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
    ], [
      'hours.*.opening_time.regex' => 'Format jam buka harus HH:mm (contoh: 09:00).',
      'hours.*.closing_time.regex' => 'Format jam tutup harus HH:mm (contoh: 21:00).',
    ]);

    foreach ($request->hours as $id => $data) {
      $hour = OperationalHour::where('id', $id)
        ->where('studio_id', $studioId)
        ->first();

      if ($hour) {
        $hour->update([
          'opening_time' => $data['opening_time'] ?? null,
          'closing_time' => $data['closing_time'] ?? null,
          'is_closed' => isset($data['is_closed']),
        ]);
      }
    }

    return redirect()->back()->with('success', 'Jam operasional berhasil diperbarui.');
  }
}
