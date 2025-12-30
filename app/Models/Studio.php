<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studio extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'address',
    'city',
    'status',
    'description',
    'image',
  ];


  // Studio punya banyak paket
  public function packages()
  {
    return $this->hasMany(Package::class);
  }

  public function sessionSlots()
  {
    return $this->hasMany(SessionSlot::class);
  }

  // Studio punya banyak booking
  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }

  // Studio punya banyak staff
  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function operationalHours()
  {
    return $this->hasMany(OperationalHour::class);
  }
}
