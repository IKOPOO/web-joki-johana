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

    // Stats specific to the staff's studio
    $stats = [
      'totalBookings' => Booking::where('studio_id', $studioId)->count(),
      'pendingBookings' => Booking::where('studio_id', $studioId)->where('status', 'PENDING')->count(),
      'todayBookings' => Booking::where('studio_id', $studioId)->whereDate('booking_datetime', Carbon::today())->count(),
      'monthlyBookings' => Booking::where('studio_id', $studioId)->whereMonth('booking_datetime', Carbon::now()->month)->count(),
      'totalIncome' => Booking::where('studio_id', $studioId)->where('payment_status', 'PAID')->sum('total_price'),
      'totalPackages' => Package::where('studio_id', $studioId)->count(),
    ];

    // Recent bookings for this studio
    $recentBookings = Booking::with(['user', 'package'])
      ->where('studio_id', $studioId)
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();

    // Re-use the admin dashboard view but with filtered data
    // We might want to customize the view later, but for now re-using is fine or we can create a specific one.
    // The user request implies using similar pages but customized data.
    // Let's create a specific view extending admin layout but customized headers if needed, 
    // or just pass a variable to admin dashboard to hide things not relevant for staff?
    // Actually, the request says "staff dashboard showing their studio's stats".
    // Let's try to reuse admin.dashboard generic structure or create a simplified one.
    // Given I haven't created a 'staff' folder in views yet, let's create a specific staff dashboard view.

    return view('staff.dashboard', compact('stats', 'recentBookings'));
  }
}
