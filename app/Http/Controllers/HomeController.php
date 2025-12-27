<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\Package;
use Illuminate\Http\Request;


class HomeController extends Controller
{
  public function index()
  {
    $studios = Studio::with('packages')->where('status', 'active')->get();

    // Get packages with booking count
    $packages = Package::withCount('bookings')
      ->orderBy('bookings_count', 'desc')
      ->get();

    // Get best seller package ID (package with most bookings)
    $bestSellerPackageId = $packages->first()?->id;

    return view('home', compact('studios', 'packages', 'bestSellerPackageId'));
  }
}
