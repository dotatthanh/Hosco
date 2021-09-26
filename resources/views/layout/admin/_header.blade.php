<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>

    <meta name="description" content="e-commerce"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="_token" content="{{ csrf_token()}}"/>
    <link rel="shortcut icon" href="">

    <link rel="stylesheet" href="{{asset('admin-assets/css/admin-style.css')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @yield('extra_css')
    <link href="{{asset('admin-assets/dist/font/font-fileuploader.css')}}" rel="stylesheet">
    <link href="{{asset('admin-assets/dist/jquery.fileuploader.min.css')}}" rel="stylesheet">
    <!-- text fonts -->
    <script src="{{ asset('admin-assets/js/jquery-2.1.4.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
