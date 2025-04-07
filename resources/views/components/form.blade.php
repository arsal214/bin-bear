@props(['route', 'button' => true, 'rules' => [], 'method' => 'POST'])

<form method="{{ $method }}" action="{{ $route }}" class="validate" role="form"
    enctype="multipart/form-data">
    @csrf

    {{ $slot }}

    @if ($button)
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end align-items-center mt-3">
                <button type="submit" class="btn btn-primary ms-3">
                    Submit <i class="ph-paper-plane-tilt ms-2"></i>
                </button>
            </div>
        </div>
    @endif
</form>

@push('style')
    <link href="{{ asset('backend/assets/js/vendor/dropify/css/dropify.css') }}" rel="stylesheet" type="text/css">
@endpush


@push('scripts')
    <script src="{{ asset('backend/assets/js/vendor/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendor/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendor/forms/validation/validate.min.js') }}"></script>
    <script>
        $(function() {

            $('.validate')[0].reset();

            $('.select').select2({
                placeholder: '-- Select --',
                // dropdownParent: $this.parent()
            });

            $('.dropify').dropify({
                messages: {
                    'default': 'Upload File',
                    'replace': 'Upload File',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong.'
                }
            });
        })
    </script>

    <script>
        $(function() {

            jQuery.validator.addMethod("noSpace", function(value, element) {
                return value.indexOf(" ") < 0 && value != "";
            }, "Space are not allowed");

            $('.validate').validate({
                errorClass: 'validation-invalid-label',
                successClass: 'validation-valid-label',
                validClass: 'validation-valid-label',
                highlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                    $(element).addClass('is-invalid');
                    $(element).removeClass('is-valid');
                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
                // success: function(label) {
                //     label.addClass('validation-valid-label').text('Success.');
                // },
                errorPlacement: function(error, element) {
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.appendTo(element.parent());
                    } else if (element.parents().hasClass('form-control-feedback') || element.parents()
                        .hasClass('form-check') || element.parents().hasClass('input-group')) {
                        error.appendTo(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: @json($rules)

            });
        });
    </script>
@endpush
