<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('My Appointments') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Filter by date/status and open patient details.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Filters</h3>

                    <form method="GET" action="{{ route('doctor.appointments') }}"
                          class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                <option value="">All (Future & Today)</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past Appointments</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900 transition">
                                Filter
                            </button>

                            @if(request('date') || request('status'))
                                <a href="{{ route('doctor.appointments') }}"
                                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    @if($hasAppointments && $appointments->count() > 0)
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-xs font-semibold text-slate-600">
                                        <th class="px-4 py-3">Date & Time</th>
                                        <th class="px-4 py-3">Patient</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach($appointments as $appointment)
                                        @php
                                            $isPast = $appointment->appointment_date->isPast();

                                            $status = $appointment->status ?? 'scheduled';
                                            $statusClass = match ($status) {
                                                'scheduled' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                                'completed' => 'bg-emerald-50 text-emerald-800 border border-emerald-100',
                                                'cancelled' => 'bg-rose-50 text-rose-800 border border-rose-100',
                                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                                            };
                                        @endphp

                                        <tr class="hover:bg-slate-50/70 {{ $isPast ? 'opacity-80' : '' }}">
                                            <td class="px-4 py-4 text-sm text-slate-800">
                                                <div class="font-semibold text-slate-900">
                                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-slate-500 mt-1">
                                                    {{ $appointment->appointment_date->format('H:i') }}
                                                    @if($isPast && $appointment->status === 'completed')
                                                        <span class="ml-2">(Completed)</span>
                                                    @elseif($isPast)
                                                        <span class="ml-2">(Past)</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-sm">
                                                <a href="{{ route('doctor.patient.view', $appointment->patient) }}"
                                                   class="text-emerald-700 hover:text-emerald-800 hover:underline font-semibold">
                                                    {{ $appointment->patient->full_name }}
                                                </a>
                                            </td>

                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-4 text-right">
                                                <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                                   class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                            <p class="mt-4 text-lg font-semibold text-slate-900">No appointments found</p>
                            <p class="mt-1 text-sm text-slate-500">
                                @if(!$hasAppointments)
                                    Your schedule is empty for the selected period.
                                @else
                                    Try adjusting filters.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
