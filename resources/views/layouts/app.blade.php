@props(['title' => 'Dashaboard'])

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Bin Bear </title>

    <!-- Global stylesheets -->
    <link href="{{ asset('backend/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/icons/phosphor/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">

    <!-- /global stylesheets -->
    <style>
        /* Loader styling */
        .loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Loader animation styling */
        .loader .ph-spinner {
            font-size: 3rem;
            animation: spin 2s infinite linear;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Backdrop styling */
        .backdrop {
            display: none;
            /* Initially hide the backdrop */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
            z-index: 99999;
            /* Ensure backdrop is under the loader */
        }
    </style>
    @stack('style')
</head>

<body>



    <!-- Main navbar -->
    @include('includes.navbar')
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">
        <!-- Main sidebar -->
        @include('includes.sidebar')
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                {{ $slot }}

                <!-- Footer -->
                @include('includes.footer')
                <!-- /footer -->
            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <div class="backdrop" id="backdrop">
        <div class="loader" id="loader">
            <i class="ph-spinner"></i>
            <p>
                Loading...
            </p>
        </div>
    </div>

    @include('includes.scripts')

    @stack('scripts')

</body>

</html>
