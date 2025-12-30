<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationalHour extends Model
{
  use HasFactory;

  protected $fillable = [
    'studio_id',
    'day_of_week',
    'opening_time',
    'closing_time',
    'is_closed',
  ];

  public function studio()
  {
    return $this->belongsTo(Studio::class);
  }

  public function getDayNameAttribute()
  {
    $days = [
      0 => 'Minggu',
      1 => 'Senin',
      2 => 'Selasa',
      3 => 'Rabu',
      4 => 'Kamis',
      5 => 'Jumat',
      6 => 'Sabtu',
    ];

    return $days[$this->day_of_week] ?? 'Unknown';
  }
}
