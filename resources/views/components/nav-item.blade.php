@props(['route', 'active', 'icon' => '', 'title', 'submenu' => false])
@php
    $active = $active ?? $route;
@endphp
<li
    class="nav-item {{ $submenu ? 'nav-item-submenu' : '' }} {{ Route::is($active) ? 'nav-item-expanded nav-item-open' : '' }}">
    <a href="{{ !$submenu && $route ? route($route) : '#' }}" class="nav-link {{ Route::is($active) ? 'active' : '' }}">

        @if ($icon)
            <i class="ph-{{ $icon }}"></i>
        @endif
        <span>{{ $title }}</span>
    </a>

    @if ($submenu)
        <ul class="nav-group-sub collapse {{ Route::is($active) ? 'show' : '' }}">
            {{ $slot }}
        </ul>
    @endif
</li>
