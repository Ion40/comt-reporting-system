<ul class="side-nav">
    @foreach($menuItems as $id => $item)
        <li class="side-nav-item">
            @if(count($item['submodules']) > 0)
                {{-- Menú Colapsable --}}
                <a data-bs-toggle="collapse" href="#menu-{{ $id }}" class="side-nav-link">
                    <span class="menu-icon"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="menu-text">{{ $item['name'] }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="menu-{{ $id }}">
                    <ul class="sub-menu">
                        @foreach($item['submodules'] as $sub)
                            <li class="side-nav-item">
                                <a href="javascript:void(0);"
                                   wire:click.prevent="navegar('{{ $sub->url_path }}', {{ !is_null($sub->has_iframe) ? 'true' : 'false' }})"
                                   class="side-nav-link">
                                    <span class="">
                                        <i class="ti ti-corner-down-right text-muted fs-15"></i>
                                    </span>
                                    <span class="menu-text">{{ $sub->module_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                {{-- Módulo Directo --}}
                <a href="javascript:void(0);"
                   wire:click.prevent="navegar('{{ $item['url'] }}', {{ $item['has_iframe'] ? 'true' : 'false' }})"
                   class="side-nav-link">
                    <span class="menu-icon"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="menu-text">{{ $item['name'] }}</span>
                </a>
            @endif
        </li>
    @endforeach
</ul>
