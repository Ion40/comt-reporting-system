<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Modules;
class ReportViewer extends Component
{
    public $modulo;

    public function mount($slug)
    {
        // 1. Validar que el slug no sea nulo o vacío
        if (empty($slug) || is_null($slug)) {
            return redirect()->route('dashboard');
        }

        //2. Buscamos el módulo por su url_path y cargamos la relación
        $this->modulo = Modules::with('reportIframe')
            ->where('url_path', $slug)
            ->first();

        // 3. Si no existe o no tiene un reporte asociado, redirigir al Dashboard
        // Esto soluciona el error de "Invalid Argument" al intentar renderizar algo nulo
        if (!$this->modulo || !$this->modulo->reportIframe) {
            Log::warning("Intento de acceso a reporte inexistente: /report-viewer/{$slug}");
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.report-viewer');
    }
}
