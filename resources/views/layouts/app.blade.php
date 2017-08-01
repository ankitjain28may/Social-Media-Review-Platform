<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Social Media Platform</title>

    <!-- Styles -->

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('startbootstrap-sb-admin-2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('startbootstrap-sb-admin-2/vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('startbootstrap-sb-admin-2/dist/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('startbootstrap-sb-admin-2/vendor/bootstrap-social/bootstrap-social.css') }}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{ asset('startbootstrap-sb-admin-2/vendor/morrisjs/morris.css') }}" rel="stylesheet">

    <!-- DatePicker -->
    <link href="{{ asset('datepicker/css/datepicker.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('startbootstrap-sb-admin-2/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">


    <style type="text/css">
        #page-wrapper {
            padding-top: 30px;
        }
        .center {
            text-align: center;
        }
        a:hover {
            text-decoration: none;
        }
        .datepicker {
            z-index: 1051;
        }
        @yield('style')
    </style>

</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include('layouts.header')
            @include('layouts.sidebar')
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->

    <!-- jQuery -->
    <script src="{{ asset('startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('startbootstrap-sb-admin-2/vendor/metisMenu/metisMenu.min.js') }}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('startbootstrap-sb-admin-2/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('startbootstrap-sb-admin-2/vendor/morrisjs/morris.min.js') }}"></script>
    <script src="{{ asset('startbootstrap-sb-admin-2/data/morris-data.js') }}"></script>

    <!-- Datepicker -->
    <script src="{{ asset('datepicker/js/datepicker.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('startbootstrap-sb-admin-2/dist/js/sb-admin-2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayBtn: true,
                todayHighlight: true
            });
        });
    </script>

    @yield('script')
</body>
</html>
