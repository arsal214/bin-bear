<x-form :route="route('settings.store')">
    <input type="hidden" name="type" value="admin">
    <div class="row g-2">
        <x-input col="6" title="Full Truck Price" name="values[full_truck_price]" :value="adminSettings('full_truck_price')" />

        <x-input col="6" title="Half Truck Price" name="values[half_truck_price]" :value="adminSettings('half_truck_price')" />

        <x-input col="6" title="Apartment Trash Valet Per Unit" name="values[apartment_trash_valet_per_unit]" :value="adminSettings('apartment_trash_valet_per_unit')" />

        <x-input col="6" title="Drumpster Size Small Price" name="values[drumpster_size_small_price]" type="number" :value="adminSettings('drumpster_size_small_price')" />

        <x-input col="6" title="Drumpster Size Medium Price" name="values[drumpster_size_medium_price]" type="number" :value="adminSettings('drumpster_size_medium_price')" />

        <x-input col="6" title="Drumpster Size Large Price" name="values[drumpster_size_large_price]" type="number" :value="adminSettings('drumpster_size_large_price')" />


    </div>
</x-form>
