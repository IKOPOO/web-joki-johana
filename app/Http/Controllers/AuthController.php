<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function showRegisterForm()
  {
    return view('auth.login', ['isRegister' => true]);
  }

  public function login(Request $request)
  {
    // validasi inputan 
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    // try login 
    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      // Store user info in session
      $user = Auth::user();

      // Check if staff has studio assigned
      if ($user->role === 'STUDIO_STAF' && !$user->studio_id) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
          'email' => 'Akun staff ini belum terhubung dengan studio manapun. Hubungi admin.'
        ])->onlyInput('email');
      }

      $request->session()->put('user_name', $user->name);
      $request->session()->put('user_email', $user->email);
      $request->session()->put('user_role', $user->role);

      // redirect based on role
      if ($user->role === 'LENSIA_ADMIN') {
        return redirect()->route('admin.dashboard');
      } elseif ($user->role === 'STUDIO_STAF') {
        return redirect()->route('admin.bookings.index');
      } elseif (strtoupper($user->role) === 'CUSTOMER') {
        return redirect()->route('customer.booking.index');
      }

      return redirect('/');
    }

    // if login failed
    return back()->withErrors([
      'email' => 'Email atau Password Salah'
    ])->onlyInput('email', 'form_type');

  }

  public function register(Request $request)
  {
    // validasi inputan
    $validated = $request->validate([
      'nama' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'no_hp' => 'required|string|max:20',
      'password' => 'required|string|min:6',
    ]);

    // buat user baru dengan role default "customer"
    $user = \App\Models\User::create([
      'name' => $validated['nama'],
      'email' => $validated['email'],
      'phone' => $validated['no_hp'],
      'password' => bcrypt($validated['password']),
      'role' => 'CUSTOMER', // default role
      'status' => 'active', // default status
    ]);

    // redirect ke halaman login dengan pesan sukses
    return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
  }
}
