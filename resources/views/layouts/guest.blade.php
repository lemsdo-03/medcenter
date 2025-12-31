<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MedCenter - {{ config('app.name', 'Medical Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased medical-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-sm border border-gray-200 overflow-hidden sm:rounded-lg">

                <div class="mb-6 flex items-center justify-center gap-3">
                    <img
                        src="{{ asset('images/logo.webp') }}"
                        alt="MedCenter logo"
                        class="h-10 w-10 object-contain"
                    />
                    <h1 class="text-2xl font-semibold text-gray-900">MedCenter</h1>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
