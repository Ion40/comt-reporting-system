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
                    <div class="alert alert-info border-0 bg-info-subtle mb-4">
                        <div class="d-flex">
                            <i class="ti ti-shield-check fs-2 text-info me-3"></i>
                            <div>
                                <h5 class="alert-heading fw-bold">Gestión de Accesos</h5>
                                <p class="mb-0">Utilice este módulo para asignar **permisos de acceso** a las diferentes áreas del
                                    sistema
                                    y definir **funcionalidades específicas** (crear, editar, eliminar) para cada nivel de usuario.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-end">
                        <div class="col-md-5">
                            <label for="user_select" class="form-label fw-bold text-muted">
                                <i class="ti ti-users me-1"></i> Seleccionar Usuario para Asignar Permisos
                            </label>
                            <div class="input-group">
                                <select id="user_select" class="select2" data-toggle="select2" data-choices-removeItem>
                                    <option value="" selected disabled>-- Seleccione un usuario --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-7 text-end">
                            <button id="btnGuardarPermisos" class="btn btn-sm btn-success">
                                <i class="ti ti-device-floppy me-1"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>

                    <hr class="border-dashed my-4">
                    <livewire:modules-monitor/>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const apiSaveConfiguration = "{{ route('users.savePermissions') }}";
        </script>
        <script src="{{asset("/js/custom/modules.js")}}"></script>
    @endpush
</x-app-layout>
