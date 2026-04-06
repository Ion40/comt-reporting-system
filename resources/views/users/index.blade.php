<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card h-auto">
                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                    <h4 class="header-title me-auto"><i class="ti ti-users me-auto fs-4"></i> Lista de usuarios</h4>

                    <div class="d-flex gap-2 justify-content-end text-end">
                        <a href="#" class="btn btn-primary" id="btnAddUser"><i
                                class="ti ti-user-plus me-1 fs-4"></i>Agregar Usuario</a>
                    </div>
                </div>

               <div class="card-body p-0 pb-3">
                   <div class="table-responsive">
                       <table class="table table-centered table-striped align-middle" id="users-table">
                           <thead class="table-dark">
                           <tr>
                               <th class="fs-12 text-uppercase text-muted py-1">Nombre</th>
                               <th class="fs-12 text-uppercase text-muted py-1">Correo</th>
                               <th class="fs-12 text-uppercase text-muted py-1">Fecha Creación</th>
                               <th class="fs-12 text-uppercase text-muted py-1">Fecha Modificación</th>
                               <th class="fs-12 text-uppercase text-muted py-1">Estado</th>
                               <th class="text-center  py-1 fs-12 text-uppercase text-muted"
                                   style="width: 120px;">Acciones
                               </th>
                           </tr>
                           </thead>
                           <tbody>

                           </tbody>
                       </table>
                   </div>
               </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUser" data-bs-backdrop="static"
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalUserLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalUserLabel">...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div> <!-- end modal header -->
                <div class="modal-body">
                    <form id="form_user">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-user fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="hidden" name="id_user_input" id="id_user_input">
                                    <input type="text"
                                           data-valid="Usuario"
                                           name="user_name_input"
                                           id="user_name_input" class="form-control validate"
                                           placeholder="Nombre de usuario">
                                    <label class="form-label">Nombre de usuario</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-mail fs-4"></i></span>
                                <div class="form-floating">
                                    <input type="email"
                                           data-valid="Correo"
                                           name="user_mail_input"
                                           id="user_mail_input" class="form-control validate"
                                           placeholder="Correo">
                                    <label class="form-label">Correo</label>
                                </div>
                            </div>
                            <small class="text-primary mt-1 d-block">
                                <i class="ti ti-info-circle me-1"></i>
                                El correo que ingrese será utilizado para recuperar la cuenta asociada en caso que no recuerde la contraseña.
                            </small>
                        </div>

                        <div id="div_pass">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock fs-4"></i></span>
                                    <div class="form-floating">
                                        <input type="password"
                                               data-valid="Contraseña"
                                               name="password_input"
                                               id="password_input"
                                               class="form-control validate"
                                               placeholder="Ingresar contraseña" required>
                                        <label class="form-label">Contraseña</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-lock-check fs-4"></i></span>
                                    <div class="form-floating">
                                        <input type="password"
                                               data-valid="Confirmar Contraseña"
                                               name="confirm_pass_input"
                                               id="confirm_pass_input" class="form-control validate"
                                               placeholder="Ingresar contraseña">
                                        <label class="form-label">Confirmar Contraseña</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>
                        Cerrar
                    </button>
                    <button id="btnSaveUpdated" type="button" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-1 fs-4"></i>
                    </button>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const usersList = " {{ route('users.index') }}";
            const getUser = " {{ route('users.show', ':id') }}";
            const saveOrUpdate = " {{ route('users.save') }}";

            // Variable útil para tu script JS
            //const isEdit =  $isEdit ? 'true' : 'false' }};
            //const method = " $isEdit ? 'PUT' : 'POST' }}";
        </script>
        <script src="{{asset("/js/custom/users.js")}}"></script>
    @endpush
</x-app-layout>
