@php use Carbon\Carbon; @endphp
<div>
    <div class="row">
        @forelse($iframes as $iframe)
            <div class="col-xl-4 col-md-6">
                <div class="card shadow-none border border-dashed {{ $iframe->is_active ? 'border-success' : 'border-danger'  }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h4 class="mt-0 mb-1">
                                    {{ $iframe->title }}
                                </h4>
                                <p class="text-primary fw-semibold fs-12 mb-0">
                                    <a class="text-primary"
                                       href="report-viewer/{{ $iframe->url_path }}">/{{ $iframe->url_path }}</a>
                                </p>
                            </div>
                            <div class="text-end">
                                @if($iframe->is_active)
                                    <div class="badge badge-soft-success p-1">Reporte en uso</div>
                                @else
                                    <div class="badge badge-soft-danger p-1">Reporte inactivo</div>
                                @endif
                                <p class="text-muted fs-13 mt-1 mb-0">
                                    <i class="ti ti-calendar-event"></i> {{ Carbon::parse($iframe->created_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <h5 class="me-2 mb-0 fs-13 text-muted">
                                <i class="ti ti-folder-cog fs-18"></i> Módulo Asignado:
                            </h5>
                            <span class="badge badge-outline-info text-dark fs-6">
                                {{ $iframe->name ?? 'Sin asignar' }}
                            </span>
                        </div>

                        <hr class="my-3 opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            @if($iframe->is_active)
                                <div class="btn-group">
                                    <a href="{{ route('iframes.edit', $iframe->url_path) }}"
                                       class="btn  btn-light border"
                                       title="Editar">
                                        <i class="ti ti-edit fs-16 text-warning"></i>
                                    </a>
                                    <button type="button" class="btn  btn-light border btn-baja-iframe" title="Dar de baja"
                                    onclick="deleteIframeFn('{{ $iframe->url_path }}')">
                                        <i class="ti ti-trash fs-16 text-danger"></i>
                                    </button>
                                </div>

                                <a href="report-viewer/{{ $iframe->url_path }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-external-link me-1"></i> Abrir
                                </a>
                            @else
                                <a href="#" class="btn btn-sm btn-outline-danger">
                                    <i class="ti ti-restore me-1"></i> Reactivar Reporte
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="ti ti-search fs-1 text-muted"></i>
                <p class="text-muted">No se encontraron iframes disponibles.</p>
            </div>
        @endforelse
    </div>

    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <p class="fs-14 m-0 text-body text-muted">
                Mostrando <span class="text-body fw-semibold">{{ $iframes->count() }}</span> registros
            </p>
        </div>
    </div>

</div>
