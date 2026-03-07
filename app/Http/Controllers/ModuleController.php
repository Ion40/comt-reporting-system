<?php

namespace App\Http\Controllers;

use App\Models\Modules;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    public function getModules()
    {
        try {
            $modules = Modules::where('parent_id', null)
                //->where('status', 1)
                ->orderBy('order_menu', 'asc')
                ->get(['id', 'name', 'icon_class']);

            return response()->json($modules, 200)->setStatusCode(200);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Error al obtener los submódulos',
                'message' => $exception->getMessage()
            ], 500)->setStatusCode(500);
        }
    }

    //Devolver submodulos cuando el usuario seleccione un módulo padre
    public function getSubmodules($parentId)
    {
        try {
            $submodules = Modules::where('modules.parent_id', $parentId)
                ->where('modules.show_menu', '>', 0)
                // Unimos con la tabla de iframes para saber si el submódulo ya tiene uno
                ->leftJoin('report_iframes as ri', 'modules.id', '=', 'ri.module_id')
                ->orderBy('modules.order_menu', 'asc')
                ->select([
                    'modules.id',
                    'modules.name',
                    'modules.url_path',
                    'ri.iframe_url',
                    'ri.id as iframe_exists' // Si este campo no es null, el badge será "Asignado"
                ])
                ->get();

            return response()->json($submodules, 200);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Error al obtener los submódulos',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
