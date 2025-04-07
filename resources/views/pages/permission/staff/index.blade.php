<x-app-layout title="Staff Permissions">

    <x-breadcrumb title="Staff Permission Management">

        <button class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill" data-bs-toggle="modal"
            data-bs-target="#modal_default">
            <span class="btn-labeled-icon bg-primary text-white rounded-pill">
                <i class="ph-plus"></i>
            </span>
            Create New
        </button>
    </x-breadcrumb>

    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Permissions List</h5>
                    </div>
                    <x-datatable :url="route('permissions.staff.index')" :index="['DT_RowIndex', 'display_name', 'name', 'action']">
                        <th>No</th>
                        <th>Display Name</th>
                        <th>Permission Name</th>
                        <th>Actions</th>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


    @include('pages.permission.staff.create')

    <div id="result"></div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.edit-btn', function() {
                    let id = $(this).data('id');
                    let url = "{{ route('permissions.staff.index') }}";
                    // Perform Ajax request
                    $.ajax({
                        type: 'GET',
                        url: `${url}/${id}/edit`,
                        success: function(data, status) {
                            $('#result').html(data);
                            $('#modal_update').modal('show');
                            // Handle the response
                        },
                        error: function(error) {
                            console.log(error);
                            // Handle errors
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
