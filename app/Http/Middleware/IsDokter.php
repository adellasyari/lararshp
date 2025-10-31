<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsDokter
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
            return redirect('/home')->withErrors(['error' => 'Anda harus login terlebih dahulu.']);
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki role Dokter
        $hasRole = $user->roles()->where('nama_role', 'Dokter')->wherePivot('status', 1)->exists();

        if (!$hasRole) {
            return redirect('/home')->withErrors(['error' => 'Akses ditolak. Anda tidak memiliki hak akses sebagai Dokter.']);
        }

        return $next($request);
    }
}
