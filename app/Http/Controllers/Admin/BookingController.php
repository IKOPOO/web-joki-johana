<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Studio;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
  public function verificationIndex()
  {
    $user = auth()->user();
    $query = Booking::with(['user', 'studio', 'package'])
      ->where('payment_proof', '!=', null)
      ->where('payment_status', 'UNPAID')
      ->whereIn('status', ['PENDING', 'PENDING_VERIFICATION']) // Covers both common statuses
      ->orderBy('created_at', 'desc');

    if ($user->role === 'STUDIO_STAF') {
      $query->where('studio_id', $user->studio_id);
    }

    $bookings = $query->get();

    return view('admin.bookings.verification', compact('bookings'));
  }

  public function index(Request $request)
  {
    $user = auth()->user();
    $isStaff = $user->role === 'STUDIO_STAF';
    $studioId = $user->studio_id;

    // Base query for stats
    $statsQuery = Booking::query();

    // ENFORCE STUDIO FILTER FOR STAFF
    if ($isStaff) {
      $statsQuery->where('studio_id', $studioId);
    }

    // Stats - Booking Pending
    $pendingBookings = (clone $statsQuery)->where('status', 'PENDING')->count();
    $pendingIncome = (clone $statsQuery)->where('status', 'PENDING')->sum('total_price');

    // Stats - Booking Hari Ini
    $todayBookings = (clone $statsQuery)->whereDate('booking_datetime', Carbon::today())->count();
    $todayIncome = (clone $statsQuery)->whereDate('booking_datetime', Carbon::today())->sum('total_price');

    // Stats - Booking Bulan Ini
    $currentMonth = $request->get('month', Carbon::now()->month);
    $currentYear = Carbon::now()->year;

    $monthlyQuery = (clone $statsQuery)
      ->whereMonth('booking_datetime', $currentMonth)
      ->whereYear('booking_datetime', $currentYear);

    $monthlyBookings = (clone $monthlyQuery)->count();
    $monthlyBookings = (clone $monthlyQuery)->count();
    $monthlyIncome = (clone $monthlyQuery)->sum('total_price');

    // Pipeline Stats for Staff/Admin
    // Menunggu Bayar: Pending & Unpaid
    $waitingPayment = (clone $statsQuery)->where('status', 'PENDING')->where('payment_status', 'UNPAID')->count();
    $waitingPaymentIncome = (clone $statsQuery)->where('status', 'PENDING')->where('payment_status', 'UNPAID')->sum('total_price');

    // Verifikasi: All Pending (as requested)
    $verificationPending = (clone $statsQuery)->where('status', 'PENDING')->count();
    $verificationIncome = (clone $statsQuery)->where('status', 'PENDING')->sum('total_price');

    $confirmedBookings = (clone $statsQuery)->where('status', 'CONFIRMED')->count();
    $completedBookings = (clone $statsQuery)->where('status', 'DONE')->count();

    // Quick Stats
    $avgBookingsPerDay = $monthlyBookings / max(1, Carbon::now()->day);

    $favoriteStudio = null;
    if (!$isStaff) {
      $favoriteStudio = Booking::selectRaw('studio_id, COUNT(*) as count')
        ->groupBy('studio_id')
        ->orderByDesc('count')
        ->with('studio')
        ->first();
    }

    $favoritePackageQuery = Booking::selectRaw('package_id, COUNT(*) as count')
      ->groupBy('package_id')
      ->orderByDesc('count')
      ->with('package');

    if ($isStaff) {
      $favoritePackageQuery->where('studio_id', $studioId);
    }

    $favoritePackage = $favoritePackageQuery->first();

    // Bookings Data with filter
    $query = Booking::with(['user', 'studio', 'package']);

    // ENFORCE STUDIO FILTER FOR STAFF MAIN QUERY
    if ($isStaff) {
      $query->where('studio_id', $studioId);
    }

    $query->orderBy('booking_datetime', 'desc');

    // Apply status filter
    if ($request->filled('status')) {
      $query->where('status', strtoupper($request->status));
    }

    $bookings = $query->paginate(10)->withQueryString();

    // For dropdown options in modals
    $users = User::where('role', 'customer')->orderBy('name')->get();

    if ($isStaff) {
      $studios = Studio::where('id', $studioId)->get();
      $packages = Package::where('studio_id', $studioId)->where('is_active', true)->orderBy('name')->get();
    } else {
      $studios = Studio::where('status', 'active')->orderBy('name')->get();
      $packages = Package::where('is_active', true)->orderBy('name')->get();
    }

    return view('admin.booking', compact(
      'pendingBookings',
      'pendingIncome',
      'todayBookings',
      'todayIncome',
      'monthlyBookings',
      'monthlyIncome',
      'avgBookingsPerDay',
      'favoriteStudio',
      'favoritePackage',
      'bookings',
      'currentMonth',
      'users',
      'studios',
      'packages',
      'packages',
      'waitingPayment',
      'waitingPaymentIncome',
      'verificationPending',
      'verificationIncome',
      'confirmedBookings',
      'completedBookings'
    ));
  }

  /**
   * Store a new booking
   */
  public function store(Request $request)
  {
    $user = auth()->user();
    $isStaff = $user->role === 'STUDIO_STAF';

    $request->validate([
      'user_id' => 'required|exists:users,id',
      // If staff, we ignore studio_id input and use their own. If admin, required.
      'studio_id' => $isStaff ? 'nullable' : 'required|exists:studios,id',
      'package_id' => 'required|exists:packages,id',
      'booking_date' => 'required|date',
      'booking_time' => 'required',
      'note' => 'nullable|string',
      'status' => 'required|in:PENDING,CONFIRMED,DONE,CANCELLED',
      'payment_status' => 'required|in:PAID,UNPAID',
    ]);

    $data = $request->all();

    // Enforce studio_id for staff
    if ($isStaff) {
      $data['studio_id'] = $user->studio_id;
    }

    // Combine date and time
    $data['booking_datetime'] = $data['booking_date'] . ' ' . $data['booking_time'];
    unset($data['booking_date'], $data['booking_time']);

    // Get price from package & verify package belongs to studio
    $package = Package::where('id', $data['package_id'])
      ->where('studio_id', $data['studio_id'])
      ->first();

    if (!$package) {
      return redirect()->back()
        ->withInput()
        ->with('error', 'Paket tidak valid untuk studio ini.');
    }

    $data['total_price'] = $package->price;

    try {
      Booking::create($data);
      return redirect()->route('admin.bookings.index')
        ->with('success', 'Booking berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()->route('admin.bookings.index')
        ->with('error', 'Gagal menambahkan booking: ' . $e->getMessage());
    }
  }

  /**
   * Update a booking
   */
  public function update(Request $request, Booking $booking)
  {
    $user = auth()->user();
    $isStaff = $user->role === 'STUDIO_STAF';

    // Verify ownership for staff
    if ($isStaff && $booking->studio_id !== $user->studio_id) {
      abort(403, 'Unauthorized action.');
    }

    $request->validate([
      'user_id' => 'required|exists:users,id',
      'studio_id' => $isStaff ? 'nullable' : 'required|exists:studios,id',
      'package_id' => 'required|exists:packages,id',
      'booking_date' => 'required|date',
      'booking_time' => 'required',
      'note' => 'nullable|string',
      'status' => 'required|in:PENDING,CONFIRMED,DONE,CANCELLED',
      'payment_status' => 'required|in:PAID,UNPAID',
    ]);

    $data = $request->all();

    // Enforce studio_id for staff (cannot change studio)
    if ($isStaff) {
      $data['studio_id'] = $user->studio_id;
    }

    // Combine date and time
    $data['booking_datetime'] = $data['booking_date'] . ' ' . $data['booking_time'];
    unset($data['booking_date'], $data['booking_time']);

    // Get price from package & verify package belongs to studio
    $package = Package::where('id', $data['package_id'])
      ->where('studio_id', $data['studio_id'])
      ->first();

    if (!$package) {
      return redirect()->back()
        ->withInput()
        ->with('error', 'Paket tidak valid untuk studio ini.');
    }

    $data['total_price'] = $package->price;

    try {
      $booking->update($data);
      return redirect()->route('admin.bookings.index')
        ->with('success', 'Booking berhasil diperbarui!');
    } catch (\Exception $e) {
      return redirect()->route('admin.bookings.index')
        ->with('error', 'Gagal memperbarui booking: ' . $e->getMessage());
    }
  }

  /**
   * Delete a booking
   */
  public function destroy(Booking $booking)
  {
    $user = auth()->user();
    // Verify ownership for staff
    if ($user->role === 'STUDIO_STAF' && $booking->studio_id !== $user->studio_id) {
      abort(403, 'Unauthorized action.');
    }

    try {
      $booking->delete();
      return redirect()->route('admin.bookings.index')
        ->with('success', 'Booking berhasil dihapus!');
    } catch (\Exception $e) {
      return redirect()->route('admin.bookings.index')
        ->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
    }
  }


  /**
   * Verify Payment (Agree)
   */
  public function verifyPayment(Booking $booking)
  {
    $user = auth()->user();
    if ($user->role === 'STUDIO_STAF' && $booking->studio_id !== $user->studio_id) {
      abort(403);
    }

    $booking->update([
      'status' => 'CONFIRMED',
      'payment_status' => 'PAID'
    ]);

    return redirect()->back()->with('success', 'Pembayaran diterima. Booking dikonfirmasi.');
  }

  /**
   * Reject Payment (Cancel)
   */
  public function rejectPayment(Booking $booking)
  {
    $user = auth()->user();
    if ($user->role === 'STUDIO_STAF' && $booking->studio_id !== $user->studio_id) {
      abort(403);
    }

    $booking->update([
      'status' => 'CANCELLED',
      // User requested "rejected means cancelled", usually payment stays unpaid or can be marked as such
      // Existing migration only has PAID/UNPAID. So we keep it UNPAID or if there was 'REJECTED' enum we would use it.
      // Based on request: "reject itu jadinya cancelled"
    ]);

    return redirect()->back()->with('success', 'Pembayaran ditolak. Booking dibatalkan.');
  }
}
