{{--@canany(['plans-edit','plans-delete'])--}}
    <x-actions
        :showRoute="route('bookings.show', $row->id)"
    />
{{--@endcanany--}}
