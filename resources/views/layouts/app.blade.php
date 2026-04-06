<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark">

<head>
    <meta charset="utf-8"/>
    <title>{{ config("app.name")  }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset("/images/logo_oficial.png")}}">

    <link href="{{asset("/css/sweetalert2.min.css")}}" rel="stylesheet" type="text/css"/>

    <!-- Vendor css -->
    <link href="{{asset("/css/vendor.min.css")}}" rel="stylesheet" type="text/css"/>

    <!-- App css -->
    <link href="{{asset("/css/app.min.css")}}" rel="stylesheet" type="text/css" id="app-style"/>

    <!-- Icons css -->
    <link href="{{asset("/css/icons.min.css")}}" rel="stylesheet" type="text/css"/>

    <link href="{{asset("/css/dataTables.bootstrap5.min.css")}}" rel="stylesheet" type="text/css"/>

    <style>
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

        .powerbi-container {
            width: 100%;
            height: 100vh; /* 100% del alto de la ventana */
            overflow: hidden;
        }

        .powerbi-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Estilo para los textos del menú */
        .side-nav-link .menu-text {
            overflow: visible;
            white-space: normal;
            height: auto;
            background: inherit;
            position: relative;
            z-index: 10;
        }

        /* Ajuste para que el Select2 respete el espacio del label flotante */
        .form-floating > .select2-container .select2-selection--single {
            height: 58px !important; /* Altura estándar de form-floating */
            padding-top: 1.6rem !important;
        }

        /* Forzar al label interno a estar siempre en la parte superior */
        .form-floating > .select2-container + label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem) !important;
            opacity: 0.8;
            z-index: 5;
            pointer-events: none; /* Permite clickear el select a través del label */
        }

        /* Ajuste del texto seleccionado dentro de Select2 */
        .form-floating > .select2-container .select2-selection__rendered {
            padding-left: 0.75rem !important;
            line-height: 1.2 !important;
            margin-top: 5px !important;
        }

        /* Alineación de la flechita de Select2 */
        .form-floating > .select2-container .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-10%) !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

    </style>

    {{-- OBLIGATORIO --}}
    @livewireStyles

    {{-- SOLO UNA VEZ --}}
    @vite(['resources/js/app.js'])

</head>

<body>
<!-- Begin page -->
<div class="wrapper">

    <!-- Menu -->
    <!-- Sidenav Menu Start -->
    <div class="sidenav-menu">

        <!-- Brand Logo -->
        <a href="index.html" class="logo">
                    <span class="logo-light">
                        <span class="logo-lg"><img loading="lazy" src="{{asset("/images/logo_oficial.png")}}" alt="logo"></span>
                        <span class="logo-sm"><img loading="lazy" src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
                    </span>

            <span class="logo-dark">
                        <span class="logo-lg"><img loading="lazy" src="{{asset("/images/logo_oficial.png")}}" alt="dark logo"></span>
                        <span class="logo-sm"><img loading="lazy" src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
                    </span>
        </a>

        <!-- Sidebar Hover Menu Toggle Button -->
        <button class="button-sm-hover">
            <i class="ri-circle-line align-middle"></i>
        </button>

        <!-- Sidebar Menu Toggle Button -->
        <button class="sidenav-toggle-button">
            <i class="ri-menu-5-line fs-20"></i>
        </button>

        <!-- Full Sidebar Menu Close Button -->
        <button class="button-close-fullsidebar">
            <i class="ti ti-x align-middle"></i>
        </button>

        <div data-simplebar>

            <!-- User -->
            <div class="sidenav-user">
                <div class="dropdown-center text-center">
                    <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                       type="button" aria-haspopup="false" aria-expanded="false">
                        <!-- todo: poner imagen de usuario -->
                        <div class="avatar-xl mx-auto">
                            <span class="avatar-title bg-soft-{{ Auth::user()->avatar_color }} text-{{ Auth::user()->avatar_color }} rounded-circle fw-bold fs-28 border border-{{ Auth::user()->avatar_color }} border-opacity-10 shadow-sm rounded-circle fw-bold fs-24">{{ Auth::user()->initials }}</span>
                        </div>
                        <span class="d-flex justify-content-center gap-1 sidenav-user-name my-2">
                                    <span>
                                        <span class="mb-0 fw-semibold lh-base fs-15">{{ Auth::user()->nombre }}</span>
                                        <p class="my-0 fs-13 text-muted">{{ Auth::user()->email }}</p>
                                    </span>
                                </span>
                    </a>
                </div>
            </div>

           <livewire:permisos-actualizados/>

            <div class="clearfix"></div>
        </div>
    </div>

    <x-app-topbar/>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent">
                <form>
                    <div class="card mb-1">
                        <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                            <i class="ri-search-line fs-22"></i>
                            <input type="search" class="form-control border-0" id="search-modal-input"
                                   placeholder="Search for actions, people,">
                            <button type="submit" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">[esc]
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="page-container">

            {{ $slot }}

            <!-- Footer Start -->
            <footer class="footer">
                <div class="page-container">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start">
                            <script>document.write(new Date().getFullYear())</script>
                            © Todos los derechos reservados.
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
</div>

@livewireScripts

<!-- Theme Config Js -->
<script defer src="{{asset("/js/config.js")}}"></script>

<!-- Vendor js -->
<script  src="{{asset("/js/vendor.min.js")}}"></script>

<!-- App js -->
<script defer src="{{asset("/js/app.js")}}"></script>

<script defer src="{{asset("/js/sweetalert2.min.js")}}"></script>

<script defer src="{{asset("/js/dataTables.min.js")}}"></script>
<script defer src="{{asset("/js/dataTables.bootstrap5.min.js")}}"></script>

<script>
    const header = { 'X-CSRF-TOKEN': "{{csrf_token()}}" };

    const validateField = (element, length = null) => {
        // IMPORTANTE: Si el elemento o su contenedor están ocultos, no validamos
        if (!element.offsetParent && element.type !== 'hidden') {
            element.classList.remove("is-invalid", "is-valid");
            return true;
        }

        const value = element.value.trim();
        const fieldName = element.dataset.valid || "Este campo";
        let bandera = true,
            message = '';

        // Limpiamos estados previos
        element.classList.remove("is-invalid", "is-valid");

        // Para Select2, quitamos la clase del contenedor visual también
        if (element.classList.contains('select2-hidden-accessible')) {
            $(element).next('.select2-container').find('.select2-selection').removeClass('is-invalid is-valid');
        }

        const existingFeedback = element.parentElement.querySelector(".invalid-tooltip");
        if (existingFeedback) existingFeedback.remove();

        // --- LÓGICA DE VALIDACIÓN ---
        if (value === "" || value === null) {
            bandera = false;
            message = `${fieldName} es obligatorio`;
        } else if (length && value.length < length) { // Cambiado a < para ser más preciso
            bandera = false;
            message = `${fieldName} debe tener al menos ${length} caracteres`;
        }

        // Validación de Email
        if (bandera && element.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                bandera = false;
                message = "Ingresa un correo electrónico válido";
            }
        }

        // Validación de Password
        if (bandera && (element.id === 'confirm_pass_input')) {
            const password = document.getElementById("password_input").value;
            if (value !== password) {
                bandera = false;
                message = `Las contraseñas no coinciden`;
            }
        }

        // --- APLICACIÓN DE RESULTADOS ---
        if (!bandera) {
            element.classList.add("is-invalid");

            // Soporte para Select2 (resaltar el buscador)
            if (element.classList.contains('select2-hidden-accessible')) {
                $(element).next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }

            // Insertar Tooltip
            element.insertAdjacentHTML("afterend", `<div class="invalid-tooltip">${message}</div>`);
            return false;
        } else {
            element.classList.add("is-valid");
            return true;
        }
    };

    const language_datatable = {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
        "infoFiltered": "(Filtrado de _MAX_ total registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": '<i class="ti ti-search"></i> Buscar:',
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    };

    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    // Prevenimos el mensaje por defecto de Livewire
                    preventDefault();

                    // Usamos SweetAlert2 (que ya tienes en tu layout) para avisar
                    Swal.fire({
                        title: 'Sesión Expirada',
                        text: 'Tu sesión ha caducado por inactividad. Serás redirigido al login.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.reload(); // Esto lo mandará al login automáticamente
                    });
                }
            });
        });
    });
</script>

@stack('scripts')

</body>

</html>
