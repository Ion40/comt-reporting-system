<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Recuperar Contraseña | {{config("app.name")}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset("/images/logo_oficial.png")}}">

    <link href="{{asset("/css/vendor.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/css/app.min.css")}}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{asset("/css/icons.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/css/sweetalert2.min.css")}}" rel="stylesheet" type="text/css"/>

    <style>

        @font-face {
            font-family: 'ComtechFont'; /* El nombre que tú quieras darle */
            src: url('../fonts/ijwRs572Xtc6ZYQws9YVwnNGfJ4.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap; /* Mejora la carga visual */
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        body {
            font-family: 'ComtechFont', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            background-image: url(../images/img_comtech.webp);
            background-size: cover;
            background-position: center;
        }
        .auth-card { box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .step-container { display: none; }
        .step-container.active { display: block; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .wizard-steps { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .step-dot { width: 30px; height: 30px; border-radius: 50%; background: #e3e8f7; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #adb5bd; }
        .step-dot.active { background: #3f51b5; color: white; }
        .step-dot.completed { background: #1abc9c; color: white; }
    </style>
</head>

<body class="w-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5 col-md-7">
            <div class="card auth-card overflow-hidden">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <img src="{{asset("/images/logo_oficial.png")}}" alt="logo" height="50" class="mb-3">
                        <h4 class="fw-bold fs-18">Restablecer Contraseña</h4>
                    </div>

                    <div class="wizard-steps">
                        <div class="step-dot active" id="dot-1">1</div>
                        <div class="step-dot" id="dot-2">2</div>
                        <div class="step-dot" id="dot-3">3</div>
                    </div>

                    <div id="step-1" class="step-container active">
                        <p class="text-muted text-center mb-4">Ingresa tu correo para recibir el token de seguridad.</p>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-mail fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="email" class="form-control border-start-0"
                                           id="email_recover_input"
                                           data-valid="Correo de recuperación"
                                           name="email_recover_input"
                                           placeholder="Ingresar correo"
                                           oninput="validateField(this,8)">
                                    <label for="email_recover_input">Correo electrónico</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button id="btnSendToken" class="btn btn-primary fw-semibold" onclick="handleSendToken()">
                               <i class=""></i>
                                Enviar Token
                            </button>
                        </div>
                    </div>

                    <div id="step-2" class="step-container">
                        <p class="text-muted text-center mb-4">Hemos enviado un código a su correo. Por favor, ingréselo a continuación.</p>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-code-circle fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="text" class="form-control border-start-0"
                                           id="token_input"
                                           data-valid="Token"
                                           name="token_input"
                                           autocomplete="off"
                                           placeholder="Ingresar correo"
                                           oninput="validateField(this,8)">
                                    <label for="token_input">Token de Seguridad</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mb-3">
                            <button id="btnValidateToken" class="btn btn-success fw-semibold" onclick="handleValidateToken()">Validar Código</button>
                        </div>
                        <div class="text-center">
                            <button id="btnResendToken" class="btn btn-link btn-sm text-primary" onclick="handleSendToken(true)">¿No recibiste el correo? Reenviar</button>
                        </div>
                    </div>

                    <div id="step-3" class="step-container">
                        <p class="text-muted text-center mb-4">El token es válido. Define tu nueva contraseña.</p>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-password-user fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="password" class="form-control border-start-0"
                                           id="password_input"
                                           data-valid="Nueva Contraseña"
                                           name="password_input"
                                           placeholder="********"
                                           oninput="validateField(this,6)">
                                    <label for="password_input">Nueva Contraseña</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-password-user fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="password" class="form-control border-start-0"
                                           id="confirm_pass_input"
                                           data-valid="Nueva Contraseña"
                                           name="confirm_pass_input"
                                           placeholder="********"
                                           oninput="validateField(this,6)">
                                    <label for="confirm_pass_input">Repetir Contraseña</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button id="btnChangePass" class="btn btn-primary fw-semibold" onclick="handleUpdatePassword()">Actualizar Contraseña</button>
                        </div>
                    </div>

                    <div class="text-center mt-3 d-grid">
                        <a href="{{ route('login') }}" class="btn btn-danger fw-bold fs-14">
                            <i class="ti ti-arrow-left me-1"></i>
                            Regresar al Login
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset("/js/config.js")}}"></script>

<!-- Vendor js -->
<script src="{{asset("/js/vendor.min.js")}}"></script>

<!-- App js -->
<script src="{{asset("/js/app.js")}}"></script>

<script src="{{asset("/js/sweetalert2.min.js")}}"></script>

<script>
    // Configuración de Rutas (Asegúrate de que existan en tu routes/web.php)
    const ROUTES = {
        send: "{{ route('recover.sendResetToken') }}",
        validate: "{{ route('recover.validateToken') }}", // Debes crear esta ruta
        update: "{{ route('recover.resetPassword') }}"   // Debes crear esta ruta
    };
    const csrf_token = "{{ csrf_token() }}";

    // Navegación del Wizard
    function goToStep(stepNumber) {
        document.querySelectorAll('.step-container').forEach(s => s.classList.remove('active'));
        document.getElementById(`step-${stepNumber}`).classList.add('active');

        // Actualizar Dots
        for(let i=1; i<=3; i++) {
            const dot = document.getElementById(`dot-${i}`);
            if(i < stepNumber) { dot.className = 'step-dot completed'; dot.innerHTML = '✓'; }
            else if(i === stepNumber) { dot.className = 'step-dot active'; dot.innerHTML = i; }
            else { dot.className = 'step-dot'; dot.innerHTML = i; }
        }
    }

    // --- ACCIÓN 1: ENVIAR TOKEN ---
    async function handleSendToken(isResend = false) {
        const btn = document.getElementById("btnSendToken");
        const btnResend = document.getElementById("btnResendToken");

        const emailEl = document.getElementById("email_recover_input");
        btn.disabled = true;
        btnResend.disabled = true;

        if (!validateField(emailEl, 8)) {
            btn.disabled = false;
            btnResend.disabled = false;
            return;
        }

        showLoading(isResend ? 'Reenviando correo...' : 'Enviando token...');
        btn.disabled = false;
        btnResend.disabled = false;

        try {
            const res = await fetch(ROUTES.send, {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: emailEl.value })
            });
            const data = await res.json();

            if (data.status === "success") {
                Swal.fire('¡Éxito!', data.message, 'success');
                if(!isResend) goToStep(2);
            } else {
                throw new Error(data.message || 'Error al enviar');
            }
        } catch (e) {
            Swal.fire('Error', e.message, 'error');
        }
    }

    // --- ACCIÓN 2: VALIDAR TOKEN ---
    async function handleValidateToken() {
        const tokenEl = document.getElementById("token_input");
        const btnValidate = document.getElementById("btnValidateToken");
        const email = document.getElementById("email_recover_input").value;

        btnValidate.disabled = true;

        if (!validateField(tokenEl, 10))
        {
            btnValidate.disabled = false;
            return;
        }

        showLoading('Validando token...');
        btnValidate.disabled = false;

        try {
            const res = await fetch(ROUTES.validate, {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token: tokenEl.value, email: email })
            });
            const data = await res.json();

            if (data.success) {
                Swal.close();
                goToStep(3);
            } else {
                throw new Error(data.message);
            }
        } catch (e) {
            Swal.fire('Token Inválido', e.message, 'error');
        }
    }

    // --- ACCIÓN 3: ACTUALIZAR PASSWORD ---
    async function handleUpdatePassword() {
        const pass = document.getElementById("password_input");
        const confirm = document.getElementById("confirm_pass_input");
        const token = document.getElementById("token_input").value;
        const email = document.getElementById("email_recover_input").value;
        const btnChangePass = document.getElementById("btnChangePass");

        btnChangePass.disabled = true;

        if (!validateField(pass, 6) || !validateField(confirm, 6))
        {
            btnChangePass.disabled = false;
            return;
        }
        if (pass.value !== confirm.value) {
            confirm.classList.add('is-invalid');
            return Swal.fire('Error', 'Las contraseñas no coinciden', 'warning');
        }


        showLoading('Actualizando contraseña...');
        btnChangePass.disabled = false;

        try {
            const res = await fetch(ROUTES.update, {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    password: pass.value,
                    password_confirmation: confirm.value,
                    token: token,
                    email: email
                })
            });
            const data = await res.json();

            if (data.success) {
                await Swal.fire('¡Listo!', 'Contraseña actualizada. Ahora puedes iniciar sesión.', 'success');
                window.location.href = "{{ route('login') }}";
            } else {
                throw new Error(data.message);
            }
        } catch (e) {
            Swal.fire('Error', e.message, 'error');
        }
    }

    function showLoading(text) {
        Swal.fire({ title: text, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    }

    // Tu función validateField (se mantiene igual pero con mejoras de tooltip)
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
</script>

</body>
</html>
