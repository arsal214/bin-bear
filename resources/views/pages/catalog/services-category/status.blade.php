<x-form :route="route('catalog.category.change', $row->id)" :button="false">
    {{ method_field('PATCH') }}
    <input type="hidden" name="field" value="status">
    <div class="form-check form-check-inline form-switch">
        <input type="checkbox" class="form-check-input" name="status" {{ $row->status == '1' ? 'checked' : '' }}
            onChange="event.preventDefault(); this.closest('form').submit();">
    </div>
</x-form>
