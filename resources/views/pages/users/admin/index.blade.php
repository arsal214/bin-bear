<x-app-layout title="Staff">

    <x-breadcrumb title="Staff" >
{{--        @can('users-create')--}}
        <a href="{{ route('users.staff.create') }}"
            class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill">
            <span class="btn-labeled-icon bg-primary text-white rounded-pill">
                <i class="ph-plus"></i>
            </span>
            Create New
        </a>
{{--        @endcan--}}
    </x-breadcrumb>

    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Staff List</h5>
                    </div>
                    <x-datatable :url="route('users.staff.index')" :index="['DT_RowIndex', 'image', 'name', 'email', 'roles', 'action']">
                        <th>No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
