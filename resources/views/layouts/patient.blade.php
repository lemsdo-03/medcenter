<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MedCenter - Patient Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" alt="MedCenter" class="h-9 w-9 object-contain" />
                            <div class="leading-tight hidden sm:block">
                                <div class="font-semibold text-slate-900">MedCenter</div>
                                <div class="text-xs text-slate-500">Patient Portal</div>
                            </div>
                        </a>

                        <div class="hidden sm:flex items-center gap-2 text-sm">
                            <a href="{{ route('patient.dashboard') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('patient.doctors.index') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.doctors.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Doctors
                            </a>
                            <a href="{{ route('patient.appointments.index') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.appointments.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Appointments
                            </a>
                            <a href="{{ route('patient.chat.index') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.chat.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Chat
                            </a>
                            <a href="{{ route('patient.notifications.index') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.notifications.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Notifications
                            </a>
                            <a href="{{ route('patient.ratings.create') }}"
                               class="px-4 py-2 rounded-2xl {{ request()->routeIs('patient.ratings.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                                Feedback
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-3 px-3 py-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition">
                                    <div class="w-9 h-9 rounded-2xl bg-emerald-700 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left hidden md:block">
                                        <div class="text-sm font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-slate-500">Patient</div>
                                    </div>
                                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('patient.profile.edit')">
                                    Profile
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-rose-700 hover:bg-rose-50">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-6xl mx-auto py-4 px-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 py-8">
            <div class="max-w-6xl mx-auto px-6">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
