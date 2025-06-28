<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="fixed w-full top-0 left-0 dark:bg-gray-800 bg-white shadow z-40" style="margin-top: 4rem;">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main  style="margin-top: 8rem;">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
