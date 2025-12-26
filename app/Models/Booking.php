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
    'booking_datetime',
    'note',
    'status',
    'total_price',
    'payment_status',
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
}
