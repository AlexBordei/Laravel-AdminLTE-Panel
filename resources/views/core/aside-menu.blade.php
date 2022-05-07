@foreach($menus as $menu)
    @if($menu->title === 'Sidebar menu')
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach($menu->options as $parentOption)
                    <li class="nav-item{{ $parentOption->id === $activeOptions['parent_id'] ? ' menu-open' : '' }}">
                        <a href="{{ $parentOption->url }}" class="nav-link{{ $parentOption->id === $activeOptions['parent_id'] ? ' active' : '' }}">
                            <i class="nav-icon fas {{ $parentOption->icon }}"></i>
                            <p>
                                {{ $parentOption->title }}
                                @if(! $parentOption->options->isEmpty())
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </p>
                        </a>
                        @isset($parentOption->options)
                            <ul class="nav nav-treeview">
                            @foreach($parentOption->options as $subOption)
                                <li class="nav-item">
                                    <a href="{{ $subOption->url }}" class="nav-link{{ $subOption->id === $activeOptions['child_id']? ' active' : '' }}">
                                        <i class="fa {{ $subOption->icon }} nav-icon"></i>
                                        <p>{{ $subOption->title }}</p>
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        @endisset
                    </li>
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    @endif
@endforeach

