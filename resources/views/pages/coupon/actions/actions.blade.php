{{--@canany(['plans-edit','plans-delete'])--}}
    <x-actions
        :editRoute="route('coupons.edit', $row->id)"
        :deleteRoute="route('coupons.destroy', $row->id)"
    />
{{--@endcanany--}}
