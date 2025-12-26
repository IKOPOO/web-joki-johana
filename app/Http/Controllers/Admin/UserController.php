<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  // get all users 
  public function index(Request $request)
  {
    // Validasi ringan untuk filter
    $request->validate([
      'role' => 'nullable|in:CUSTOMER,STUDIO_STAF,LENSIA_ADMIN',
      'status' => 'nullable|in:ACTIVE,SUSPENDED',
      'search' => 'nullable|string|max:100',
    ]);

    $users = User::query()
      // Search
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
        });
      })

      // Filter Role
      ->when($request->filled('role'), function ($query) use ($request) {
        $query->where('role', $request->role);
      })

      // Filter Status
      ->when($request->filled('status'), function ($query) use ($request) {
        $query->where('status', $request->status);
      });

    // Export Logic
    if ($request->filled('export') && $request->export == 'true') {
      return $this->exportUsers($users);
    }

    $users = $users
      // Sorting & Pagination
      ->latest()
      ->paginate(5)
      ->withQueryString();

    // Calculate Stats for View
    $stats = [
      'total' => User::count(),
      'staff' => User::where('role', 'STUDIO_STAF')->count(),
      'customer' => User::where('role', 'CUSTOMER')->count(),
      'admin' => User::where('role', 'LENSIA_ADMIN')->count(),
      'active' => User::where('status', 'ACTIVE')->count(),
      'suspended' => User::where('status', 'SUSPENDED')->count(),
    ];

    // Get studios for dropdown
    $studios = \App\Models\Studio::where('status', 'active')->orderBy('name')->get();

    return view('admin.users.index', compact('users', 'stats', 'studios'));
  }

  private function exportUsers($usersQuery)
  {
    $csvFileName = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
    $headers = [
      "Content-type" => "text/csv",
      "Content-Disposition" => "attachment; filename=$csvFileName",
      "Pragma" => "no-cache",
      "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
      "Expires" => "0"
    ];

    $columns = ['ID', 'Nama', 'Email', 'No HP', 'Role', 'Status', 'Tanggal Daftar'];

    // Clone query to avoid modifying original builder if passed by reference (though Objects are by ref, cloning is safer)
    // or just use straight since we return response immediately.
    $users = $usersQuery->get();

    $callback = function () use ($users, $columns) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      foreach ($users as $user) {
        $row['ID'] = $user->id;
        $row['Nama'] = $user->name;
        $row['Email'] = $user->email;
        $row['No HP'] = "'" . $user->phone; // Prevent Excel auto-format
        $row['Role'] = $user->role;
        $row['Status'] = $user->status;
        $row['Tanggal Daftar'] = $user->created_at->format('Y-m-d H:i:s');

        fputcsv($file, array($row['ID'], $row['Nama'], $row['Email'], $row['No HP'], $row['Role'], $row['Status'], $row['Tanggal Daftar']));
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  /**
   * Store a new user
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email',
      'phone' => 'required|string|max:20',
      'password' => 'required|string|min:6',
      'role' => 'required|in:CUSTOMER,STUDIO_STAF,LENSIA_ADMIN',
      'status' => 'required|in:ACTIVE,SUSPENDED',
      'studio_id' => 'nullable|exists:studios,id|required_if:role,STUDIO_STAF',
    ]);

    $validated['password'] = bcrypt($validated['password']);

    // Ensure studio_id is null if not staff (cleanup)
    if ($validated['role'] !== 'STUDIO_STAF') {
      $validated['studio_id'] = null;
    }

    try {
      User::create($validated);
      return redirect()->route('admin.users.index')
        ->with('success', 'User berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()->route('admin.users.index')
        ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
    }
  }

  public function update(Request $request, User $user)
  {
    // Permission check for updating admin
    if (auth()->user()->role !== 'LENSIA_ADMIN' && $user->role === 'LENSIA_ADMIN') {
      return redirect()->back()->with('error', 'Unauthorized to update admin.');
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email,' . $user->id,
      'phone' => 'required|string|max:20',
      'role' => 'required|in:CUSTOMER,STUDIO_STAF,LENSIA_ADMIN',
      'status' => 'required|in:ACTIVE,SUSPENDED',
      'studio_id' => 'nullable|exists:studios,id|required_if:role,STUDIO_STAF',
    ]);

    // Ensure studio_id is null if not staff (cleanup)
    if ($validated['role'] !== 'STUDIO_STAF') {
      $validated['studio_id'] = null;
    }

    $user->update($validated);

    return redirect()->back()->with('success', 'User updated successfully');
  }

  public function destroy(User $user)
  {
    // Cek apakah yang menghapus adalah LENSIA_ADMIN
    if (auth()->user()->role !== 'LENSIA_ADMIN') {
      return redirect()->back()->with('error', 'Unauthorized action.');
    }

    // check jika user adalah admin atau tidak 
    if ($user->role === 'LENSIA_ADMIN') {
      return redirect()->back()->with('error', 'You cannot delete admin user');
    }

    $user->delete();
    return redirect()->back()->with('success', 'User deleted successfully');
  }
}
