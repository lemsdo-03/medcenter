<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                {{ __('Doctor Dashboard') }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Overview and quick actions for today.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6">

            {{-- Top welcome card --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            Welcome, {{ auth()->user()->name }} 👋
                        </h1>
                        <p class="text-sm text-slate-600 mt-1">
                            You are logged in as <span class="font-semibold">{{ ucfirst(auth()->user()->role) }}</span>.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('doctor.appointments') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            View My Appointments
                        </a>

                        {{-- Optional second action (you can delete if not needed) --}}
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-3xl border border-slate-200 bg-white p-5">
                    <p class="text-xs text-slate-500">Today</p>
                    <p class="text-2xl font-semibold text-slate-900 mt-1">—</p>
                    <p class="text-sm text-slate-600">Appointments</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5">
                    <p class="text-xs text-slate-500">Pending</p>
                    <p class="text-2xl font-semibold text-slate-900 mt-1">—</p>
                    <p class="text-sm text-slate-600">Notes to complete</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5">
                    <p class="text-xs text-slate-500">This week</p>
                    <p class="text-2xl font-semibold text-slate-900 mt-1">—</p>
                    <p class="text-sm text-slate-600">Total visits</p>
                </div>
            </div>

            {{-- Main grid --}}
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Left: next steps --}}
                <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-slate-900">Quick actions</h3>
                        <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                            Doctor tools
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a href="{{ route('doctor.appointments') }}"
                           class="p-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition">
                            <p class="font-semibold text-slate-900">View schedule</p>
                            <p class="text-sm text-slate-600 mt-1">See upcoming appointments.</p>
                        </a>

                        <a href="#"
                           class="p-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition">
                            <p class="font-semibold text-slate-900">Create note</p>
                            <p class="text-sm text-slate-600 mt-1">Add consultation notes faster.</p>
                        </a>

                        <a href="#"
                           class="p-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition">
                            <p class="font-semibold text-slate-900">Search patient</p>
                            <p class="text-sm text-slate-600 mt-1">Find records by name or ID.</p>
                        </a>

                        <a href="#"
                           class="p-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition">
                            <p class="font-semibold text-slate-900">Update availability</p>
                            <p class="text-sm text-slate-600 mt-1">Adjust your time slots.</p>
                        </a>
                    </div>
                </div>

                {{-- Right: tips/notice --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-900">Notice</h3>
                    <p class="text-sm text-slate-600 mt-2">
                        Keep notes short and clear. Use standardized terms where possible.
                    </p>

                    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Tip</p>
                        <p class="text-sm text-slate-700 mt-1">
                            If you’re behind, finish notes right after each appointment.
                        </p>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('doctor.appointments') }}"
                           class="inline-flex w-full items-center justify-center px-4 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                            Open schedule
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
