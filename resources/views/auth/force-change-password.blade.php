<!DOCTYPE html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark">

<head>
    <meta charset="utf-8"/>
    <title>Actualizar Contraseña | {{ config("app.name") }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vendor css -->
    <link href="{{asset("/css/vendor.min.css")}}" rel="stylesheet" type="text/css"/>
    <!-- App css -->
    <link href="{{asset("/css/app.min.css")}}" rel="stylesheet" type="text/css" id="app-style"/>
    <!-- Icons css -->
    <link href="{{asset("/css/icons.min.css")}}" rel="stylesheet" type="text/css"/>

    <style>
        @font-face {
            font-family: 'ComtechFont';
            src: url('../fonts/ijwRs572Xtc6ZYQws9YVwnNGfJ4.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'ComtechFont', sans-serif;
        }

        .auth-card {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-radius: 1.25rem;
        }

        .password-strength-bar {
            height: 5px;
            transition: all 0.3s ease;
        }

        .auth-pass-inputgroup .form-control {
            padding-right: 3.5rem;
        }

        .password-toggle-btn {
            z-index: 10;
            padding: 0.75rem;
        }

        /* Estilo para error de coincidencia */
        .is-invalid-match {
            border-color: #f1556c !important;
            background-image: none !important;
        }
    </style>
</head>

<body>

<div class="auth-bg auth-page-wrapper d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="text-center mb-4 mt-2">
                    <a href="javascript:void(0)" class="d-inline-block auth-logo">
                        <img src="{{asset("/images/logo_oficial.png")}}" alt="logo" height="70">
                    </a>
                    <p class="mt-3 fs-15 fw-medium text-muted uppercase tracking-wider">Reporting System</p>
                </div>

                <div class="card auth-card overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <div class="avatar-lg mx-auto">
                                <div class="avatar-title bg-soft-warning text-warning display-5 rounded-circle">
                                    <i class="ti ti-shield-lock"></i>
                                </div>
                            </div>
                            <h4 class="text-primary mt-4">Actualización Requerida</h4>
                            <div class="alert alert-info d-flex align-items-center border border-info text-start" role="alert">
                                <i class="ti ti-info-circle fs-2 me-2"></i>
                                <div>
                                    <small><strong>Por motivos de seguridad,</strong> debe establecer una nueva contraseña para continuar navegando.</small>
                                </div>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger border-0 bg-soft-danger text-danger mb-4" role="alert">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="p-2 mt-2">
                            <form method="POST" action="{{ route('password.update.custom') }}" id="form-update-pass">
                                @csrf
                                @method('PUT')

                                <!-- Contraseña Actual -->
                                <div class="mb-3">
                                    <div class="form-floating auth-pass-inputgroup position-relative">
                                        <input type="password" class="form-control" placeholder="Ingrese clave actual"
                                               id="current_password" name="current_password" required>
                                        <label for="current_password">Contraseña Temporal / Actual</label>
                                        <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted password-toggle-btn"
                                                type="button" onclick="togglePassword('current_password')">
                                            <i class="ti ti-eye fs-18" id="icon-current_password"></i>
                                        </button>
                                        {{-- Mensaje de error específico debajo del input --}}
                                        @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4 text-muted opacity-25">

                                <!-- Nueva Contraseña -->
                                <div class="mb-3">
                                    <div class="form-floating auth-pass-inputgroup position-relative">
                                        <input type="password" class="form-control" placeholder="Mínimo 8 caracteres"
                                               id="password" name="password" required oninput="handlePasswordInput()">
                                        <label for="password">Nueva Contraseña</label>
                                        <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted password-toggle-btn"
                                                type="button" onclick="togglePassword('password')">
                                            <i class="ti ti-eye fs-18" id="icon-password"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress password-strength-bar bg-light">
                                            <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <p id="strength-text" class="fs-11 text-muted mt-1 mb-0 text-uppercase fw-bold">Seguridad: No definida</p>
                                    </div>
                                </div>

                                <!-- Confirmación -->
                                <div class="mb-4">
                                    <div class="form-floating auth-pass-inputgroup position-relative">
                                        <input type="password" class="form-control" placeholder="Repita su nueva clave"
                                               id="password_confirmation" name="password_confirmation" required oninput="validateMatch()">
                                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                        <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted password-toggle-btn"
                                                type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="ti ti-eye fs-18" id="icon-password_confirmation"></i>
                                        </button>
                                    </div>
                                    <div id="match-error" class="text-danger fs-5 mt-1 d-none fw-bold">
                                        <i class="ti ti-x me-1"></i> Las contraseñas no coinciden.
                                    </div>
                                    <div id="match-success" class="text-success fs-5 mt-1 d-none fw-bold">
                                        <i class="ti ti-circle-check me-1"></i> Las contraseñas coinciden.
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-primary w-100 shadow-sm py-2 fw-bold" type="submit" id="btn-submit">
                                        <i class="ti ti-device-floppy me-1"></i> Actualizar y Acceder
                                    </button>
                                    <button type="button" id="btn-loading" class="btn btn-primary w-100 d-none" disabled>
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Procesando...
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset("/js/vendor.min.js")}}"></script>
<script src="{{asset("/js/app.js")}}"></script>

<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('btn-submit');
    const matchError = document.getElementById('match-error');
    const matchSuccess = document.getElementById('match-success');

    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('ti-eye', 'ti-eye-off');
        } else {
            input.type = "password";
            icon.classList.replace('ti-eye-off', 'ti-eye');
        }
    }

    // Valida si las claves coinciden
    function validateMatch() {
        const val1 = passwordInput.value;
        const val2 = confirmInput.value;

        if (val2.length > 0 && val1 !== val2) {
            confirmInput.classList.add('is-invalid-match');
            matchError.classList.remove('d-none');
            matchSuccess.classList.add('d-none');
            return false;
        } else {
            confirmInput.classList.remove('is-invalid-match');
            matchError.classList.add('d-none');
            matchSuccess.classList.remove('d-none');
            return true;
        }
    }

    // Ejecuta fuerza y validación de match al mismo tiempo
    function handlePasswordInput() {
        const currentPass = document.getElementById('current_password').value;
        const newPass = passwordInput.value;

        // 1. Validar fuerza de la clave
        checkStrength(newPass);

        // 2. Validar que sea diferente a la actual (Regla different)
        if (newPass.length > 0 && currentPass === newPass) {
            passwordInput.classList.add('is-invalid-match');
            // Opcional: podrías mostrar un pequeño texto de advertencia aquí
        } else {
            passwordInput.classList.remove('is-invalid-match');
        }

        // 3. Validar coincidencia con la confirmación
        if (confirmInput.value.length > 0) {
            validateMatch();
        }
    }

    function checkStrength(p) {
        let s = 0;
        if (p.length >= 8) s++;
        if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;

        const bar = document.getElementById('strength-bar');
        const text = document.getElementById('strength-text');

        bar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');

        const states = [
            {w: '5%', c: 'bg-secondary', t: 'Muy Corta'},
            {w: '25%', c: 'bg-danger', t: 'Débil'},
            {w: '50%', c: 'bg-warning', t: 'Media'},
            {w: '75%', c: 'bg-info', t: 'Fuerte'},
            {w: '100%', c: 'bg-success', t: 'Excelente'}
        ];

        let res = states[s];
        bar.style.width = res.w;
        bar.classList.add(res.c);
        text.innerText = "Seguridad: " + res.t;
        text.className = "fs-11 mt-1 mb-0 text-uppercase fw-bold " + res.c.replace('bg-', 'text-');
    }

    document.getElementById('form-update-pass').addEventListener('submit', function (e) {
        // Validación final antes de enviar
        if (!validateMatch()) {
            e.preventDefault();
            return false;
        }

        if (this.checkValidity()) {
            submitBtn.classList.add('d-none');
            document.getElementById('btn-loading').classList.remove('d-none');
        }
    });
</script>

</body>
</html>
