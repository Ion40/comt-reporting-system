<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>404 - Página no encontrada | {{ config("app.name") }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset("/images/logo_oficial.png")}}">

    <link href="{{asset("/css/vendor.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/css/app.min.css")}}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{asset("/css/icons.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/css/sweetalert2.min.css")}}" rel="stylesheet" type="text/css"/>

    <style>
        .error-text {
            text-shadow: 4px 4px 0px rgba(0,0,0,0.1);
            line-height: 1;
        }
        .bg-auth {
            background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
            min-height: 100vh;
        }

        @font-face {
            font-family: 'ComtechFont'; /* El nombre que tú quieras darle */
            src: url('../fonts/ijwRs572Xtc6ZYQws9YVwnNGfJ4.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap; /* Mejora la carga visual */
        }

        /* Ejemplo de uso */
        body {
            font-family: 'ComtechFont', sans-serif;
        }
    </style>
</head>

<body class="loading bg-auth">

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0">

                    <div class="card-body p-4">

                        <div class="text-center">
                            <div class="">
                                <a href="{{ url('/') }}" class="logo text-center">
                                        <span class="">
                                            <img width="100" src="{{asset("/images/logo_oficial.png")}}" alt="">
                                        </span>
                                </a>
                            </div>

                            <div class="mt-4">
                                <h1 style="font-size: 100px;">
                                    <span class="text-primary error-text fw-bolder">4</span>
                                    <span class="text-danger error-text fw-bolder">0</span>
                                    <span class="text-primary error-text fw-bolder">4</span>
                                </h1>
                                <h3 class="text-uppercase mt-3 fw-bold">Página No Encontrada</h3>
                                <p class="text-muted mt-3">
                                    Parece que has tomado un camino equivocado. El módulo o la ruta que intentas consultar no está disponible o ha sido movida permanentemente.
                                </p>

                                <a class="btn btn-primary rounded-pill waves-effect waves-light mt-3" href="{{ url('/dashboard') }}">
                                    <i class="fe-home me-1"></i> Regresar al Dashboard
                                </a>

                                <!--<div class="mt-4 pt-3 border-top">
                                    <p class="text-muted mb-2">¿Crees que esto es un error del sistema?</p>
                                    <button type="button" class="btn btn-outline-danger rounded-pill waves-effect waves-light btn-sm" id="btnReportError">
                                        <i class="fe-alert-triangle me-1"></i> Reportar este problema
                                    </button>
                                </div>-->

                            </div>

                        </div>
                    </div> </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white">© {{ date('Y') }} Sistema de Módulos - Todos los derechos reservados.</p>
                    </div> </div>
            </div> </div>
    </div>
</div>
<script src="{{asset("/js/vendor.min.js")}}"></script>
<script src="{{asset("/js/app.min.js")}}"></script>
<script src="{{asset("/js/sweetalert2.min.js")}}"></script>


<script>
    document.getElementById('btnReportError').addEventListener('click', async () => {
        const { value: text } = await Swal.fire({
            title: 'Reportar Error',
            input: 'textarea',
            inputLabel: 'Cuéntanos brevemente qué intentabas hacer',
            inputPlaceholder: 'Ej: Intentaba acceder al submódulo de facturación...',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Enviar Reporte',
            cancelButtonText: 'Cancelar',
            inputAttributes: { 'aria-label': 'Escribe tu mensaje aquí' }
        });

        if (text) {
            // Aquí enviarías el reporte vía AJAX a tu controlador
            /*try {
                const response = await fetch(" route('report.error') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': ' csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: text,
                        url: window.location.href, // Captura la URL exacta del 404
                        user_agent: navigator.userAgent
                    })
                });

                if (response.ok) {
                    Swal.fire('¡Enviado!', 'Gracias por ayudarnos a mejorar.', 'success');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo enviar el reporte.', 'error');
            }*/
        }
    });
</script>

</body>
</html>
