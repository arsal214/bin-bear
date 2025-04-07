<x-form :route="route('pages.blogs.change', $row->id)" :button="false">
    {{ method_field('PATCH') }}
    <input type="hidden" name="field" value="status">
    <div class="form-check form-check-inline form-switch">
        <input type="checkbox" class="form-check-input" name="status" {{ $row->status == 'Active' ? 'checked' : '' }}
            onChange="event.preventDefault(); this.closest('form').submit();">
    </div>
</x-form>
