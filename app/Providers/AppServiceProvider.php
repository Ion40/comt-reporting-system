<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limite para ENVÍO de tokens (3 correos por hora por IP)
        RateLimiter::for('password-recovery-send', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Has excedido el límite de intentos. Por favor, espera una hora para solicitar un nuevo correo.'
                ], 429);
            });
        });

        // Limite para VALIDACIÓN de tokens (5 intentos por minuto)
        RateLimiter::for('password-recovery-validate', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Demasiados intentos fallidos. Intenta de nuevo en un minuto.'
                ], 429);
            });
        });
    }
}
