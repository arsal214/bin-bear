<x-app-layout title="Zip Code">

    <x-breadcrumb title="Zip Code Management">
{{--        @can('plans-create')--}}
        <a href="{{ route('zip-codes.create') }}" class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill">
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
                        <h5 class="mb-0">Zip Code List</h5>
                    </div>
                    <x-datatable :url="route('zip-codes.list')" :index="[
                        'DT_RowIndex',
                        'city_name',
                        'zip_code',
                        'status',
                        'action',
                    ]">
                        <th>No</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
