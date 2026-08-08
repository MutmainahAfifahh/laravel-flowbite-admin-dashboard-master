<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil role pengguna yang berhasil login & format ke underscore
        $role = str_replace(' ', '_', strtolower(trim(Auth::user()->role ?? '')));

        // Redirect otomatis sesuai role
        if ($role === 'admin') {
            return redirect('/categories');
        } elseif ($role === 'manajer_gudang') {
            return redirect('/products');
        } elseif ($role === 'staff_gudang') {
            return redirect('/transactions');
        }

        // Fallback jika role belum diatur
        return redirect('/login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}