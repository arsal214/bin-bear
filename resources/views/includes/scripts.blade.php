<!-- Core JS files -->
{{-- <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script> --}}
<script src="{{ asset('backend/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{ asset('backend/assets/js/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ asset('backend/assets/js/app.js') }}"></script>
<!-- /theme JS files -->
<script src="{{ asset('backend/assets/js/vendor/notifications/noty.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>


<script>
    Noty.overrideDefaults({
        theme: 'limitless',
        layout: 'topRight',
        type: 'alert',
        closeWith: ['button'],
        timeout: 2500
    });
    @if (Session::has('success'))
        new Noty({
            text: "{{ Session::get('success') }}",
            type: 'success'
        }).show();
    @endif
    @if (Session::has('warning'))
        new Noty({
            text: "{{ Session::get('warning') }}",
            type: 'warning'
        }).show();
    @endif
    @if (Session::has('info'))
        new Noty({
            text: "{{ Session::get('info') }}",
            type: 'info'
        }).show();
    @endif
    @if (Session::has('error'))
        new Noty({
            text: "{{ Session::get('error') }}",
            type: 'error'
        }).show();
    @endif
    @if ($errors->any())
        new Noty({
            text: "{{ $errors->first() }}",
            type: 'error'
        }).show();
    @endif
    // var NotyDemo = function() {
    //     const _componentNoty = function() {
    //         if (typeof Noty == 'undefined') {
    //             console.warn('Warning - noty.min.js is not loaded.');
    //             return;
    //         }
    //         Noty.overrideDefaults({
    //             theme: 'limitless',
    //             layout: 'topRight',
    //             type: 'alert',
    //             closeWith: ['button'],
    //             timeout: 2500
    //         });
    //         @if (Session::has('success'))
    //             new Noty({
    //                 text: "{{ Session::get('success') }}",
    //                 type: 'success'
    //             }).show();
    //         @endif
    //         @if (Session::has('warning'))
    //             new Noty({
    //                 text: "{{ Session::get('warning') }}",
    //                 type: 'warning'
    //             }).show();
    //         @endif
    //         @if (Session::has('info'))
    //             new Noty({
    //                 text: "{{ Session::get('info') }}",
    //                 type: 'info'
    //             }).show();
    //         @endif
    //         @if (Session::has('error'))
    //             new Noty({
    //                 text: "{{ Session::get('error') }}",
    //                 type: 'error'
    //             }).show();
    //         @endif
    //         @if ($errors->any())
    //             new Noty({
    //                 text: "{{ $errors->first() }}",
    //                 type: 'error'
    //             }).show();
    //         @endif
    //     }
    //     return {
    //         init: function() {
    //             _componentNoty();
    //         }
    //     }
    // }();
    // document.addEventListener('DOMContentLoaded', function() {
    //     NotyDemo.init();
    // });
</script>


<script>
    $(function() {
        // $('#loader').show();
        // $('#backdrop').show();

        // $('body').append(
        //     '<div class="modal-backdrop ajax-backdrop fade show d-flex justify-content-center align-items-center"><p class="m-0 text-muted fs-3"> Loading... </p> </div>'
        //     );
        $(document).ajaxStart(function() {
            $('#backdrop').show();
            // $('body').append('<div class="modal-backdrop ajax-backdrop fade show"></div>');
        }).ajaxStop(function() {
            // $('.ajax-backdrop').remove();
            $('#backdrop').hide();
        });

        const swalInit = swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light',
                denyButton: 'btn btn-light',
                input: 'form-control'
            }
        });
        $(document).on('click', ".sa-confirm", function(event) {
            event.preventDefault();
            swalInit.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                }
            }).then((result) => {
                if (result.value === true) $(this).closest("form").submit();
            });
        });
    });
</script>
