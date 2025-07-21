<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://kit.fontawesome.com/d1f3a2f699.js" crossorigin="anonymous"></script>

    @vite(['resources/js/app.ts', 'resources/sass/app.scss'])
    @inertiaHead
    @routes
</head>
<body>
@inertia
</body>
</html>
