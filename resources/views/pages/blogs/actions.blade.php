@canany(['blogs-edit','blogs-delete'])
<x-actions
    :editRoute="route('pages.blogs.edit', $row->id)" canEdit="blogs-edit"
    :deleteRoute="route('pages.blogs.destroy', $row->id)" canDelete="blogs-delete"
>
</x-actions>
@endcanany
