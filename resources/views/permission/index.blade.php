<x-app-layout>
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-h-100">
                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                    <h4 class="header-title me-auto">Configuración de Permisos</h4>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-history me-1"></i> Historial de Cambios
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <livewire:modules-monitor/>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            //const apiSaveConfiguration = "//route('saveConfiguration') "
        </script>
        <script src="{{asset("/js/custom/modules.js")}}"></script>
    @endpush
</x-app-layout>
