<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Studio;
use App\Models\Package;
use Carbon\Carbon;

class DashboardController extends Controller
{
  public function index()
  {
    // Basic stats
    $stats = [
      'totalBookings' => Booking::count(),
      'pendingBookings' => Booking::where('status', 'PENDING')->count(),
      'todayBookings' => Booking::whereDate('booking_datetime', Carbon::today())->count(),
      'monthlyBookings' => Booking::whereMonth('booking_datetime', Carbon::now()->month)->count(),
      'totalIncome' => Booking::where('payment_status', 'PAID')->sum('total_price'),
      'totalUsers' => User::count(),
      'totalStudios' => Studio::count(),
      'totalPackages' => Package::count(),
    ];

    // 1. Booking Ratio (DONE vs CANCELLED vs OTHERS)
    $bookingRatio = [
      'done' => Booking::where('status', 'DONE')->count(),
      'cancelled' => Booking::where('status', 'CANCELLED')->count(),
      'others' => Booking::whereNotIn('status', ['DONE', 'CANCELLED'])->count(),
    ];

    // 2. Booking Per Studio (Top 5)
    $bookingPerStudio = Studio::withCount('bookings')
      ->orderBy('bookings_count', 'desc')
      ->limit(5)
      ->get()
      ->map(function ($studio) {
        return [
          'name' => $studio->name,
          'count' => $studio->bookings_count
        ];
      });

    // 3. Monthly Booking Trend (Last 12 Months)
    $monthlyTrend = collect();
    $incomeTrend = collect();
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $monthName = $date->format('M');

      // Bookings count
      $count = Booking::whereYear('booking_datetime', $date->year)
        ->whereMonth('booking_datetime', $date->month)
        ->count();

      $monthlyTrend->push([
        'month' => $monthName,
        'count' => $count
      ]);

      // Income sum (Only PAID)
      $income = Booking::whereYear('booking_datetime', $date->year)
        ->whereMonth('booking_datetime', $date->month)
        ->where('payment_status', 'PAID')
        ->sum('total_price');

      // Pending Income (UNPAID)
      $pending = Booking::whereYear('booking_datetime', $date->year)
        ->whereMonth('booking_datetime', $date->month)
        ->where('payment_status', 'UNPAID')
        ->sum('total_price');

      $incomeTrend->push([
        'month' => $monthName,
        'income' => $income,
        'pending' => $pending
      ]);
    }

    // 4. User Segmentation - Lifecycle Based (Customers Only)
    $customerQuery = User::where('role', 'CUSTOMER');
    $userSegmentation = [
      // New: Registered < 30 days (regardless of booking count)
      'new' => (clone $customerQuery)->where('created_at', '>=', Carbon::now()->subDays(30))->count(),

      // Engaged: Registered >= 30 days AND has 2+ bookings
      'engaged' => (clone $customerQuery)->where('created_at', '<', Carbon::now()->subDays(30))
        ->has('bookings', '>=', 2)->count(),

      // Casual: Registered >= 30 days AND has exactly 1 booking
      'casual' => (clone $customerQuery)->where('created_at', '<', Carbon::now()->subDays(30))
        ->has('bookings', '=', 1)->count(),

      // Dormant: Registered >= 30 days AND never booked
      'dormant' => (clone $customerQuery)->where('created_at', '<', Carbon::now()->subDays(30))
        ->doesntHave('bookings')->count(),
    ];

    // 5. Income Per Studio (Top 5) - Only PAID
    $incomePerStudio = Studio::with([
      'bookings' => function ($query) {
        $query->where('payment_status', 'PAID');
      }
    ])
      ->get()
      ->map(function ($studio) {
        return [
          'name' => $studio->name,
          'income' => $studio->bookings->sum('total_price')
        ];
      })
      ->sortByDesc('income')
      ->take(5);

    // Recent bookings for table
    $recentBookings = Booking::with(['user', 'studio', 'package'])
      ->latest()
      ->paginate(5);

    return view('admin.dashboard', compact(
      'stats',
      'recentBookings',
      'bookingRatio',
      'bookingPerStudio',
      'monthlyTrend',
      'incomePerStudio',
      'incomeTrend',
      'userSegmentation'
    ));
  }
}
