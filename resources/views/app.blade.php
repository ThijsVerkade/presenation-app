<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="/fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="/fontawesome/css/brands.css" rel="stylesheet" />
    <link href="/fontawesome/css/light.css" rel="stylesheet" />

    @vite(['resources/js/app.ts', 'resources/sass/app.scss'])
    @inertiaHead
    @routes
</head>
<body>
@inertia
</body>
</html>
