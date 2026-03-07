<?php

namespace App\Livewire;

use App\Models\Modules;
use App\Models\Permissions;
use App\Models\PermissionUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ModulesMonitor extends Component
{
    public $userId;
    public $config;
    public $permisos;
    public $selectedPermissions = [];

    public function mount()
    {
        //$this->show = $show;
        $this->getModules();
        if (!is_array($this->selectedPermissions)) {
            $this->selectedPermissions = [];
        }
    }

    public function getModules()
    {
        //obtener todos los modulos
        $this->config = Modules::all();
        $this->permisos = Permissions::all();
        Log::info('Cargando modulos en NuevoModuloGenerado Livewire component.');
    }

    #[On('echo:new-module,NuevoModuloGenerado')]
    public function refreshModules()
    {
        Log::info('Evento NuevoModuloGenerado recibido en AppConfig Livewire component.');
        $this->getModules();
    }

    //funcion para marcar los checkboxes de cada fila
    public function checkAllRow($moduleId) {
        $this->selectedPermissions = $this->selectedPermissions ?? [];
        $permisosIds = Permissions::pluck('id')->toArray();
        $rowKeys = array_map(fn($pId) => $moduleId . '_' . $pId, $permisosIds);

        $intersect = array_intersect($rowKeys, $this->selectedPermissions);

        if (count($intersect) === count($rowKeys)) {
            // Al quitar, usamos array_values para resetear los índices [0, 1, 2...]
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $rowKeys));
        } else {
            $this->selectedPermissions = array_values(array_unique(array_merge($this->selectedPermissions, $rowKeys)));
        }
    }

    public function render()
    {
     return view('livewire.modules-monitor');
    }

    #[On('user-selected')]
    public function updateSelectedUser($id)
    {
        // Si enviaste {id: 9} desde JS, extraemos el valor
        $this->userId = is_array($id) ? $id['id'] : $id;

        if ($this->userId) {
            // Consulta a la tabla pivote de permisos
            $this->selectedPermissions = DB::table('permission_user')
                ->where('user_id', $this->userId)
                ->get()
                ->map(function ($row) {
                    // Generamos el string 'moduloID_permisoID' para que coincida con el value del checkbox
                    return $row->module_id . '_' . $row->permission_id;
                })
                ->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }
}
