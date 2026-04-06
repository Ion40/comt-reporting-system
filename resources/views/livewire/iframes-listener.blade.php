@php use Carbon\Carbon; @endphp
<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between d-sm-flex gap-2">
                    <a href="{{ route("iframes.create")  }}" class="btn btn-primary btn-sm mb-sm-0 mb-2">
                        <i class="ti ti-circle-plus fs-20 me-2"></i> Agregar nuevo IFrame
                    </a>

                    <form class="row g-2 align-items-center">
                        <div class="col-auto" wire:ignore>
                            <div class="d-flex align-items-center">
                                <div class="input-group">
                                    <span class="input-group-text "><i class="ti ti-hierarchy-3 fs-4"></i></span>
                                    <div class="form-floating">
                                        <select id="select-modulo" class="form-select form-select-sm" wire:model.live="modulo">
                                            <option value="all">Todos los grupos</option>
                                            @foreach($parentModulesList as $padre)
                                                <option value="{{ $padre->id }}">{{ $padre->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="select-modulo" class="fw-semibold me-2 mb-0">Grupo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-auto" wire:ignore>
                            <div class="d-flex align-items-center">
                                <div class="input-group">
                                    <span class="input-group-text "><i class="ti ti-menu-order fs-4"></i></span>
                                    <div class="form-floating">
                                        <select id="select-orden" class="form-select form-select-sm" wire:model.live="orden">
                                            <option value="latest">Más recientes</option>
                                            <option value="name">Nombre (A-Z)</option>
                                        </select>
                                        <label for="select-orden" class="fw-semibold me-2 mb-0">Ordenar</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-auto">
                            <div class="position-relative">
                                <div class="input-group">
                                    <span class="input-group-text border-b-gray-900 bg-light bg-opacity-50"><i class="ti ti-search fs-5"></i></span>
                                    <div class="form-floating">
                                        <input type="search"
                                               id="txtSearch"
                                               class="form-control border-b-gray-900 bg-light bg-opacity-50 border-start-0"
                                               placeholder="{{ $modulo !== 'all' ? 'Buscar en este grupo...' : 'Búsqueda general...' }}"
                                               wire:model.live.debounce.300ms="search">

                                        <div wire:loading wire:target="modulo, search" class="position-absolute end-0 top-50 translate-middle-y me-2">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        </div>
                                        <label for="txtSearch">{{ $modulo !== 'all' ? 'Buscar en este grupo...' : 'Búsqueda general...' }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="iframe-main-scroll" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
        @forelse($groupedData as $parentName => $pagination)
            @php
                $groupIcon = $pagination->first()->icon_class ?? 'ti ti-folder';
                $groupId = Str::slug($parentName);
            @endphp

            <div class="card mb-4 border-0 shadow-sm" wire:key="group-{{ $groupId }}">
                {{-- Header clickable para colapsar --}}
                <div class="card-header bg-white border-bottom-0 py-3"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#collapse-{{ $groupId }}"
                     aria-expanded="true"
                     aria-controls="collapse-{{ $groupId }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <i class="{{ $groupIcon }} fs-20"></i>
                                </span>
                            </div>
                            <h4 class="header-title mb-0 text-uppercase fs-5 fw-bold">
                                {{ $parentName }}
                                <span class="badge badge-soft-secondary fs-11 ms-2">
                                    {{ $pagination->total() }} totales
                                </span>
                            </h4>
                        </div>
                        {{-- Icono indicador de colapso --}}
                        <i class="ti ti-chevron-down fs-4 transition-all"></i>
                    </div>
                </div>

                {{-- Contenedor colapsable --}}
                <div id="collapse-{{ $groupId }}" class="collapse show">
                    <div class="card-body bg-light-subtle">
                        <div class="row g-3">
                            @foreach($pagination as $iframe)
                                <div class="col-xl-4 col-md-6 mb-3" wire:key="iframe-{{ $iframe->id }}">
                                    <div class="card h-100 shadow-sm border border-dashed {{ $iframe->is_active ? 'border-primary' : 'border-danger' }} mb-0 transition-hover">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="mt-0 mb-1 fw-bold text-truncate" title="{{ $iframe->title }}">{{ $iframe->title }}</h5>
                                                <div class="text-end">
                                                    <span class="badge {{ $iframe->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} p-1">
                                                        {{ $iframe->is_active ? 'Reporte activo' : 'Reporte inactivo' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <p class="text-primary fw-semibold fs-12 mb-0">/{{ $iframe->url_path }}</p>

                                                <div class="text-end">
                                                    <p class="text-muted fw-semibold fs-12 mb-0">
                                                        <i class="ti ti-calendar-month me-1 fs-5 text-secondary"></i>
                                                        {{ \Carbon\Carbon::parse($iframe->created_at)->locale('es')->translatedFormat('d M, Y h:i A') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <span  class="badge badge-soft-secondary text-secondary fs-12 mb-2">
                                                <i class="ti ti-hierarchy-3"></i> {{ $iframe->parent_name }}
                                            </span>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                <div class="btn-group">
                                                    <a href="{{ route('iframes.edit', $iframe->url_path) }}" class="btn btn-light border"><i class="ti ti-edit text-warning"></i></a>
                                                    <button onclick="deleteIframeFn('{{ $iframe->url_path }}')" class="btn btn-light border"><i class="ti ti-trash text-danger"></i></button>
                                                </div>
                                                <a href="report-viewer/{{ $iframe->url_path }}" class="btn btn-sm btn-outline-primary px-3">
                                                    <i class="ti ti-report-search fs-5 me-1"></i>
                                                    Abrir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3" wire:key="pagination-{{ $groupId }}">
                        <div class="text-muted fs-12">
                            Página <strong>{{ $pagination->currentPage() }}</strong> de {{ $pagination->lastPage() }}
                            <span class="mx-2">|</span>
                            Total: {{ $pagination->total() }} reportes
                        </div>

                        <div class="btn-group shadow-sm">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary px-3"
                                wire:click="previousPage('{{ $pagination->getPageName() }}')"
                                wire:loading.attr="disabled"
                                @if($pagination->onFirstPage()) disabled @endif
                            >
                                <i class="ti ti-chevron-left me-1"></i> Anterior
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary px-3"
                                wire:click="nextPage('{{ $pagination->getPageName() }}')"
                                wire:loading.attr="disabled"
                                @if(!$pagination->hasMorePages()) disabled @endif
                            >
                                Siguiente <i class="ti ti-chevron-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="ti ti-search fs-1 text-muted"></i>
                <p class="text-muted">No se encontraron reportes configurados.</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            initSelect2(@this);
        });

        document.addEventListener('livewire:load', function () {
            Livewire.hook('morph.updated', (el, component) => {
                initSelect2(@this);
            });
        });
    </script>
@endpush
