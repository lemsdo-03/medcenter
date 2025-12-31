<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedCenter')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen bg-slate-50 text-slate-900 font-sans">
    <div class="h-full flex">

        {{-- Sidebar --}}
        <aside class="w-72 bg-white border-r border-slate-200 flex flex-col">
            <div class="px-6 py-5 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="MedCenter" class="h-10 w-10 object-contain" />
                    <div class="leading-tight">
                        <div class="font-semibold">MedCenter</div>
                        <div class="text-xs text-slate-500">Staff Portal</div>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1 text-sm">
                @php
                    $linkBase = "flex items-center gap-3 px-4 py-3 rounded-2xl transition";
                    $linkIdle = "text-slate-700 hover:bg-slate-100";
                    $linkActive = "bg-emerald-50 text-emerald-800 border border-emerald-100";
                @endphp

                <a href="{{ route('dashboard') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkIdle }}">
                    <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                    Dashboard
                </a>

                <a href="{{ route('patients') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('patients*') ? $linkActive : $linkIdle }}">
                    <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                    Patients
                </a>

                <a href="{{ route('appointments') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('appointments*') ? $linkActive : $linkIdle }}">
                    <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                    Appointments
                </a>

                <a href="{{ route('staff') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('staff*') ? $linkActive : $linkIdle }}">
                    <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                    Staff
                </a>
            </nav>

            <div class="p-4 border-t border-slate-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200">
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-xl font-semibold truncate">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-sm text-slate-500 truncate">@yield('page-subtitle', '')</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            class="hidden md:block w-72 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0 text-sm"
                            placeholder="Search..."
                        />
                        <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200"></div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6 overflow-auto">
                <div class="max-w-6xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>
</body>
</html>
