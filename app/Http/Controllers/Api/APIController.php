<?php

namespace App\Http\Controllers\Api;

use App\Events\PermisosActualizados;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->term;

        $users = User::where('nombre', 'LIKE', "%{$term}%")
            //->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->nombre // Select2 requiere la propiedad "text"
                ];
            });

        return response()->json(['results' => $users]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(
            [
                'message' => 'Usuario no encontrado',
                'success' => false,
                'data' => null,
            ]
            ,404);
        return response()->json([
            'message' => 'Usuario encontrado',
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function savePermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'permissions' => 'array'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Limpiar anteriores
                DB::table('permission_user')->where('user_id', $request->user_id)->delete();

                // 2. Preparar inserción
                $data = [];
                foreach ($request->permissions as $p) {
                    $parts = explode('_', $p);
                    if (count($parts) === 2) {
                        $data[] = [
                            'user_id' => $request->user_id,
                            'module_id' => $parts[0],
                            'permission_id' => $parts[1],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                if (!empty($data)) DB::table('permission_user')->insert($data);
            });

            event(new PermisosActualizados($request->user_id));

            return response()->json(['message' => 'Permisos guardados con éxito'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
