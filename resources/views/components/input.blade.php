@props([
    'col' => 12,
    'id'=> null,
    'name'=> null,
    'type' => 'text',
    'value' => null,
    'title' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'readOnly' => false,
    'options' => [],
    'defaultFile' => null,
    'dropifyHeight' => '200',
])

@php
    $title = $title ?? ucfirst(str_replace('_', ' ', $name));
    $placeholder = $placeholder ?? $title;

@endphp

<div class="form-group col-md-{{ $col }}">
    <label for="{{ $name }}" class="text-capitalize">{{ $title }}</label>

    @if ($type == 'select')

        <select
            id="{{ $id ?? '' }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $multiple ? 'multiple' : '' }}
            {!! $attributes->merge(['class' => 'form-control']) !!}>
                <option value="">--select option--</option>
                {!! $slot  !!}
        </select>

    @elseif ($type == 'dropify')

        <input
            type="file"
            name="{{ $name }}"
            {!! $attributes->merge(['class' => 'form-control dropify ']) !!}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            accept="image/png,image/jpg,image/jpeg"
            data-default-file="{{ $defaultFile }}"
            data-height="{{ $dropifyHeight }}">

    @elseif ($type == 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            {!! $attributes->merge(['class' => 'form-control']) !!}>{!!$value!!}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readOnly ? 'readonly' : '' }}
            {!! $attributes->merge(['class' => 'form-control']) !!}>
    @endif

</div>



