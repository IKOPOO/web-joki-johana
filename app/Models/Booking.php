<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
  use HasFactory;

  protected $table = 'tbl_booking';

  protected $fillable = [
    'user_id',
    'studio_id',
    'package_id',
    'slot_id', // Add this
    'booking_datetime',
    'note',
    'status',
    'total_price',
    'payment_status',
    'payment_method',
    'payment_proof',
  ];

  protected $casts = [
    'booking_datetime' => 'datetime',
  ];


  // Booking milik user
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Booking milik studio
  public function studio()
  {
    return $this->belongsTo(Studio::class);
  }

  // Booking milik paket
  public function package()
  {
    return $this->belongsTo(Package::class);
  }

  // Booking linked to slot (optional/nullable in older bookings)
  public function sessionSlot()
  {
    // Since tbl_booking might not have slot_id foreign key or it was added recently?
    // Wait, in the controller store method: 
    // $slot = SessionSlot::findOrFail($request->session_slot_id);
    // But in the create call:
    // Booking::create([ ..., 'booking_datetime' => ... ]); 
    // It DOES NOT save slot_id in the create array!

    // Let's check the schema of tbl_booking to see if there IS a slot_id column.
    // If not, we can't have a direct relationship unless we add the column or infer it.
    // BUT the user's error message says "Call to undefined relationship [sessionSlot]".
    // I should check if I missed adding slot_id to the fillable and the create method first?
    // Actually checking schema is safest.
    return $this->belongsTo(SessionSlot::class, 'slot_id');
  }
}
