<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style type="text/css">
        body {padding-top: 50px;color: #5a5a5a;}
    </style>
    @section('style')@show
    <link href="{{ asset('static/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('static/js/jquery.js') }}"></script>
    <script src="{{ asset('static/js/bootstrap.min.js') }}"></script>
</head>
<body>
@component('common.adminNav')@endcomponent
@section('content')@show
@component('common.footer')@endcomponent
@component('common.error')@endcomponent

@section('javascript')@show
</body>
</html>