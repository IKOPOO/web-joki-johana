<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Booking;
use App\Models\Studio;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'phone',
    'role',
    'studio_id',
    'password',
    'status',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  // Customer / Staff → banyak booking
  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }

  // Staff → milik satu studio
  public function studio()
  {
    return $this->belongsTo(Studio::class);
  }

}
