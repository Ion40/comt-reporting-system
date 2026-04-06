<x-app-layout>
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="ti ti-circle-check-filled me-2 fs-18"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Columna Izquierda: Información de Usuario -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-none border border-light-subtle">
                <div class="card-body">
                    <div class="text-center pb-3 border-bottom border-light">
                        <!-- Avatar Sutil Estilo Google (Fondo Suave) -->
                        <div class="avatar-xl mx-auto mb-3">
                            <span
                                class="avatar-title bg-soft-{{ Auth::user()->avatar_color }} text-{{ Auth::user()->avatar_color }} rounded-circle fw-bold fs-28 border border-{{ Auth::user()->avatar_color }} border-opacity-10 shadow-sm">
                                {{ Auth::user()->initials }}
                            </span>
                        </div>

                        <h4 class="mb-1 fw-bold text-dark">{{ Auth::user()->nombre }}</h4>
                        <p class="text-muted mb-3 fs-13">{{ Auth::user()->email }}</p>

                        <span class="badge bg-soft-success text-success px-3 py-1 rounded-pill fw-semibold">
                            <i class="ti ti-point-filled me-1"></i>Cuenta Activa
                        </span>
                    </div>

                    <div class="mt-4">
                        <h5 class="text-uppercase fs-11 fw-bold text-muted mb-3 tracking-wider">Datos Personales</h5>

                        <div class="d-flex align-items-start mb-3">
                            <i class="ti ti-user-circle text-muted fs-20 me-2"></i>
                            <div>
                                <p class="mb-0 fs-12 text-muted">Nombre Completo</p>
                                <h6 class="mb-0 fs-14">{{ Auth::user()->nombre }}</h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <i class="ti ti-mail text-muted fs-20 me-2"></i>
                            <div>
                                <p class="mb-0 fs-12 text-muted">Correo Electrónico</p>
                                <h6 class="mb-0 fs-14">{{ Auth::user()->email }}</h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <i class="ti ti-calendar-event text-muted fs-20 me-2"></i>
                            <div>
                                <p class="mb-0 fs-12 text-muted">Miembro desde</p>
                                <h6 class="mb-0 fs-14">{{ Auth::user()->created_at->format('d M, Y') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Cambio de Contraseña -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-none border border-light-subtle">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-shield-lock me-2 text-primary fs-20"></i>Seguridad de la Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4 fs-14">
                        Actualice su información de acceso. Se recomienda utilizar una clave que no use en otros sitios.
                    </p>

                    @if($errors->any())
                        <div class="alert alert-soft-danger border-0 mb-4">
                            <ul class="mb-0 fs-13">
                                @foreach($errors->all() as $error)
                                    <li><i class="ti ti-alert-triangle me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update.custom') }}" id="form-profile-pass">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-5">
                                <label for="current_password" class="form-label fw-bold text-dark">Contraseña
                                    Actual</label>
                                <p class="text-muted fs-12">Para verificar su identidad.</p>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control bg-light border-light shadow-none"
                                           id="current_password" name="current_password" required
                                           placeholder="Clave temporal o actual">
                                    <button class="btn btn-light border-light text-muted shadow-none" type="button"
                                            onclick="togglePassword('current_password', this)">
                                        <i class="ti ti-eye fs-18"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="row mb-4">
                            <div class="col-md-5">
                                <label for="password" class="form-label fw-bold text-dark">Nueva Contraseña</label>
                                <p class="text-muted fs-12">Mínimo 6 caracteres.</p>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control shadow-none" id="password"
                                           name="password" required oninput="handlePasswordInput()"
                                           placeholder="Nueva clave">
                                    <button class="btn btn-outline-light text-muted shadow-none" type="button"
                                            onclick="togglePassword('password', this)">
                                        <i class="ti ti-eye fs-18"></i>
                                    </button>
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div id="strength-bar" class="progress-bar rounded" role="progressbar"
                                         style="width: 0%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span id="strength-text" class="fs-11 text-muted text-uppercase fw-bold">Seguridad: Pendiente</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-5">
                                <label for="password_confirmation" class="form-label fw-bold text-dark">Confirmar
                                    Contraseña</label>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control shadow-none" id="password_confirmation"
                                           name="password_confirmation" required oninput="validateMatch()"
                                           placeholder="Repita la clave">
                                    <button class="btn btn-outline-light text-muted shadow-none" type="button"
                                            onclick="togglePassword('password_confirmation', this)">
                                        <i class="ti ti-eye fs-18"></i>
                                    </button>
                                </div>
                                <div id="match-error" class="text-danger fs-11 mt-2 d-none fw-bold">
                                    <i class="ti ti-circle-x me-1"></i> Las contraseñas no coinciden.
                                </div>
                                <div id="match-success" class="text-success fs-11 mt-2 d-none fw-bold">
                                    <i class="ti ti-circle-check me-1"></i> Las contraseñas coinciden.
                                </div>
                            </div>
                        </div>

                        <div class="text-end pt-3">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" id="btn-submit">
                                <i class="ti ti-device-floppy me-1"></i> Actualizar Contraseña
                            </button>
                            <button type="button" id="btn-loading" class="btn btn-primary d-none px-4" disabled>
                                <span class="spinner-border spinner-border-sm me-1"></span> Procesando...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle('ti-eye');
            icon.classList.toggle('ti-eye-off');
        }

        function handlePasswordInput() {
            const currentPass = document.getElementById('current_password').value;
            const newPass = document.getElementById('password').value;
            checkStrength(newPass);
            if (newPass.length > 0 && currentPass === newPass) {
                document.getElementById('password').classList.add('is-invalid');
            } else {
                document.getElementById('password').classList.remove('is-invalid');
            }
            if (document.getElementById('password_confirmation').value.length > 0) validateMatch();
        }

        function validateMatch() {
            const val1 = document.getElementById('password').value;
            const val2 = document.getElementById('password_confirmation').value;
            const error = document.getElementById('match-error');
            const success = document.getElementById('match-success');

            if (val2.length > 0 && val1 !== val2) {
                error.classList.remove('d-none');
                success.classList.add('d-none');
                return false;
            } else if (val2.length > 0) {
                error.classList.add('d-none');
                success.classList.remove('d-none');
                return true;
            }
            return false;
        }

        function checkStrength(p) {
            let s = 0;
            if (p.length >= 6) s++;
            if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
            if (/[0-9]/.test(p)) s++;
            if (/[^A-Za-z0-9]/.test(p)) s++;

            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            const states = [
                {w: '10%', c: 'bg-danger', t: 'Muy Débil'},
                {w: '25%', c: 'bg-danger', t: 'Débil'},
                {w: '50%', c: 'bg-warning', t: 'Media'},
                {w: '75%', c: 'bg-info', t: 'Fuerte'},
                {w: '100%', c: 'bg-success', t: 'Excelente'}
            ];
            let res = states[s] || states[0];
            bar.className = "progress-bar " + res.c;
            bar.style.width = res.w;
            text.innerText = "Seguridad: " + res.t;
        }

        document.getElementById('form-profile-pass').addEventListener('submit', function (e) {
            if (document.getElementById('password').value !== document.getElementById('password_confirmation').value) {
                e.preventDefault();
                return false;
            }
            document.getElementById('btn-submit').classList.add('d-none');
            document.getElementById('btn-loading').classList.remove('d-none');
        });
    </script>
</x-app-layout>
