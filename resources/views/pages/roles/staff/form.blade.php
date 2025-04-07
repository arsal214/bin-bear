<div class="row g-2">

    <x-input name="name" :value="$role->name ?? null" />
    <div class="col-md-12 ">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <tbody>
                    <tr>
                        <th class="table-light">Permssions</th>
                        <td colspan="1000">
                            <label class="form-check d-inline-block">
                                <input type="checkbox" id="select-all"
                                    class="form-check-input form-check-input-secondary">
                                <span class="form-check-label">Select All</span>
                            </label>
                        </td>
                    </tr>
                    @foreach (collect($permissionGroup)->sortKeys() as $key => $permissions)
                        <tr>
                            <th class="table-light">{{ ucfirst($key) }}</th>
                            @foreach ($permissions as $permission)
                                <td colspan="{{ $loop->last ? '1000' : '' }}">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input form-check-input-secondary"
                                            name="permission[]" value="{{ $permission['id'] }}"
                                            @if (isset($permission['exist'])) {{ $permission['exist'] }} @endif>
                                        <span class="form-check-label">{{ ucfirst($permission['name']) }}</span>
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#select-all').on('change', function() {
                $('.form-check-input-secondary').prop('checked', this.checked);
            });

            $('.form-check-input-secondary').on('change', function() {
                if ($('.form-check-input-secondary:checked').length === $('.form-check-input-secondary')
                    .length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });
        });
    </script>
@endpush
