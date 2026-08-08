<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ambil role user dari DB & bersihkan dari spasi/huruf besar ke format underscore
        $userRole = str_replace(' ', '_', strtolower(trim(auth()->user()->role ?? '')));

        // Jika role di DB kosong, langsung blokir
        if (empty($userRole)) {
            abort(403, 'Role pengguna belum diatur di database.');
        }

        // Kumpulkan semua role yang diizinkan di route
        $allowedRoles = [];
        foreach ($roles as $role) {
            // Memisah jika ditulis 'role:admin,manajer_gudang'
            $splitRoles = explode(',', $role);
            foreach ($splitRoles as $r) {
                $allowedRoles[] = str_replace(' ', '_', strtolower(trim($r)));
            }
        }

        // 2. Jika role user cocok ATAU user adalah 'admin', izinkan lewat!
        if (in_array($userRole, $allowedRoles) || $userRole === 'admin') {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}