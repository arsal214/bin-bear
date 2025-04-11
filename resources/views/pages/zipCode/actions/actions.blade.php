{{--@canany(['plans-edit','plans-delete'])--}}
    <x-actions
        :editRoute="route('zip-codes.edit', $row->id)"
        :deleteRoute="route('zip-codes.destroy', $row->id)"
    />
{{--@endcanany--}}
