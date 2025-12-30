<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSlot extends Model
{
  use HasFactory;

  protected $fillable = [
    'studio_id',
    'date',
    'start_time',
    'end_time',
    'is_active',
  ];

  protected $casts = [
    'date' => 'date',
    'is_active' => 'boolean',
  ];

  public function studio()
  {
    return $this->belongsTo(Studio::class);
  }
}
