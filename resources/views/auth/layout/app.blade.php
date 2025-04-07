<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('page_title')</title>
        <meta name="description" content="" />
        
        <!-- Global stylesheets -->
        <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">

        <!-- Core JS files -->
        <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <!-- Theme JS files -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script src="{{ asset('assets/demo/pages/form_validation_styles.js') }}"></script>
    </head>
    <body class="bg-dark">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="content-inner">
                    <div class="content d-flex justify-content-center align-items-center">
                        @yield('page_content')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>