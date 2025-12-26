<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Package;
use Illuminate\Http\Request;

class StudioPackageController extends Controller
{
  public function index(Request $request)
  {
    // Stats
    $totalStudios = Studio::count();
    $activeStudios = Studio::where('status', 'active')->count();
    $totalPackages = Package::count();
    $activePackages = Package::where('is_active', true)->count();

    // Studios Data with filter
    $query = Studio::withCount('packages')->orderBy('created_at', 'desc');

    // Apply status filter if provided
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    $studios = $query->paginate(5)->withQueryString();

    return view('admin.sourcePackage', compact(
      'totalStudios',
      'activeStudios',
      'totalPackages',
      'activePackages',
      'studios'
    ));
  }

  /**
   * Store a new studio
   */
  public function storeStudio(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'address' => 'required|string',
      'city' => 'required|string|max:100',
      'status' => 'required|in:active,inactive',
    ]);

    try {
      Studio::create($validated);
      return redirect()->route('admin.studios.index')
        ->with('success', 'Studio berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()->route('admin.studios.index')
        ->with('error', 'Gagal menambahkan studio: ' . $e->getMessage());
    }
  }

  /**
   * Update a studio
   */
  public function updateStudio(Request $request, Studio $studio)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'address' => 'required|string',
      'city' => 'required|string|max:100',
      'status' => 'required|in:active,inactive',
    ]);

    try {
      $studio->update($validated);
      return redirect()->route('admin.studios.index')
        ->with('success', 'Studio berhasil diperbarui!');
    } catch (\Exception $e) {
      return redirect()->route('admin.studios.index')
        ->with('error', 'Gagal memperbarui studio: ' . $e->getMessage());
    }
  }

  /**
   * Delete a studio (cascade deletes all packages)
   */
  public function destroyStudio(Studio $studio)
  {
    try {
      $packageCount = $studio->packages()->count();
      $studio->delete(); // Cascade delete is handled by foreign key

      $message = 'Studio berhasil dihapus!';
      if ($packageCount > 0) {
        $message .= " ({$packageCount} package juga terhapus)";
      }

      return redirect()->route('admin.studios.index')
        ->with('success', $message);
    } catch (\Exception $e) {
      return redirect()->route('admin.studios.index')
        ->with('error', 'Gagal menghapus studio: ' . $e->getMessage());
    }
  }

  /**
   * Helper to check ownership
   */
  private function checkOwnership($studio)
  {
    $user = auth()->user();
    if ($user->role === 'STUDIO_STAF' && $user->studio_id !== $studio->id) {
      abort(403, 'Unauthorized action.');
    }
  }

  /**
   * Show packages page for a specific studio
   */
  public function showPackages(Request $request, Studio $studio)
  {
    $this->checkOwnership($studio);

    $query = $studio->packages()->orderBy('created_at', 'desc');

    // Apply status filter
    if ($request->filled('status')) {
      $isActive = $request->status === 'active';
      $query->where('is_active', $isActive);
    }

    $packages = $query->paginate(5)->withQueryString();

    return view('admin.packages', compact('studio', 'packages'));
  }

  /**
   * Store a new package
   */
  public function storePackage(Request $request, Studio $studio)
  {
    $this->checkOwnership($studio);

    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'description' => 'required|string',
      'duration_minutes' => 'required|integer|min:1',
      'price' => 'required|integer|min:0',
      'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    try {
      $studio->packages()->create($validated);
      return redirect()->route('admin.packages.index', $studio)
        ->with('success', 'Package berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()->route('admin.packages.index', $studio)
        ->with('error', 'Gagal menambahkan package: ' . $e->getMessage());
    }
  }

  /**
   * Update a package
   */
  public function updatePackage(Request $request, Studio $studio, Package $package)
  {
    $this->checkOwnership($studio);

    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'description' => 'required|string',
      'duration_minutes' => 'required|integer|min:1',
      'price' => 'required|integer|min:0',
      'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $package->update($validated);

    return redirect()->route('admin.packages.index', $studio)
      ->with('success', 'Package berhasil diperbarui!');
  }

  /**
   * Delete a package
   */
  public function destroyPackage(Studio $studio, Package $package)
  {
    $this->checkOwnership($studio);

    $package->delete();

    return redirect()->route('admin.packages.index', $studio)
      ->with('success', 'Package berhasil dihapus!');
  }
}
