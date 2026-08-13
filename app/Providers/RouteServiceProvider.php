<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Redirect dinamis berdasarkan role user saat login.
     */
    public static function redirectTo()
    {
        $role = str_replace(' ', '_', strtolower(trim(auth()->user()?->role ?? '')));

        if ($role === 'admin') {
            return '/categories'; // Admin ke Manajemen Kategori
        } elseif ($role === 'manajer_gudang') {
            return '/products'; // Manajer Gudang ke Manajemen Produk
        } elseif ($role === 'staff_gudang') {
            return '/stock-transactions'; // Staff Gudang ke Transaksi Stok
        }

        return '/categories'; // Default fallback
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // template routes
            Route::middleware('web')
                ->prefix('example')
                ->group(base_path('routes/example.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}