<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Modules;
class ReportViewer extends Component
{
    public $modulo;

    public function mount($slug)
    {
        // Buscamos el módulo por su url_path y cargamos la relación
        $this->modulo = Modules::with('reportIframe')
            ->where('url_path', $slug)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.report-viewer');
    }
}
