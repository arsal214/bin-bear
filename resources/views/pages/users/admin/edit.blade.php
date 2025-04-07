<x-app-layout title="Update Staff">

    <x-breadcrumb title="Update Staff" :back-button="route('users.staff.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Staff</h5>
                    </div>
                    <div class="card-body">

                        <x-form :route="route('users.staff.update', $staff->id)">
                            {{ method_field('PATCH') }}
                            @include('pages.users.admin.form')
                        </x-form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>

{{-- @section('script')
    <script>
        var id = {{ $user->id }};
        $(function() {
            var _token = $("input[name='_token']").val();
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
                success: function(label) {
                    label.addClass('validation-valid-label').text('Success.');
                },
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
                rules: {
                    password: {
                        required: false,
                        minlength: 8,
                        maxlength: 15
                    },
                    confirm_password: {
                        required: false,
                        equalTo: "#password"
                    },
                    email: {
                        "remote": {
                            url: "{{ route('users.checkEmail') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                id: id,
                                email: function() {
                                    return $("input[name='email']").val();
                                }
                            },
                        }
                    }
                },
                messages: {
                    email: {
                        required: "Please enter a valid email address.",
                        remote: jQuery.validator.format("{0} is already exist.")
                    }
                }
            });
        });
    </script>
@endsection --}}
