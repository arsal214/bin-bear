@props(['name'])

<div {!! $attributes->merge(['class' => 'tab-pane fade']) !!} id="{{ $name }}-tab" role="tabpanel">
    {{ $slot }}
</div>
