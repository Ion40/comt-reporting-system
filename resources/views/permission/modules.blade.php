<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card h-auto">
                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                    <h4 class="header-title me-auto"><i class="ti ti-hierarchy-3 me-auto fs-4"></i> Lista de módulos</h4>

                    <div class="d-flex gap-2 justify-content-end text-end">
                        <a href="#" class="btn btn-primary" id="btnAddModule"><i
                                class="ti ti-hierarchy me-1 fs-4"></i>Agregar Módulo</a>
                    </div>
                </div>

                <div class="card-body p-0 pb-3">
                    <div class="table-responsive">
                        <table class="table table-centered table-striped align-middle" id="modules-table">
                            <thead class="table-dark">
                            <tr>
                                <th></th><th></th>
                                <th class="fs-12 text-uppercase text-muted py-1">Módulo</th>
                                <th class="fs-12 text-uppercase text-muted py-1">Descripción</th>
                                <th class="fs-12 text-uppercase text-muted py-1">Url</th>
                                <th class="fs-12 text-uppercase text-muted py-1">Icono</th>
                                <th class="fs-12 text-uppercase text-muted py-1">Fecha Creación</th>
                                <th class="fs-12 text-uppercase text-muted py-1">Fecha Modificación</th>
                                <th class="text-center  py-1 fs-12 text-uppercase text-muted"
                                    style="width: 120px;">Acciones
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModule" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalModuleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md shadow-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalModuleLabel">
                        Configuración de Módulo
                    </h5>
                    <button type="button" class="btn-close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="form_module">
                        @csrf
                        <input type="hidden" name="id_module_input" id="id_module_input">

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-hierarchy fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="hidden" id="id_module_edit">
                                    <input type="text" class="form-control border-start-0 validate"
                                           data-valid="Nombre"
                                           id="module_name" name="module_name" placeholder="Nombre">
                                    <label for="module_name">Nombre del módulo</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-notes fs-4"></i></span>
                                <div class="form-floating">
                                    <textarea class="form-control border-start-0" placeholder="Descripción" id="module_description" name="module_description" style="height: 100px"></textarea>
                                    <label for="module_description">Descripción del módulo (Opcional)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-link fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="text" class="form-control border-start-0 validate"
                                           data-valid="Url"
                                           value="#"
                                           id="module_url_input" name="module_url_input" placeholder="Ruta">
                                    <label for="module_url_input">Ruta / URL de acceso</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4" id="divIcono">
                            <div class="input-group input-group-merge shadow-sm">
                                <span class="input-group-text ">
                                    <i id="icon_preview" class="ti ti-brush fs-4"></i>
                                </span>

                                <div class="form-floating flex-grow-1">
                                    <input type="text" name="module_icon_input" id="module_icon_input" class="form-control border-start-0" placeholder="ti ti-home" readonly>
                                    <label for="module_icon_input">Iconografía del Módulo</label>
                                </div>

                                <button type="button" class="btn btn-primary"
                                        data-bs-target="#modalIconSelector"
                                        data-bs-toggle="modal"
                                        data-bs-dismiss="modal"
                                        onclick="loadIconCatalog()">
                                    <i class="ti ti-search me-1"></i> Buscar Icono
                                </button>
                            </div>
                            <small class="text-primary ms-1 mt-1 d-block">
                                <i class="ti ti-info-circle me-1"></i>
                                Haz clic en "Buscar" para elegir un icono del catálogo.
                            </small>
                        </div>

                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1">
                            <span class="mx-3 text-muted small text-uppercase fw-bold">Propiedades</span>
                            <hr class="flex-grow-1">
                        </div>

                        <div class="row bg-light rounded-3 p-3 mx-0 mb-3 border border-dashed">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="check_show_menu" name="check_show_menu" checked>
                                    <label class="form-check-label fw-semibold" for="check_show_menu">Visible en menú</label>
                                </div>
                                <small class="text-muted d-block mt-n1">¿Se mostrará como opción?</small>
                            </div>
                            <div class="col-md-6 border-start border-white">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="check_is_submodule" name="check_is_submodule" onchange="toggleSubmoduleView()">
                                    <label class="form-check-label fw-semibold" for="check_is_submodule">Es un sub-módulo</label>
                                </div>
                                <small class="text-muted d-block mt-n1">¿Depende de otro módulo?</small>
                            </div>
                        </div>

                        <div id="div_parent_select" class="mb-3 animate__animated animate__fadeIn" style="display: none;">
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="ti ti-hierarchy-2 fs-4"></i></span>
                                <div class="form-floating">
                                    <select class="form-select form-select-sm select2 border-primary" id="parent_module_id" name="parent_module_id">
                                        <option value="" selected disabled>Elija módulo principal...</option>
                                    </select>
                                    <label for="parent_module_id" class="text-primary fw-bold">Depende del módulo:</label>
                                </div>
                            </div>
                            <small class="text-primary ms-5 mt-1 d-block">
                                <i class="ti ti-info-circle me-1"></i>
                                Seleccione el nivel superior de este submódulo.
                            </small>
                        </div>

                    </form>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Cancelar
                    </button>
                    <button id="btnSaveModule" type="button" class="btn btn-primary px-4 shadow">
                        <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalIconSelector" aria-hidden="true" aria-labelledby="modalIconSelectorLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white" id="modalIconSelectorLabel">Seleccionar Icono</h5>
                </div>
                <div class="modal-body p-4">
                    <table id="table-icon-catalog" class="table table-centered table-striped align-middle">
                        <thead class="">
                        <tr>
                            <th>Icono</th>
                            <th>Nombre</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-target="#modalModule" data-bs-toggle="modal" data-bs-dismiss="modal">
                        <i class="ti ti-arrow-left me-1"></i> Volver al Formulario
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const modulesList = " {{ route('modules.index') }}";
            const parentModuleList = " {{ route('modules.modulesParents') }}";
            const saveOrUpdate = " {{ route('modules.save') }}";

            // Variable útil para tu script JS
            //const isEdit =  $isEdit ? 'true' : 'false' }};
            //const method = " $isEdit ? 'PUT' : 'POST' }}";

            const jsonTable = "{{ asset('assets/data/tabler_icons.json') }}";
        </script>
        <script src="{{asset("/js/custom/modules.js")}}"></script>
    @endpush
</x-app-layout>
