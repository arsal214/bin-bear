{{-- @canany(['category-edit','category-delete']) --}}
    <x-actions
        :editRoute="route('catalog.category.edit', $row->id)" 
        :deleteRoute="route('catalog.category.destroy', $row->id)" 
    >
    </x-actions>
{{-- @endcanany --}}

