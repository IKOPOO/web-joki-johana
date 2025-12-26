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
  ];


  // Studio punya banyak paket
  public function packages()
  {
    return $this->hasMany(Package::class);
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
}
