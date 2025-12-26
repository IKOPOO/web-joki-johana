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
    $packages = Package::all();

    return view('home', compact('studios', 'packages'));
  }
}
