<div>
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
                        <div class="d-flex align-items-center gap-2 ">
                            <div class="avatar-xs">
                                <div class="h-100 w-100 rounded bg-light p-2 d-flex align-items-center justify-content-center border">
                                    <i class="{{ $parent->icon_class }} fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fs-5"> {{ $parent->name }}</h6>
                            </div>
                        </div>
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
                                    wire:target="checkAllRow({{ $child->id }})"
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
