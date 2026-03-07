<?php

namespace App\Http\Controllers;

use App\Events\IframesEvent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\throwException;

class ReportIframeController extends Controller
{
    public function index()
    {
        return view('iframes.index');
    }

    public function createView()
    {
        return view('iframes.create');
    }

    public function editIframe($url_path)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.');
        }

        $iframe = DB::table('report_iframes')
            ->join('modules', 'report_iframes.module_id', '=', 'modules.id')
            ->leftJoin('modules as parents', 'modules.parent_id', '=', 'parents.id')
            ->where('modules.url_path', $url_path) // Buscamos por el path del módulo
            ->select([
                'report_iframes.id as iframe_id',
                'report_iframes.module_id',
                'report_iframes.title',
                'report_iframes.iframe_url',
                'modules.name as submodule_name',
                'modules.url_path as url_path',
                'parents.name as parent_name',
            ])
            ->first();

        if (!$iframe) {
            return redirect()->route('iframes.index')
                ->with('error', 'No se encontró el reporte asociado a la ruta: ' . $url_path);
        }

        return view('iframes.create', compact('iframe'));
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                "id_module_input"  => "required|exists:modules,id",
                "titulo_iframe"    => "required|string|max:255",
                "iframe_url_input" => "required|string"
            ], [
                "id_module_input.required" => "El reporte debe tener un módulo para poder ser asignado",
                "titulo_iframe.required" => "Debe agregar el titulo del reporte",
                "iframe_url_input.required" => "Debe insertar la url del reporte",
            ]);

            $idIframe = $request->input('id_iframe');
            $moduleId = $request->input('id_module_input');
            $moduleUrl = $request->input('module_url_input');

            // 1. Validar si ya existe un reporte para este módulo (Solo en Creación)
            if (!$idIframe) {
                $exists = DB::table('report_iframes')
                    ->where('module_id', $moduleId)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de duplicidad',
                        'errors'  => ['id_module_input' => ['Este módulo ya tiene un reporte asignado.']]
                    ], 422);
                }
            }

            // 2. Validar que la URL del módulo sea válida (no "#" ni vacía)
            if (str_contains(trim($moduleUrl), '#') || empty(trim($moduleUrl))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuración de módulo inválida',
                    'errors'  => ['module_url_input' => ['El módulo seleccionado no tiene una URL de destino válida.']]
                ], 422);
            }

            // 3. Ejecutar dentro de una Transacción
            $result = DB::transaction(function () use ($idIframe, $moduleId, $request) {
                $data = [
                    "module_id"  => $moduleId,
                    "title"      => $request->input('titulo_iframe'),
                    "iframe_url" => $request->input('iframe_url_input'),
                ];

                if ($idIframe) {
                    // ACTUALIZACIÓN
                    $data['updated_at'] = now();
                    DB::table('report_iframes')->where('id', $idIframe)->update($data);
                    $message = "Reporte actualizado correctamente.";
                } else {
                    // CREACIÓN
                    $data['created_at'] = now();
                    $data['is_active']  = true;
                    DB::table('report_iframes')->insert($data);
                    $message = "Reporte creado y publicado con éxito.";
                }

                // Disparar evento dentro de la transacción
                event(new IframesEvent());

                return $message;
            });

            return response()->json([
                'success' => true,
                'message' => $result
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteIframe($urlPath)
    {
        try {
            // Ejecutamos la transacción
            $message = DB::transaction(function () use ($urlPath) {
                $iframe = DB::table('report_iframes')
                    ->join('modules', 'report_iframes.module_id', '=', 'modules.id')
                    ->where('modules.url_path', $urlPath)
                    ->select('report_iframes.id')
                    ->first();

                if (!$iframe) {
                    // Lanzar excepción para disparar el rollback automático
                    throw new \Exception("Reporte no encontrado.");
                }

                // Actualización de estado (Baja lógica)
                DB::table('report_iframes')
                    ->where('id', $iframe->id)
                    ->update([
                        'is_active' => false
                    ]);

                // Disparar evento de actualización en tiempo real
                event(new IframesEvent());

                return "Reporte dado de baja correctamente.";
            });

            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
