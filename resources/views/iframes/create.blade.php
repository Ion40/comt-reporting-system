<x-app-layout>
    @php
        // Detectamos si estamos en modo edición
        $isEdit = isset($iframe);
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box mt-2 mb-2">
                    <a href="{{ route('iframes.index') }}" class="btn btn-outline-danger" type="button">
                        Volver a Gestión de Iframes
                        <i class="ti ti-arrow-autofit-left fs-4 ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                        <h4 class="header-title me-auto">{{ $isEdit ? 'Editar Reporte' : 'Asignación de Módulo' }}</h4>
                        <button id="btnSelectModule"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-title="mostrar lista de módulos"
                                class="btn btn-outline-secondary btn-sm" type="button">
                            {{ $isEdit ? 'Cambiar módulo' : 'Seleccionar módulo' }}
                            <i class="ti ti-folder-plus fs-4 ms-2"></i>
                        </button>
                    </div>

                    <div class="card-body">

                        <form>
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-question-mark fs-4"></i></span>
                                    <div class="form-floating">
                                        @if($isEdit)
                                            <input type="hidden" name="" id="id_iframe"
                                                   value="{{ $iframe->iframe_id  }}">
                                        @endif
                                        <input type="hidden" name="module_id" id="id_module_input"
                                               value="{{ $iframe->module_id ?? '' }}">
                                        <input type="text"
                                               name="parent_id_input"
                                               id="parent_id_input" class="form-control"
                                               placeholder="Módulo"
                                               value="{{ $iframe->parent_name ?? '' }}"
                                               disabled>
                                        <label for="parent_id_input fs-4">Módulo Padre</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-question-mark fs-4"></i></span>
                                    <div class="form-floating">
                                        <input type="text"
                                               name="sub_module_input"
                                               id="sub_module_input" class="form-control"
                                               placeholder="Submodulo"
                                               value="{{ $iframe->submodule_name ?? '' }}"
                                               disabled>
                                        <label class="form-label">Submódulo (Destino)</label>
                                    </div>
                                </div>
                                <small class="text-primary mt-1 d-block">
                                    <i class="ti ti-info-circle me-1"></i>
                                    El reporte se mostrará al hacer clic en este submódulo.
                                </small>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-link fs-5"></i></span>
                                    <div class="form-floating">
                                        <input type="text"
                                               name="module_url_input"
                                               id="module_url_input" class="form-control"
                                               placeholder="Url"
                                               value="{{ $iframe->url_path ?? '' }}"
                                               disabled>
                                        <label class="form-label">Url Reporte (Url Submódulo)</label>
                                    </div>
                                </div>
                                <small class="text-primary mt-1 d-block">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Url de enlace de acceso (Submódulo).
                                </small>
                            </div>

                            <hr>

                            <h4 class="header-title mb-3">Datos del Reporte</h4>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clipboard-data fs-5"></i></span>
                                    <div class="form-floating">
                                        <input type="text" name="titulo_iframe" class="form-control"
                                               id="titulo_iframe"
                                               placeholder="Ej: Resumen de Operaciones 2024"
                                               value="{{ old('title', $iframe->title ?? '') }}" required>
                                        <label for="titulo_iframe" class="form-label">Título del Reporte</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-link fs-5"></i></span>
                                    <div class="form-floating">
                                        <input type="url" name="iframe_url_input" id="iframe_url_input"
                                               class="form-control"
                                               placeholder="https://app.powerbi.com/view?r=..."
                                               value="{{ old('iframe_url', $iframe->iframe_url ?? '') }}" required>
                                        <label for="iframe_url_input" class="form-label">URL del Iframe
                                            (PowerBI)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="button" id="btnSaveIframe"
                                        class="btn btn-primary btn-lg waves-effect waves-light">
                                    <i class="ti {{ $isEdit ? 'ti-edit' : 'ti-device-floppy' }} me-1"></i>
                                    {{ $isEdit ? 'Actualizar Cambios' : 'Guardar y Publicar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card border-primary border">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="ti ti-eye me-1"></i> Previsualización en Tiempo Real
                        </h5>

                        <button id="btnRefreshPreview"
                                class="btn btn-sm btn-light text-primary"
                                type="button"
                                title="Refrescar">
                            <i class="ti ti-refresh"></i>
                        </button>

                    </div>
                    <div class="card-body p-0"
                         style="height: 600px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <div id="preview-placeholder" class="text-center" style="{{ $isEdit ? 'display: none;' : '' }}">
                            <i class="ti ti-report-analytics fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Ingrese una URL válida para ver el reporte aquí.</p>
                        </div>
                        <iframe id="iframe-preview" src="{{ $iframe->iframe_url ?? '' }}" frameborder="0"
                                style="width: 100%; height: 100%; {{ $isEdit ? 'display: block;' : 'display: none;' }}"
                                allowFullScreen="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSelectorModulos" tabindex="-1" aria-hidden="true"
         data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalSelectorModulos">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Selector de Ubicación del Reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 border-end">
                            <div class="p-3 text-center text-muted">Seleccione un Módulo Padre</div>
                            <div class="list-group list-group-flush" id="parent-list"
                                 style="max-height: 400px; overflow-y: auto;">
                            </div>
                        </div>
                        <div class="col-md-6 bg-light-subtle">
                            <div id="submodule-list" class="p-2" style="max-height: 400px; overflow-y: auto;">
                                <div class="text-center mt-5 text-muted">
                                    <i class="ti ti-arrow-left fs-1"></i>
                                    <p>Seleccione un módulo de la izquierda</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const modulesParents = "{{ route('modules.modulesParents') }}";
            const getSubmodules = "{{ route('modules.getSubmodules', ':id') }}";
            const saveOrUpdate = "{{ route('iframes.save') }}";

            // Variable útil para tu script JS
            const isEdit = {{ $isEdit ? 'true' : 'false' }};
            const method = "{{ $isEdit ? 'PUT' : 'POST' }}";
        </script>
        <script src="{{asset("/js/custom/iframesRegister.js")}}"></script>
    @endpush

</x-app-layout>
