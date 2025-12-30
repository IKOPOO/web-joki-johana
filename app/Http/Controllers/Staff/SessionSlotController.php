<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SessionSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionSlotController extends Controller
{
  public function index(Request $request)
  {
    $user = auth()->user();
    $date = $request->get('date', date('Y-m-d'));

    $sessionSlots = SessionSlot::where('studio_id', $user->studio_id)
      ->where('date', $date)
      ->orderBy('start_time')
      ->get();

    return view('staff.session-slots.index', compact('sessionSlots', 'date'));
  }

  public function generate(Request $request)
  {
    $user = auth()->user();

    $request->validate([
      'date' => ['required', 'date'],
      'start_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
      'end_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/', 'after:start_time'],
      'duration' => ['required', 'integer', 'min:5'],
    ]);

    $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
    $endTime = Carbon::parse($request->date . ' ' . $request->end_time);
    $duration = (int) $request->duration;

    $currentSlotStart = $startTime->copy();
    $slotsCreated = 0;

    while ($currentSlotStart->copy()->addMinutes($duration)->lte($endTime)) {
      $currentSlotEnd = $currentSlotStart->copy()->addMinutes($duration);

      SessionSlot::create([
        'studio_id' => $user->studio_id,
        'date' => $request->date,
        'start_time' => $currentSlotStart->format('H:i'),
        'end_time' => $currentSlotEnd->format('H:i'),
        'is_active' => true,
      ]);

      $currentSlotStart->addMinutes($duration);
      $slotsCreated++;
    }

    return redirect()->back()->with('success', "Berhasil membuat $slotsCreated slot sesi untuk tanggal $request->date.");
  }

  public function reset(Request $request)
  {
    $user = auth()->user();

    $request->validate([
      'start_date' => ['required', 'date'],
      'end_date' => ['required', 'date', 'after_or_equal:start_date'],
    ]);

    $deletedCount = SessionSlot::where('studio_id', $user->studio_id)
      ->whereBetween('date', [$request->start_date, $request->end_date])
      ->delete();

    return redirect()->back()->with('success', "Berhasil menghapus $deletedCount slot sesi dari tanggal $request->start_date sampai $request->end_date.");
  }

  public function store(Request $request)
  {
    $user = auth()->user();

    $request->validate([
      'date' => ['required', 'date'],
      'start_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
      'end_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/', 'after:start_time'],
    ]);

    SessionSlot::create([
      'studio_id' => $user->studio_id,
      'date' => $request->date,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
      'is_active' => true,
    ]);

    return redirect()->back()->with('success', 'Slot sesi berhasil ditambahkan.');
  }

  public function update(Request $request, SessionSlot $sessionSlot)
  {
    $user = auth()->user();
    if ($sessionSlot->studio_id !== $user->studio_id) {
      abort(403);
    }

    $request->validate([
      'date' => ['required', 'date'],
      'start_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
      'end_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/', 'after:start_time'],
    ]);

    // Calculate shift in minutes based on END TIME (to propagate duration changes)
    $oldDate = Carbon::parse($sessionSlot->date)->format('Y-m-d');
    $oldEnd = Carbon::parse($oldDate . ' ' . $sessionSlot->end_time);
    $newEnd = Carbon::parse($request->date . ' ' . $request->end_time);
    $shiftMinutes = $oldEnd->diffInMinutes($newEnd, false);

    // Get subsequent slots on the same day if date hasn't changed
    $subsequentSlots = collect();
    if ($oldDate === $request->date) {
      $subsequentSlots = SessionSlot::where('studio_id', $user->studio_id)
        ->where('date', $sessionSlot->date)
        ->where('start_time', '>', $sessionSlot->start_time)
        ->orderBy('start_time')
        ->get();
    }

    // Update the target slot
    $sessionSlot->update([
      'date' => $request->date,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
    ]);

    // Update subsequent slots
    foreach ($subsequentSlots as $slot) {
      $slotDate = Carbon::parse($slot->date)->format('Y-m-d');
      $slotStart = Carbon::parse($slotDate . ' ' . $slot->start_time);
      $slotEnd = Carbon::parse($slotDate . ' ' . $slot->end_time);

      $slot->update([
        'start_time' => $slotStart->addMinutes($shiftMinutes)->format('H:i'),
        'end_time' => $slotEnd->addMinutes($shiftMinutes)->format('H:i'),
      ]);
    }

    return redirect()->back()->with('success', 'Slot sesi berhasil diperbarui dan jadwal berikutny telah disesuaikan.');
  }

  public function destroy(SessionSlot $sessionSlot)
  {
    $user = auth()->user();
    if ($sessionSlot->studio_id !== $user->studio_id) {
      abort(403);
    }

    $sessionSlot->delete();

    return redirect()->back()->with('success', 'Slot sesi berhasil dihapus.');
  }

  public function toggleStatus(SessionSlot $sessionSlot)
  {
    $user = auth()->user();
    if ($sessionSlot->studio_id !== $user->studio_id) {
      abort(403);
    }

    $sessionSlot->update([
      'is_active' => !$sessionSlot->is_active,
    ]);

    return redirect()->back()->with('success', 'Status slot sesi berhasil diubah.');
  }
}
