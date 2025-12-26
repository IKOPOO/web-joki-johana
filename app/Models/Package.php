<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{

  use HasFactory;

  protected $fillable = [
    'studio_id',
    'name',
    'description',
    'duration_minutes',
    'price',
    'is_active',
  ];

  // Paket milik satu studio
  public function studio()
  {
    return $this->belongsTo(Studio::class);
  }

  // Paket bisa dipakai di banyak booking
  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
}
