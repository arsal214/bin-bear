<x-form :route="route('settings.store')">
    <input type="hidden" name="type" value="admin">
    <div class="row g-2">

        <x-input col="6" title="Drumpster Size Small Price" name="values[drumpster_size_small_price]" type="number" :value="adminSettings('drumpster_size_small_price')" />

        <x-input col="6" title="Drumpster Size Medium Price" name="values[drumpster_size_medium_price]" type="number" :value="adminSettings('drumpster_size_medium_price')" />

        <x-input col="6" title="Drumpster Size Large Price" name="values[drumpster_size_large_price]" type="number" :value="adminSettings('drumpster_size_large_price')" />


    </div>
</x-form>
