<x-app-layout title="Staff Roles">

    @can('roles-create')
    <x-breadcrumb title="Staff Roles Management" >
        <a href="{{ route('roles.staff.create') }}"
            class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill">
            <span class="btn-labeled-icon bg-primary text-white rounded-pill">
                <i class="ph-plus"></i>
            </span>
            Create New
        </a>
    </x-breadcrumb>
    @endcan

    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Roles List</h5>
                    </div>
                    <x-datatable :url="route('roles.staff.list')" :index="['DT_RowIndex', 'name', 'guard_name', 'action']">
                        <th>No</th>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
