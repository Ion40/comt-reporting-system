<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class IframesListener extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $modulo = 'all';

    #[Url(history: true)]
    public $orden = 'latest';

    // Resetear paginación al filtrar
    public function updatedSearch() { $this->resetPage(); }
    public function updatedModulo() { $this->resetPage(); }

    // ... dentro de la clase IframesListener

    public function render()
    {
        // Cacheamos la lista de módulos padres por 1 hora
        // Se limpia sola si agregas un nuevo módulo
        $parentModulesList = Cache::remember('parent_modules_user_' . auth()->id(), 3600, function () {
            return DB::table('modules')->whereNull('parent_id')->get();
        });

        // 1. Obtener solo los módulos que son PADRES (donde parent_id es null)
        // Esto llenará tu select solo con "Ventas", "RRHH", "Sistemas", etc.
        $parentModulesList = DB::table('modules')
            ->whereNull('parent_id')
            ->select('id', 'name')
            ->get();

        // 2. Obtener los nombres de los grupos para los acordeones/secciones
        $groups = DB::table('report_iframes')
            ->join('modules', 'report_iframes.module_id', '=', 'modules.id')
            ->leftJoin('modules as parents', 'modules.parent_id', '=', 'parents.id')
            ->select(DB::raw("COALESCE(parents.name, 'Módulos Principales') as group_name"))
            ->when($this->search, function($q) {
                $q->where('report_iframes.title', 'like', '%' . $this->search . '%');
            })
            ->when($this->modulo !== 'all', function($q) {
                // Filtramos para que solo aparezca el grupo del padre seleccionado
                $q->where('parents.id', $this->modulo)
                    ->orWhere(function($sub) {
                        // Caso especial: Si el padre es null, lo buscamos por el ID del módulo directamente
                        if ($this->modulo == 'principal') $sub->whereNull('modules.parent_id');
                    });
            })
            ->distinct()
            ->pluck('group_name');

        $groupedData = [];

        foreach ($groups as $name) {
            $pageName = 'page_' . Str::slug($name, '_');

            $query = DB::table('report_iframes')
                ->join('modules', 'report_iframes.module_id', '=', 'modules.id')
                ->leftJoin('modules as parents', 'modules.parent_id', '=', 'parents.id')
                ->select([
                    'report_iframes.*',
                    'modules.name as submodule_name',
                    'modules.url_path',
                    'parents.name as parent_name',
                    'parents.icon_class as icon_class'
                ])
                ->where(function($q) use ($name) {
                    if ($name === 'Módulos Principales') {
                        $q->whereNull('parents.name');
                    } else {
                        $q->where('parents.name', $name);
                    }
                })
                ->when($this->search, function($q) {
                    $q->where('report_iframes.title', 'like', '%' . $this->search . '%');
                })
                ->when($this->modulo !== 'all', function($q) {
                    // Filtramos los reportes cuyo PADRE coincida con el ID seleccionado
                    $q->where('parents.id', $this->modulo);
                });

            // Aplicar orden...
            if ($this->orden === 'name') $query->orderBy('title', 'asc');
            else $query->latest('report_iframes.created_at');

            $groupedData[$name] = $query->paginate(3, ['*'], $pageName);
        }

        return view('livewire.iframes-listener', [
            'groupedData' => $groupedData,
            'parentModulesList' => $parentModulesList
        ]);
    }
}
