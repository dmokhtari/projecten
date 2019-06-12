<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.ico">
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- og meta tags -->
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('ogType')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:site_name" content="">

    <!-- iphone, ipad -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- <link rel="apple-touch-icon" href="assets/img/touch/iphone.png"> -->
    <!-- <link rel="apple-touch-icon" sizes="76x76" href="assets/img/touch/ipad.png"> -->
    <!-- <link rel="apple-touch-icon" sizes="120x120" href="assets/img/touch/iphone-r.png"> -->
    <!-- <link rel="apple-touch-icon" sizes="152x152" href="assets/img/touch/ipad-r.png"> -->

    <!-- css -->
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('/assets/css/app.css') }}">
</head>
<body>

<div id="admin">
    <admin></admin>
</div>

<!-- scripts -->
{{--<script src="{{ mix('/assets/js/manifest.js') }}"></script>--}}
{{--<script src="{{ mix('/assets/js/vendor.js') }}"></script>--}}
<script src="{{ mix('/assets/js/admin.js') }}"></script>
</body>
</html>
