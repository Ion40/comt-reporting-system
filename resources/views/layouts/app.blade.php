<!DOCTYPE html>
<html lang="en" data-layout="">

<head>
    <meta charset="utf-8"/>
    <title>Dashboard | Adminto - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset("/images/logo_oficial.png")}}">

    <!-- Vendor css -->
    <link href="{{asset("/css/vendor.min.css")}}" rel="stylesheet" type="text/css"/>

    <!-- App css -->
    <link href="{{asset("/css/app.min.css")}}" rel="stylesheet" type="text/css" id="app-style"/>

    <!-- Icons css -->
    <link href="{{asset("/css/icons.min.css")}}" rel="stylesheet" type="text/css"/>
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
                        <span class="logo-lg"><img src="{{asset("/images/logo_oficial.png")}}" alt="logo"></span>
                        <span class="logo-sm"><img src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
                    </span>

            <span class="logo-dark">
                        <span class="logo-lg"><img src="{{asset("/images/logo_oficial.png")}}" alt="dark logo"></span>
                        <span class="logo-sm"><img src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
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
                        <img src="{{asset("/images/user.png")}}" width="46" class=""
                             alt="user-image">
                        <span class="d-flex justify-content-center gap-1 sidenav-user-name my-2">
                                    <span>
                                        <span class="mb-0 fw-semibold lh-base fs-15">{{ Auth::user()->nombre }}</span>
                                        <p class="my-0 fs-13 text-muted">{{ Auth::user()->email }}</p>
                                    </span>
                                </span>
                    </a>
                </div>
            </div>

            <!--- Sidenav Menu -->
            <ul class="side-nav">
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarContacts" aria-expanded="false" aria-controls="sidebarContacts" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-shield-cog"></i></span>
                        <span class="menu-text"> Autorizaciones</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarContacts">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route("permission.index")  }}" class="side-nav-link">
                                    <span class="menu-text">Administrar Permisos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route("dashboard")  }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                        <span class="menu-text"> Compras e Importaciones </span>
                    </a>
                </li>
            </ul>

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
<script src="{{asset("/js/config.js")}}"></script>

<!-- Vendor js -->
<script src="{{asset("/js/vendor.min.js")}}"></script>

<!-- App js -->
<script src="{{asset("/js/app.js")}}"></script>

<!-- Apex Chart js -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

<!-- Projects Analytics Dashboard App js -->
<script src="assets/js/pages/dashboard.js"></script>

@stack('scripts')
</body>

</html>
