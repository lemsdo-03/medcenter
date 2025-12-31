<nav class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between h-16">

            {{-- Left: Logo + Links --}}
            <div class="flex items-center gap-6">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="MedCenter" class="h-9 w-9 object-contain" />
                    <div class="leading-tight hidden sm:block">
                        <div class="font-semibold text-slate-900">MedCenter</div>
                        <div class="text-xs text-slate-500">Staff Portal</div>
                    </div>
                </a>

                {{-- Desktop Links --}}
                <div class="hidden sm:flex items-center gap-2 text-sm">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="px-4 py-2 rounded-2xl text-slate-700 hover:bg-slate-100"
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'receptionist')
                        <x-nav-link
                            :href="route('receptionist.patients.index')"
                            :active="request()->routeIs('receptionist.patients.*')"
                            class="px-4 py-2 rounded-2xl text-slate-700 hover:bg-slate-100"
                        >
                            Patients
                        </x-nav-link>

                        <x-nav-link
                            :href="route('receptionist.appointments.index')"
                            :active="request()->routeIs('receptionist.appointments.*')"
                            class="px-4 py-2 rounded-2xl text-slate-700 hover:bg-slate-100"
                        >
                            Appointments
                        </x-nav-link>

                        <x-nav-link
                            :href="route('receptionist.reports.monthly')"
                            :active="request()->routeIs('receptionist.reports.*')"
                            class="px-4 py-2 rounded-2xl text-slate-700 hover:bg-slate-100"
                        >
                            Reports
                        </x-nav-link>

                        <x-nav-link
                            :href="route('receptionist.emergency.create')"
                            :active="request()->routeIs('receptionist.emergency.*')"
                            class="px-4 py-2 rounded-2xl text-rose-700 hover:bg-rose-50 font-semibold"
                        >
                            Emergency Alert
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- Right: User Dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-2xl bg-emerald-700 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <div class="text-left hidden md:block">
                                <div class="text-sm font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>

                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Profile hidden --}}
                        @if(false)
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-rose-700 hover:bg-rose-50"
                            >
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</nav>
