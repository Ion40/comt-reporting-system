<?php

namespace App\Http\Controllers;

use App\Events\NuevoModuloGenerado;
use App\Events\PermisosActualizados;
use App\Models\Modules;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager loading de la relación parent (autoreferencia)
            $query = Modules::with('parent')->select('modules.*');

            return DataTables::of($query)
                // 1. Identificador técnico para agrupar (ID del padre o el propio ID si es padre)
                ->addColumn('group_id', function ($module) {
                    return $module->parent_id ?? $module->id;
                })
                // 2. Nombre que se mostrará en la fila de encabezado del grupo
                ->addColumn('group_name', function ($module) {
                    return $module->parent_id ? $module->parent->name : $module->name;
                })
                ->editColumn("name", function ($module) {
                    $isSubmodule = !is_null($module->parent_id);
                    $icon = $module->icon_class ?: 'ti ti-package';

                    // Clases de diseño basadas en tu petición
                    $marginClass = $isSubmodule ? 'ms-4 ps-2 border-start border-2' : '';
                    $titleColor = $isSubmodule ? 'text-muted fw-bold' : 'fw-bold text-dark';
                    $branchIcon = $isSubmodule ? '<i class="ti ti-subtask me-1 text-primary"></i>' : '';
                    $urlIcon = $isSubmodule ? '<i class="ti ti-link me-1 text-primary fs-5"></i>' : '';

                    // Badge de estado de visibilidad (Diseño Adminto)
                    $menuStatus = $module->show_menu
                        ? '<span class="badge bg-soft-success text-success  ms-1">Visible</span>'
                        : '<span class="badge bg-soft-secondary text-secondary  ms-1">Oculto</span>';

                    return '
                <div class="d-flex align-items-center gap-2 ' . $marginClass . '">
                    <div class="avatar-sm">
                        <div class="h-100 w-100 rounded bg-soft-' . ($isSubmodule ? 'secondary' : 'primary') . ' d-flex align-items-center justify-content-center border border-light">
                            <i class="' . $icon . ' fs-14 ' . ($isSubmodule ? 'text-secondary' : 'text-primary') . '"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class=" mb-0' . $titleColor . '">
                            ' . $branchIcon . $module->name . $menuStatus . '
                        </h5>
                        ' . ($isSubmodule ? '<small class="text-muted"> ' . $urlIcon . ' ' . $module->url_path . '</small>' : '') . '
                    </div>
                </div>';
                })
                ->addColumn('actions', function ($module) {
                    return '
                <div class="hstack gap-1 justify-content-center">
                    <button data-idedit="' . $module->id . '" class="btn btn-soft-info btn-icon btn-sm rounded-circle btn-edit" title="Editar">
                        <i data-idedit="' . $module->id . '" class="ti ti-edit font-size-16 btn-edit"></i>
                    </button>
                    <button data-id="' . $module->id . '" class="btn btn-soft-danger btn-icon btn-sm rounded-circle btn-delete" title="Eliminar">
                        <i class="ti ti-trash font-size-16"></i>
                    </button>
                </div>';
                })
                ->addColumn('created_at', function ($module) {
                    if (!$module->created_at) {
                        return '<span class="text-muted small">-</span>';
                    }

                    // Formateamos la fecha: 19 Mar, 2026
                    $date = Carbon::parse($module->created_at)->format('d M, Y');
                    // Formateamos la hora: 02:30 PM
                    $time = Carbon::parse($module->created_at)->format('h:i A');

                    return '
                    <div class="text-center">
                        <span class="font-size-13 fw-medium text-dark">' . $date . '</span>
                        <br />
                        <small class="text-muted font-size-11">' . $time . '</small>
                    </div>';
                })
                ->addColumn('updated_at', function ($module) {
                    if (!$module->updated_at) {
                        return '<span class="text-muted small">-</span>';
                    }

                    // Formateamos la fecha: 19 Mar, 2026
                    $date = Carbon::parse($module->updated_at)->format('d M, Y');
                    // Formateamos la hora: 02:30 PM
                    $time = Carbon::parse($module->updated_at)->format('h:i A');

                    return '
                    <div class="text-center">
                        <span class="font-size-13 fw-medium text-dark">' . $date . '</span>
                        <br />
                        <small class="text-muted font-size-11">' . $time . '</small>
                    </div>';
                })
                ->order(function ($query) {
                    $query->orderByRaw("ISNULL(parent_id, id) ASC")
                        ->orderByRaw("CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END ASC")
                        ->orderBy("order_menu", "ASC");
                })
                ->rawColumns(['name', 'actions', 'created_at', 'updated_at'])
                ->make(true);
        }
        return view('permission.modules');
    }

    public function getModules(Request $request)
    {
        try {
            $id_module_edit = $request->input('id_module_edit');

            $modules = Modules::query()
                // Cargamos la relación recursiva definida en el modelo
                ->with('parent')
                ->when($id_module_edit, function ($query) use ($id_module_edit) {
                    return $query->where('id', $id_module_edit);
                }, function ($query) {
                    // Si no es edición, solo listamos los padres para el select
                    return $query->whereNull('parent_id');
                })
                ->orderBy('order_menu', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'text' => $item->name, // Requerido por Select2
                        'icon_class' => $item->icon_class,
                        'icon' => $item->icon_class,
                        'description' => $item->description,
                        'parent_id' => $item->parent_id,
                        // Extraemos el nombre del padre desde la relación recursiva
                        'parent_name' => $item->parent ? $item->parent->name : null,
                        'url_path' => $item->url_path,
                        'show_menu' => $item->show_menu,
                        'order_menu' => $item->order_menu
                    ];
                });

            return response()->json($modules, 200)->setStatusCode(200);

        } catch (Exception $exception) {
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

        } catch (Exception $exception) {
            return response()->json([
                'error' => 'Error al obtener los submódulos',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            //viene al editar
            $id_module_edit = $request->input('id_module_edit');
            //otros campos
            $module_url_input = $request->input('module_url_input');
            $module_description = $request->input("module_description");
            $module_icon_input = $request->input("module_icon_input");
            $module_name = $request->input("module_name");
            $show_menu = $request->input("show_menu");
            $parent_module_id = $request->input('parent_module_id');

            $exists = false;

            // 1. Evitamos validar si es un módulo padre (usando el hash #)
            if ($module_url_input !== "#") {
                $exists = DB::table("modules")
                    ->whereRaw('LOWER(url_path) = ?', [strtolower($module_url_input)])
                    // Usamos el ID de edición para ignorar el registro actual si existe
                    ->when($id_module_edit, function ($query) use ($id_module_edit) {
                        return $query->where('id', '!=', $id_module_edit);
                    })
                    ->exists(); // Asegúrate de que NO haya un ";" antes de esta línea


                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La URL ingresada ya está asignada a otro módulo.',
                        'errors' => [
                            // El key debe coincidir con el name del input para que tu JS lo marque en rojo
                            'module_url_input' => ['Esta ruta ya se encuentra en uso.']
                        ]
                    ], 422);
                }
            }

            if ($parent_module_id) {
                //Validar que el modulo seleccionado no sea un hijo de otro modulo
                /*$parentIsActuallyAChild = Modules::where('id', $parent_module_id)
                        ->whereNotNull('parent_id')
                    ->exists();

                if ($parentIsActuallyAChild) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No puedes asignar este módulo como padre porque ya es un submódulo.',
                        'errors' => ['parent_module_id' => ['El nivel máximo de anidación es 2.']]
                    ], 422);
                }*/

                //Validar que el módulo ACTUAL no tenga hijos si quieres volverlo hijo
                if ($id_module_edit) {
                    $hasChildren = Modules::where('parent_id', $id_module_edit)->exists();
                    if ($hasChildren) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Este módulo tiene submódulos dependientes y no puede convertirse en un submódulo.',
                            'errors' => ['parent_module_id' => ['Primero debes mover o eliminar sus submódulos.']]
                        ], 422);
                    }
                }

            }

            $result = DB::transaction(function () use (
                $module_name, $module_url_input, $module_icon_input,
                $module_description, $id_module_edit, $show_menu, $parent_module_id
            ) {

                $data = [
                    'name' => $module_name,
                    'description' => $module_description,
                    'parent_id' => $parent_module_id ? $parent_module_id : null,
                    'icon_class' => $module_icon_input ? $module_icon_input : null,
                    'url_path' => $module_url_input,
                    'show_menu' => $show_menu
                ];

                if (!$id_module_edit) {
                    $data['created_at'] = now();
                    DB::table("modules")->insert($data);
                    $message = "Se ha registrado el modulo " . $module_name . " con exito.";
                } else {
                    $data['updated_at'] = now();
                    DB::table("modules")->where('id', $id_module_edit)->update($data);
                    $message = "Se ha actualizado el modulo " . $module_name . " con exito.";
                }

                event(new NuevoModuloGenerado());
                event(new PermisosActualizados(auth()->id()));
                return $message;
            });

            return response()->json([
                'success' => true,
                'message' => $result,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false,
                'message' => 'Ocurrió un error en el servidor: ' . $e->getMessage(),
                'error' => $e->getMessage()], 500);
        }
    }
}
