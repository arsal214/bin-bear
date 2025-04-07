<x-app-layout title="Coupons">

    <x-breadcrumb title="Coupons Management">
{{--        @can('plans-create')--}}
        <a href="{{ route('coupons.create') }}" class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill">
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
                        <h5 class="mb-0">Coupons List</h5>
                    </div>
                    <x-datatable :url="route('coupons.list')" :index="[
                        'DT_RowIndex',
                        'name',
                        'discount_value',
                        'discount_type',
                        'status',
                        'action',
                    ]">
                        <th>No</th>
                        <th>Name</th>
                        <th>Discount Value</th>
                        <th>Discount Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
