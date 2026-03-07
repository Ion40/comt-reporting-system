<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PermisosActualizados extends Component
{
    public $menuItems = [];

    public function getListeners()
    {
        $userId = Auth::id();
        return [
            "echo:user-permissions.{$userId},PermisosActualizados" => 'refreshPermissions',
        ];
    }

    public function mount()
    {
        $this->loadMenu();
    }

    public function loadMenu()
    {
        $results = DB::table('permission_user as pu')
            ->join('permissions as p', 'p.id', '=', 'pu.permission_id')
            ->leftJoin('modules as m', 'm.id', '=', 'pu.module_id')
            ->leftJoin('modules as m1', 'm1.id', '=', 'm.parent_id')
            // JOIN clave: buscamos si el id del modulo existe en la tabla de iframes
            ->leftJoin('report_iframes as ri', 'ri.module_id', '=', 'm.id')
            ->where('pu.permission_id', 1)
            ->where('pu.user_id', Auth::id())
            ->where('m.show_menu', 1)
            ->select([
                'pu.permission_id',
                'pu.module_id',
                'm.name as module_name',
                'm1.id as parent_id',
                'm1.name as parent_name',
                'm1.icon_class as parent_icon',
                'm1.order_menu as parent_order',
                'm.icon_class as module_icon',
                'm.url_path',
                'm.order_menu as module_order',
                'ri.id as has_iframe' // Si este valor no es nulo, tiene iframe
            ])
            ->get();

        $menu = [];

        foreach ($results as $row) {
            if ($row->parent_id) {
                if (!isset($menu[$row->parent_id])) {
                    $menu[$row->parent_id] = [
                        'name' => $row->parent_name,
                        'icon' => $row->parent_icon,
                        'order' => $row->parent_order,
                        'submodules' => []
                    ];
                }
                $menu[$row->parent_id]['submodules'][] = $row;
            } else {
                if (!isset($menu[$row->module_id])) {
                    $menu[$row->module_id] = [
                        'name' => $row->module_name,
                        'icon' => $row->module_icon,
                        'url' => $row->url_path,
                        'order' => $row->module_order,
                        'has_iframe' => !is_null($row->has_iframe), // Guardamos el estado
                        'submodules' => []
                    ];
                }
            }
        }

        $menu = collect($menu)->sortBy('order')->all();

        foreach ($menu as $id => $item) {
            if (!empty($item['submodules'])) {
                $menu[$id]['submodules'] = collect($item['submodules'])
                    ->sortBy('module_order')
                    ->values()
                    ->all();
            }
        }

        $this->menuItems = $menu;
    }

    /**
     * Esta es la función que decide el destino
     */
    public function navegar($urlPath, $hasIframe)
    {
        if ($hasIframe) {
            // REDIRIGIR AL COMPONENTE VISOR
            // Asumiendo que tu ruta se llama 'report-viewer'
            return redirect()->to(url('/report-viewer/' . $urlPath));
        } else {
            // REDIRIGIR A LA RUTA REGISTRADA
            return redirect()->to(url($urlPath));
        }
    }

    public function refreshPermissions()
    {
        Log::info('Refrescando menú para el usuario: ' . Auth::id());
        $this->loadMenu();
    }

    public function render()
    {
        return view('livewire.side-nav');
    }
}
