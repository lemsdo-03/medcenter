<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Appointments') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    View and manage scheduled, completed, and cancelled appointments.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if(session('success'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-6 py-4">
                    <p class="text-sm font-medium">
                        <span class="font-semibold">Success:</span> {{ session('success') }}
                    </p>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-3xl border border-rose-200 bg-rose-50 text-rose-800 px-6 py-4">
                    <p class="text-sm font-medium">
                        <span class="font-semibold">Error:</span> {{ session('error') }}
                    </p>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Appointment Management</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Filter by date/status and manage appointments.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('receptionist.appointments.availability') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                Check Availability
                            </a>

                            <a href="{{ route('receptionist.appointments.create') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Book Appointment
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('receptionist.appointments.index') }}"
                          class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                <option value="">All</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition w-full sm:w-auto">
                                Filter
                            </button>

                            @if(request('date') || request('status'))
                                <a href="{{ route('receptionist.appointments.index') }}"
                                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition w-full sm:w-auto">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    @if($appointments->count() > 0)
                        <div class="overflow-x-auto rounded-3xl border border-slate-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                        <th class="py-3 px-4">Date & Time</th>
                                        <th class="py-3 px-4">Patient</th>
                                        <th class="py-3 px-4">Doctor</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($appointments as $appointment)
                                        @php
                                            $statusStyles = [
                                                'scheduled' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            ];
                                            $dotStyles = [
                                                'scheduled' => 'bg-blue-500',
                                                'completed' => 'bg-emerald-500',
                                                'cancelled' => 'bg-rose-500',
                                            ];
                                        @endphp

                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                                {{ $appointment->appointment_date->format('M d, Y H:i') }}
                                            </td>

                                            <td class="py-3 px-4">
                                                <div class="font-medium text-slate-900">{{ $appointment->patient->full_name }}</div>
                                                <div class="text-xs text-slate-500">#{{ $appointment->patient->id }}</div>
                                            </td>

                                            <td class="py-3 px-4 text-slate-800">
                                                {{ $appointment->doctor->name }}
                                            </td>

                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $statusStyles[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                    <span class="mr-2 h-2 w-2 rounded-full {{ $dotStyles[$appointment->status] ?? 'bg-slate-400' }}"></span>
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>

                                            <td class="py-3 px-4 text-right">
                                                <div class="inline-flex gap-2">
                                                    <a href="{{ route('receptionist.appointments.show', $appointment) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                                        View
                                                    </a>
                                                    <a href="{{ route('receptionist.appointments.edit', $appointment) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-100 hover:bg-amber-100 transition">
                                                        Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                            <p class="mt-4 text-lg font-semibold text-slate-900">No appointments found</p>
                            <p class="mt-1 text-sm text-slate-500">Get started by booking your first appointment.</p>

                            <a href="{{ route('receptionist.appointments.create') }}"
                               class="mt-5 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Book Appointment
                            </a>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
