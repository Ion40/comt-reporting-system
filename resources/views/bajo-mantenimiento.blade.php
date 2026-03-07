<x-app-layout>
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-5 py-2">
                            <img src="{{ asset('/images/under-maintenance.png') }}"
                                 class="img-fluid"
                                 style="max-height: 280px; filter: drop-shadow(0px 20px 30px rgba(62, 96, 213, 0.2)); animation: float 6s ease-in-out infinite;"
                                 alt="Sistema en mantenimiento">
                        </div>

                        <span class="badge bg-soft-info text-info mb-3 px-3 py-2 rounded-pill fw-bold">
                            ACTUALIZACIÓN EN CURSO
                        </span>

                        <h2 class="text-dark fw-bold mb-3">
                            ¡Estamos Mejorando la Plataforma!
                        </h2>

                        <div class="px-lg-4">
                            <p class="fs-15 mb-2">
                                Nuestro equipo está realizando mejoras y ajustes importantes para ofrecerle una mejor experiencia de usuario y reportes más rápidos.
                            </p>
                            <p class="fs-15">
                                La plataforma estará disponible nuevamente muy pronto. <strong>¡Gracias por su comprensión!</strong>
                            </p>
                        </div>

                        <!-- <div class="mt-4 mb-4 px-lg-5">
                             <div class="d-flex justify-content-between mb-2">
                                 <span class="text-muted small fw-semibold">Progreso de optimización</span>
                                 <span class="text-primary small fw-bold">75%</span>
                             </div>
                             <div class="progress progress-sm" style="height: 8px; border-radius: 10px; background-color: #f1f3fa;">
                                 <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                      role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                 </div>
                             </div>
                         </div>

                         <hr class="my-4 opacity-25">

                         <div class="d-grid">
                             <p class="text-secondary fw-semibold mb-3">
                                 <i class="ti ti-headset me-1"></i> ¿Necesitas asistencia inmediata?
                             </p>
                             <a href="mailto:soporte@comtech.com.ni" class="btn btn-primary rounded-pill shadow-sm py-2">
                                 Contactar Soporte Técnico
                             </a>
                         </div>-->
                    </div>
                </div> <!-- end card-body-->
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .bg-soft-info {
            background-color: rgba(62, 96, 213, 0.1) !important;
        }
        </style>
</x-app-layout>
