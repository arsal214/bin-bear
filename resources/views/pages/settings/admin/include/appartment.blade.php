<x-form :route="route('settings.store')">
    <input type="hidden" name="type" value="admin">
    <div class="row g-2">

        <x-input col="6" title="Apartment Trash Valet Per Unit" name="values[apartment_trash_valet_per_unit]" :value="adminSettings('apartment_trash_valet_per_unit')" />

    

    </div>
</x-form>
