<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Package;
use App\Models\Booking;
use App\Models\SessionSlot;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
  public function index()
  {
    $studios = Studio::with('packages')->where('status', 'ACTIVE')->get();

    // Get packages with booking count
    $packages = Package::withCount('bookings')
      ->orderBy('bookings_count', 'desc')
      ->get();

    // Get best seller package ID
    $bestSellerPackageId = $packages->first()?->id;

    return view('customer.booking.index', compact('studios', 'packages', 'bestSellerPackageId'));
  }

  public function create(Studio $studio)
  {
    $packages = Package::where('studio_id', $studio->id)
      ->where('is_active', true)
      ->orderBy('price')
      ->get();

    // Fetch active slots for today/future? 
    // Ideally we fetch slots via AJAX based on date selection, but for now passing empty or basic setup.

    return view('customer.booking.create', compact('studio', 'packages'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'studio_id' => 'required|exists:studios,id',
      'package_id' => 'required|exists:packages,id',
      'date' => 'required|date|after_or_equal:today',
      'session_slot_id' => 'required|exists:session_slots,id',
      'note' => 'nullable|string|max:500',
    ]);

    $slot = SessionSlot::findOrFail($request->session_slot_id);

    // Check if slot matches date and studio
    if (\Carbon\Carbon::parse($slot->date)->format('Y-m-d') !== $request->date || $slot->studio_id != $request->studio_id) {
      return back()->withErrors(['session_slot_id' => 'Slot sesi tidak valid. Data tidak cocok.']);
    }

    // Check availability against existing bookings
    $exists = Booking::where('studio_id', $request->studio_id)
      ->where('booking_datetime', \Carbon\Carbon::parse($slot->date)->format('Y-m-d') . ' ' . $slot->start_time)
      ->whereIn('status', ['PENDING', 'CONFIRMED', 'PAID'])
      ->exists();

    if ($exists) {
      return back()->withErrors(['session_slot_id' => 'Sesi ini sudah dibooking.']);
    }

    $package = Package::findOrFail($request->package_id);

    // Create booking instance
    $booking = Booking::create([
      'user_id' => auth()->id(),
      'studio_id' => $request->studio_id,
      'package_id' => $request->package_id,
      'slot_id' => $request->session_slot_id, // Add this line
      'booking_datetime' => \Carbon\Carbon::parse($slot->date)->format('Y-m-d') . ' ' . $slot->start_time,
      'total_price' => $package->price,
      'status' => 'PENDING',
      'note' => $request->note,
      'payment_status' => 'UNPAID',
    ]);

    return redirect()->route('customer.booking.payment', $booking->id);
  }

  public function print(Booking $booking)
  {
    if ($booking->user_id !== auth()->id()) {
      abort(403);
    }

    // Load relationships if needed, though route binding might not load them all by default unless defined in model.
    // Better to explicitly load them to be safe for the view.
    $booking->load(['studio', 'package', 'sessionSlot', 'user']);

    return view('customer.booking.print', compact('booking'));
  }

  public function payment(Booking $booking)
  {
    if ($booking->user_id !== auth()->id()) {
      abort(403);
    }

    if ($booking->payment_status === 'PAID') {
      return redirect()->route('customer.reservations.index');
    }

    return view('customer.booking.payment', compact('booking'));
  }

  public function processPayment(Request $request, Booking $booking)
  {
    if ($booking->user_id !== auth()->id()) {
      abort(403);
    }

    $request->validate([
      'payment_method' => 'required|string',
      'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $proofPath = null;
    if ($request->hasFile('payment_proof')) {
      $file = $request->file('payment_proof');
      $filename = time() . '_' . $booking->id . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads/payment_proofs'), $filename);
      $proofPath = 'uploads/payment_proofs/' . $filename;
    }

    // Process payment and update booking
    $booking->update([
      'payment_status' => 'UNPAID', // Waiting for verification
      'status' => 'PENDING',
      'payment_method' => $request->payment_method,
      'payment_proof' => $proofPath,
    ]);

    return redirect()->route('customer.reservations.index')->with('success', 'Pembayaran berhasil! Reservasi Anda telah dikonfirmasi.');
  }

  public function getSlots(Request $request)
  {
    $request->validate([
      'studio_id' => 'required|exists:studios,id',
      'date' => 'required|date',
    ]);

    $slots = SessionSlot::where('studio_id', $request->studio_id)
      ->whereDate('date', $request->date) // Assuming date column exists as per recent migration
      ->where('is_active', true)
      ->orderBy('start_time')
      ->get();

    // Check booked status for each slot
    $bookings = Booking::where('studio_id', $request->studio_id)
      ->whereDate('booking_datetime', $request->date)
      ->whereIn('status', ['PENDING', 'CONFIRMED', 'PAID']) // Exclude cancelled
      ->get();

    $slots = $slots->map(function ($slot) use ($bookings) {
      $isBooked = $bookings->contains(function ($booking) use ($slot) {
        // Compare time
        return Carbon::parse($booking->booking_datetime)->format('H:i:s') === $slot->start_time;
      });

      $slot->is_booked = $isBooked;
      // Format times for display
      $slot->display_time = Carbon::parse($slot->start_time)->format('H:i') . ' - ' . Carbon::parse($slot->end_time)->format('H:i');
      return $slot;
    });

    // Filter out booked slots
    $slots = $slots->filter(function ($slot) {
      return !$slot->is_booked;
    })->values(); // Reset keys

    return response()->json($slots);
  }

  public function history()
  {
    $bookings = Booking::where('user_id', auth()->id())
      ->with(['studio', 'package'])
      ->orderBy('booking_datetime', 'desc')
      ->get();

    return view('customer.reservations.index', compact('bookings'));
  }

  public function profile()
  {
    $user = auth()->user();
    return view('customer.profile', compact('user'));
  }
}
