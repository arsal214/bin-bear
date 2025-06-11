<x-app-layout title="Bookings">

    <x-breadcrumb title="Bookings Management">
    </x-breadcrumb>

    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Bookings List</h5>
                    </div>
                    <x-datatable :url="route('bookings.list')" :index="[
                        'DT_RowIndex',
                        'service_name',
                        'name',
                        'phone_number',
                        'date',
                        'action',
                    ]">
                        <th>No</th>
                        <th>Service Name</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
