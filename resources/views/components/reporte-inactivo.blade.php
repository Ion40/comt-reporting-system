@props([
    'title' => '',
    'fechaBaja' => '',
])
    <div class="row justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card border-0 shadow-lg mt-4" style="border-radius: 15px; overflow: hidden;">
                <div class="bg-danger" style="height: 4px;"></div>

                <div class="card-body p-3">
                    <div class="text-center">
                        <div class="mb-2 py-2">
                            <img src="{{ asset('/images/delete-file.png') }}"
                                 class="img-fluid"
                                 width="150px"
                                 alt="Reporte Inactivo">
                        </div>

                        <span class="badge bg-soft-danger text-danger mb-3 px-3 py-2 rounded-pill fw-bold fs-12">
                            <i class="ti ti-circle-x me-1 fs-5 "></i> REPORTE FUERA DE SERVICIO
                        </span>

                        <h2 class="text-dark fw-bold mb-3">
                            Contenido no disponible
                        </h2>

                        <div class="px-lg-4">
                            <p class="text-muted fs-15 mb-4">
                                Lo sentimos, el reporte que intenta visualizar ha sido <strong>desactivado o dado de baja</strong> del sistema por el administrador.
                            </p>

                            <div class="bg-light p-3 rounded-3 mb-2">
                                <p class="text-secondary mb-0">
                                    <i class="ti ti-info-circle me-1 text-primary fs-3"></i>
                                    Si considera que esto se trata de un error técnico o necesita recuperar el acceso a esta información,
                                    por favor póngase en contacto con el departamento correspondiente.
                                </p>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="ti ti-smart-home me-1"></i> Ir al Inicio
                            </a>

                            <a href="mailto:administrador@tuempresa.com" class="btn btn-danger px-4 py-2 shadow-sm">
                                <i class="ti ti-mail-forward me-1"></i> Contactar Administrador
                            </a>
                        </div>
                    </div>
                </div> </div>

            <p class="text-center text-muted mt-4 small">
                ID del Reporte: <span class="fw-semibold">{{ $title ?? 'N/A' }}</span> |
                Fecha de baja: <span class="fw-semibold">{{ $fechaBaja ?? 'N/A'}}</span>
            </p>
        </div>
    </div>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        .bg-soft-danger {
            background-color: rgba(239, 71, 111, 0.1) !important;
        }
        /* Opcional: Estilo para iconos Tabler si no están cargados */
        .fs-12 { font-size: 0.75rem; }
        .fs-15 { font-size: 0.9375rem; }
    </style>
