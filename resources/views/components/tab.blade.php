@props([
    'links' => [],
    'align' => 'vertical',
])

@php
    $class = $align == 'horizantol'
        ? 'nav-tabs-highlight nav-justified'
        : 'nav-tabs-vertical nav-tabs-vertical-start wmin-lg-200 me-lg-3 mb-3 mb-lg-0';
@endphp

<div class="{{ $align == 'horizantol' ?: 'd-lg-flex' }}">
    <ul class="nav nav-tabs {{ $class }}" role="tablist">

        @foreach ($links as $link)
            <li class="nav-item" role="presentation">
                <a href="#{{ str_replace(' ', '-', $link) }}-tab"
                    class="nav-link {{ $loop->first ? 'active' : '' }} text-capitalize" data-bs-toggle="tab"
                    aria-selected="true" role="tab">
                    {{ $link }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content flex-lg-fill pt-3">
        {{ $slot }}
    </div>
</div>
