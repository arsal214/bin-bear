<x-form :route="route('settings.store')">
    <input type="hidden" name="type" value="admin">
    <div class="row g-2">
        <x-input col="6" title="Full Truck Price" name="values[full_truck_price]" :value="adminSettings('full_truck_price')" />

        <x-input col="6" title="Half Truck Price" name="values[half_truck_price]" :value="adminSettings('half_truck_price')" />



    </div>
</x-form>
