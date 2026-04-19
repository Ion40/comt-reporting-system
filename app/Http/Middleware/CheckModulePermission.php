<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
// IMPORTANTE: Agregar esta línea para que el type-hint funcione
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $actionId = 1): Response
    {
        // Rutas que NUNCA deben ser validadas como módulos
        $excludedPaths = ['Profile', 'logout', 'dashboard', 'profile', 'Recover'];

        $path = $request->routeIs('report.viewer')
            ? $request->route('slug')
            : $request->segment(1);

        if (!$path || in_array($path, $excludedPaths)) {
            return $next($request);
        }

        // 2. Buscar el ID del módulo en la base de datos
        // Nota: Agregué una pequeña validación por si el cache devuelve null
        $module = Cache::remember("mod_data_{$path}", 3600, function () use ($path) {
            return DB::table('modules')->where('url_path', $path)->first();
        });

        // 3. Si el módulo existe, validamos permisos
        if ($module) {
            if (!Auth::check() || !Auth::user()->tienePermiso($module->id, $actionId)) {

                // Si no tiene permiso de Visualizar (1), al perfil
                if ($actionId == 1) {
                    return redirect()->route('users.profile')
                        ->with('error', "No tienes permiso para acceder al módulo: {$module->name}");
                }

                abort(403, 'Acción no autorizada para este módulo.');
            }

            // 4. COMPARTIR EL ID
            view()->share('currentModuleId', $module->id);
        }

        return $next($request);
    }
}
