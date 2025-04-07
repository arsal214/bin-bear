@props(['index', 'url', 'class' => null])

<table class="table {{ $class ?? 'datatable-basic' }}">
    <thead class="thead">
        <tr>
            {{ $slot }}
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>


@push('scripts')
    <script src="{{ asset('backend/assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            let indexes = {{ \Illuminate\Support\Js::from($index) }}
            let $columns = [];
            indexes.forEach((value, key) => {
                $columns[key] = {
                    'data': value,
                    'name': value
                };
            });

            $.extend($.fn.dataTable.defaults, {
                paging: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                orderClasses: false,
                autoWidth: false,
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                        'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                    }
                }
            });

            var table = $('.datatable-basic').DataTable({
                ajax: "{{ $url }}",
                columns: $columns,
                columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: 0,

                    },
                    {
                        orderable: false,
                        searchable: false,
                        targets: -1,

                    },
                ],
            });

        });
    </script>
@endpush
