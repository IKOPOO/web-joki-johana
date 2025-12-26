<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
  /**
   * Handle an incoming request.
   * 
   * Usage: middleware('role:LENSIA_ADMIN') or middleware('role:LENSIA_ADMIN,STUDIO_STAF')
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   * @param  string  ...$roles  List of allowed roles
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    // Check if user is authenticated
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    $user = auth()->user();

    // Check if user status is active
    if ($user->status !== 'ACTIVE') {
      \Illuminate\Support\Facades\Auth::logout();
      return redirect()->route('login')
        ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
    }

    // Check if user's role is in the allowed roles
    if (!in_array($user->role, $roles)) {
      // Return 403 Forbidden
      abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    return $next($request);
  }
}
