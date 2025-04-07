<div id="modal_default" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <x-form :route="route('permissions.staff.store')" :button="false" :rules="['name' => ['noSpace' => true]]">
                <div class="modal-body">
                    <div class="row g-2">
                        <x-input title="Permission Name" name="name" :value="$permission->permission_name ?? ''" :required="true" />
                        <x-input name="display_name" :value="$permission->display_name ?? ''" :required="true" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </x-form>
        </div>
    </div>
</div>
