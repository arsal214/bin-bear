<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>  Bin Bear </title>

    <!-- Global stylesheets -->
    <link href="{{ asset('backend/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/icons/phosphor/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('backend/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <script src="{{ asset('backend/assets/js/vendor/notifications/noty.min.js') }}"></script>


    <script>
        var NotyDemo = function() {
            const _componentNoty = function() {
                if (typeof Noty == 'undefined') {
                    console.warn('Warning - noty.min.js is not loaded.');
                    return;
                }
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
            }
            return {
                init: function() {
                    _componentNoty();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function() {
            NotyDemo.init();
        });
    </script>

    @stack('scripts')
    <!-- /theme JS files -->



</head>

<body class="bg-dark">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                {{ $slot }}

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</body>

</html>
