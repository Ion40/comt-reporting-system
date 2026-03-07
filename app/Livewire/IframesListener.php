<?php

namespace App\Livewire;

use App\Models\ReportIframe;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class IframesListener extends Component
{
    public $iframes;

    public function render()
    {
        return view('livewire.iframes-listener');
    }

    public function mount()
    {
        $this->getIframes();
    }

    public function getIframes()
    {
        $this->iframes = DB::table('report_iframes')
            ->join('modules', 'report_iframes.module_id', '=', 'modules.id')
            ->select([
                'report_iframes.id',
                'report_iframes.module_id',
                'report_iframes.title',
                'report_iframes.iframe_url',
                'report_iframes.is_active',
                'report_iframes.created_at',
                'modules.name',
                'modules.url_path',
                'modules.description',
            ])
            ->get();
    }

    #[On('echo:iframe-list,IframesEvent')]
    public function refreshIframes()
    {
        $this->getIframes();
    }
}
