<!DOCTYPE html>
<html lang="en" data-layout="">

<head>
    <meta charset="utf-8"/>
    <title>{{ config("app.name")  }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

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
    </style>
</head>

<body>

<div class="auth-bg d-flex min-vh-100">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xxl-3 col-lg-5 col-md-6">
            <a href="index.html" class="auth-brand d-flex justify-content-center mb-2">
                <img src="{{asset("/images/logo_oficial.png")}}" alt="dark logo" height="80" class="logo-dark">
                <img src="{{asset("/images/logo_oficial.png")}}" alt="logo light" height="26" class="logo-light">
            </a>

            <p class="fw-semibold mb-4 text-center  fs-15">Bienvenido a Com. Reporting System</p>

            <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">

                <h4 class="fw-semibold mb-3 fs-18">Inicia sesión con tu cuenta</h4>

                <form method="POST" action="{{ url('login') }}" class="text-start mb-3">
                    @csrf

                    @if($errors->any())
                        <div
                            class="alert alert-danger alert-dismissible d-flex align-items-center border-2 border border-danger fade show mb-xl-0 mb-3"
                            role="alert" aria-label="Close">
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1"><strong>Error - </strong>
                                @foreach($errors->all() as $error)
                                    <div class="mt-1">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fs-5" for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" class="form-control"
                               value="{{ old("correo")  }}"
                               placeholder="Ingresa tu correo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Ingresa tu contraseña">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">Recuérdame</label>
                        </div>

                        <a href="auth-recoverpw.html" class="text-muted border-bottom border-dashed">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>

                    <div class="d-grid">
                        <button id="btn-login" type="submit" class="btn btn-primary">
                            Iniciar Sesión
                        </button>
                        <button type="button" id="btn-loading" class="btn btn-primary btn-load d-none" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Cargando...
                        </button>
                    </div>
                </form>

                <!--<p class="text-muted fs-14 mb-0">Don't have an account?
                    <a href="auth-register.html" class="fw-semibold text-danger ms-1">Sign Up !</a>
                </p>-->

            </div>
            <p class="mt-4 text-center mb-0">
                <script>document.write(new Date().getFullYear())</script>
                © Todos los derechos reservados.
            </p>
        </div>
    </div>
</div>

<!-- Theme Config Js -->
<script src="{{asset("/js/config.js")}}"></script>

<!-- Vendor js -->
<script src="{{asset("/js/vendor.min.js")}}"></script>

<!-- App js -->
<script src="{{asset("/js/app.js")}}"></script>

<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        // Only toggle if the form is valid (browser native validation check)
        if (this.checkValidity()) {
            document.getElementById('btn-login').classList.add('d-none');
            document.getElementById('btn-loading').classList.remove('d-none');
        }
    });
</script>

</body>

</html>
