<div>
    <div class="alert alert-info border-0 bg-info-subtle mb-4">
        <div class="d-flex">
            <i class="ti ti-shield-check fs-2 text-info me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold">Gestión de Accesos</h5>
                <p class="mb-0">Utilice este módulo para asignar **permisos de acceso** a las diferentes áreas del
                    sistema
                    y definir **funcionalidades específicas** (crear, editar, eliminar) para cada nivel de usuario.</p>
            </div>
        </div>
    </div>

    <div class="row mb-4 align-items-end">
        <div class="col-md-5">
            <label for="user_select" class="form-label fw-bold text-muted">
                <i class="ti ti-users me-1"></i> Seleccionar Usuario para Asignar Permisos
            </label>
            <div class="input-group">
                <select id="user_select" class="form-select select2" data-toggle="select2">
                    <option value="" selected disabled>-- Seleccione un usuario para empezar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-7 text-end">
            <button class="btn btn-sm btn-success">
                <i class="ti ti-device-floppy me-1"></i>
                Guardar Cambios
            </button>
        </div>
    </div>

    <hr class="border-dashed my-4">


    <div class="table-responsive"
         wire:target="checkAllRow, selectedPermission"
    >
        <table class="table align-middle border">
            <thead class="table-dark">
            <tr>
                <th>
                    Módulo / Funcionalidad
                </th>
                @foreach($permisos as $p)
                    <th class="text-center">{{ $p->name }}</th>
                @endforeach
                <th class="text-center">Acción</th>
            </tr>
            </thead>
            <tbody>
            @foreach($config->where('parent_id', null) as $parent)
                <tr class="table-light">
                    <td colspan="{{ count($permisos) + 2 }}">
                        <strong><i class="{{ $parent->icon_class }} me-2 fs-4"></i> {{ $parent->name }}</strong>
                    </td>
                </tr>
                @foreach($config->where('parent_id', $parent->id) as $child)
                    <tr>
                        <td class="ps-4">
                            <i class="ti ti-corner-down-right text-muted me-2"></i> {{ $child->name }}
                        </td>
                        @foreach($permisos as $p)
                            @php $currentKey = $child->id . '_' . $p->id; @endphp
                            <td class="text-center" wire:key="cell-{{ $currentKey }}">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           value="{{ $currentKey }}"
                                           wire:model.live="selectedPermissions">
                                </div>
                            </td>
                        @endforeach
                        <td class="text-center">
                            <button type="button"
                                    wire:click="checkAllRow({{ $child->id }})"
                                    wire:loading.attr="disabled" {{-- Deshabilita el botón mientras carga --}}
                                    class="btn btn-sm btn-outline-secondary py-0 px-2"
                                    title="Marcar todos los permisos de esta fila">

                                {{-- Este icono se oculta cuando carga este ID específico --}}
                                <i wire:loading.remove wire:target="checkAllRow({{ $child->id }})"
                                   class="ti ti-checks fs-5"></i>

                                {{-- Este spinner solo aparece cuando carga este ID específico --}}
                                <span wire:loading wire:target="checkAllRow({{ $child->id }})"
                                      class="spinner-border spinner-border-sm text-secondary" role="status">
                                </span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>
