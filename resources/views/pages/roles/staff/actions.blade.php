@canany(['roles-edit','roles-delete'])
    <x-actions
{{--        :showRoute="route('roles.staff.show', $id)" --}}
        :editRoute="route('roles.staff.edit', $id)" canEdit="roles-edit"
        :deleteRoute="route('roles.staff.destroy', $id)" canDelete="roles-delete"
    />
@endcanany
