<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Carbon\Carbon;

class StaffController extends Controller
{
  public function dashboard()
  {
    $user = auth()->user();
    $studioId = $user->studio_id;

    if (!$studioId) {
      abort(403, 'Anda tidak terdaftar di studio manapun.');
    }

    // 1. Summary Cards
    $total_booking = Booking::where('studio_id', $studioId)->count();

    $today_booking = Booking::where('studio_id', $studioId)
      ->whereDate('created_at', Carbon::today())
      ->count();

    $total_revenue = Booking::where('studio_id', $studioId)
      ->where('payment_status', 'PAID')
      ->sum('total_price');

    // Status Breakdown
    // We manually map to ensure all keys exist or we just pass the collection
    $rawStatus = Booking::where('studio_id', $studioId)
      ->selectRaw('status, count(*) as total')
      ->groupBy('status')
      ->pluck('total', 'status')
      ->toArray();

    // Ensure defaults
    $status = [
      'PENDING' => $rawStatus['PENDING'] ?? 0,
      'CONFIRMED' => $rawStatus['CONFIRMED'] ?? 0,
      'CANCELLED' => $rawStatus['CANCELLED'] ?? 0,
      'COMPLETED' => $rawStatus['COMPLETED'] ?? 0, // dynamic if other statuses exist
    ];

    // 2. Charts Data (Last 6 Months)
    $booking_per_month = [];
    $revenue_per_month = [];
    $month_labels = [];

    for ($i = 5; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $monthName = $date->format('M Y');
      $monthKey = $date->format('m');
      $yearKey = $date->format('Y');

      $bookingCount = Booking::where('studio_id', $studioId)
        ->whereYear('booking_datetime', $yearKey)
        ->whereMonth('booking_datetime', $monthKey)
        ->count();

      $revenueSum = Booking::where('studio_id', $studioId)
        ->where('payment_status', 'PAID')
        ->whereYear('booking_datetime', $yearKey)
        ->whereMonth('booking_datetime', $monthKey)
        ->sum('total_price');

      $month_labels[] = $monthName;
      $booking_per_month[] = $bookingCount;
      $revenue_per_month[] = $revenueSum;
    }

    // Pass all data to view
    return view('staff.dashboard', compact(
      'total_booking',
      'today_booking',
      'total_revenue',
      'status',
      'month_labels',
      'booking_per_month',
      'revenue_per_month'
    ));
  }

  public function preview()
  {
    $user = auth()->user();
    $studio = $user->studio;

    if (!$studio) {
      abort(403, 'Anda tidak terdaftar di studio manapun.');
    }

    $stats = [
      'total_booking' => Booking::where('studio_id', $studio->id)->count(),
      'this_month' => Booking::where('studio_id', $studio->id)
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->count(),
      'confirmed' => Booking::where('studio_id', $studio->id)->where('status', 'CONFIRMED')->count(),
      'total_revenue' => Booking::where('studio_id', $studio->id)->where('payment_status', 'PAID')->sum('total_price'),
    ];

    $booking_url = route('customer.booking.create', $studio->id);

    return view('staff.studio.preview', compact('studio', 'stats', 'booking_url'));
  }

  public function updateStudio(\Illuminate\Http\Request $request)
  {
    $user = auth()->user();
    $studio = $user->studio; // Assuming relationship exists

    if (!$studio) {
      abort(403);
    }

    $request->validate([
      'name' => 'required|string|max:100',
      'address' => 'required|string|max:100',
      'city' => 'required|string|max:50',
      'description' => 'nullable|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
      'name' => $request->name,
      'address' => $request->address,
      'city' => $request->city,
      'description' => $request->description,
    ];

    if ($request->hasFile('image')) {
      // Delete old image if exists? (Optional, maybe later)
      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('uploads/studios'), $imageName);
      $data['image'] = 'uploads/studios/' . $imageName;
    }

    $studio->update($data);

    return redirect()->back()->with('success', 'Informasi studio berhasil diperbarui.');
  }
}
