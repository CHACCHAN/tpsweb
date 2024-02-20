<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TPS</title>
    @vite('resources/sass/app.scss')
    {{-- フォント --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;400&family=Poppins:wght@300&display=swap" rel="stylesheet">
    {{-- 全体設定 --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-dark">
    <div id="app"></div>
    @vite('resources/js/app.js')
</body>
</html>