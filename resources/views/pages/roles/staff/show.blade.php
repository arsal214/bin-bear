<x-app-layout title="Staff Permissions">

    <x-breadcrumb title="Staff Permission Management" :back-button="route('roles.staff.index')" />

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Show') }} Role</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <strong>Name:</strong>
                            {{ $role->name }}
                        </div>
{{--                        <div class="form-group mb-3">--}}
{{--                            <strong>Guard Name:</strong>--}}
{{--                            {{ $role->guard_name }}--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <h5 class="m-0">Permissions:</h5>
                            <div class="row">
                                @foreach ($permissionGroup as $key => $permissions)
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <p class="fw-semibold">{{ ucfirst($key) }}</p>
                                            <div class="border px-3 pt-3 pb-2 rounded">
                                                <div class="row">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-6">
                                                            <label class="form-check mb-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input form-check-input-secondary"
                                                                    disabled
                                                                    @if (isset($permission['exist'])) {{ $permission['exist'] }} @endif>
                                                                <span
                                                                    class="form-check-label">{{ ucfirst($permission['name']) }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
