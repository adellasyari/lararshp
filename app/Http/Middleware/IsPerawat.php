<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class IsPerawat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            $home = Route::has('site.home') ? route('site.home') : url('/');
            return redirect($home)->withErrors(['error' => 'Anda harus login terlebih dahulu.']);
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki role Perawat
        $hasRole = $user->roles()->where('nama_role', 'Perawat')->wherePivot('status', 1)->exists();

        if (!$hasRole) {
            $home = Route::has('site.home') ? route('site.home') : url('/');
            return redirect($home)->withErrors(['error' => 'Akses ditolak. Anda tidak memiliki hak akses sebagai Perawat.']);
        }

        return $next($request);
    }
}
