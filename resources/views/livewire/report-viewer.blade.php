<div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between">
                    <h4 class="header-title mb-0 text-primary">
                        {{ $modulo->reportIframe ? $modulo->reportIframe->title : $modulo->name }}
                    </h4>
                </div>
                <div class="card-body p-0">
                    @if($modulo && $modulo->reportIframe)
                        @if($modulo->reportIframe && $modulo->reportIframe->is_active)
                            {{-- CASO 1: DIBUJAR IFRAME --}}
                            <div class="powerbi-container" style="height: 82vh; background: #fdfdfd;">
                                <iframe
                                    src="{{ $modulo->reportIframe->iframe_url }}"
                                    frameborder="0"
                                    allowFullScreen="true"
                                    loading="lazy"
                                    style="width: 100%; height: 100%;">
                                </iframe>
                            </div>
                        @else
                            {{-- CASO 2: CARGAR VISTA BLADE --}}
                            <div class="p-4">
                                <x-reporte-inactivo title="{{ $modulo->reportIframe->title  }}" fechaBaja="{{ $modulo->reportIframe->created_at  }}"/>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            El reporte no está disponible o la URL ha cambiado.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
