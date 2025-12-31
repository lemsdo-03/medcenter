<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                {{ __('Doctor Dashboard') }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Overview for {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-6">

            {{-- Emergency modal (server-filled + reused by polling) --}}
            <div
                id="emergency-modal"
                class="{{ $latestEmergency ? 'flex' : 'hidden' }} fixed inset-0 z-40 items-center justify-center bg-black/40 p-4"
                aria-hidden="{{ $latestEmergency ? 'false' : 'true' }}"
            >
                <div class="w-full max-w-lg rounded-3xl overflow-hidden border border-rose-200 bg-white shadow-xl">
                    <div class="px-6 py-4 bg-rose-50 border-b border-rose-200">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-rose-800">Emergency Alert</h3>
                                <p id="emergency-time" class="text-xs text-rose-600 mt-1">
                                    @if($latestEmergency)
                                        Received at {{ $latestEmergency->created_at->format('M d, Y H:i') }}
                                    @endif
                                </p>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-600 text-white">
                                Urgent
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <p id="emergency-message" class="text-slate-800 whitespace-pre-line">
                            @if($latestEmergency)
                                {{ $latestEmergency->message }}
                            @endif
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2">
                        <button
                            id="emergency-dismiss-btn"
                            type="button"
                            class="px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition"
                        >
                            Dismiss
                        </button>
                    </div>
                </div>
            </div>

            {{-- Welcome + quick stats --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-900">
                            Welcome, Dr. {{ auth()->user()->name }}
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">
                            Here is a quick overview of your day.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3 w-full lg:w-auto">
                        <div class="px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Today</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $todayAppointments->count() }}</p>
                            <p class="text-xs text-slate-500">appointments</p>
                        </div>

                        <div class="px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Upcoming</p>
                            <p class="mt-1 text-2xl font-semibold text-emerald-800">{{ $upcomingAppointments->count() }}</p>
                            <p class="text-xs text-emerald-700">next 7 days</p>
                        </div>

                        <div class="px-4 py-3 rounded-2xl bg-rose-50 border border-rose-100">
                            <p class="text-xs font-semibold text-rose-700 uppercase tracking-wide">Alerts</p>
                            <p class="mt-1 text-2xl font-semibold text-rose-800">{{ $latestEmergency ? 1 : 0 }}</p>
                            <p class="text-xs text-rose-700">latest</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lists --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Today's appointments --}}
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Today's Appointments</h3>
                            <p class="text-sm text-slate-500 mt-1">For today only</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $todayAppointments->count() }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($todayAppointments->count() > 0)
                            <div class="space-y-3">
                                @foreach($todayAppointments as $appointment)
                                    <div class="rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 transition">
                                        <div class="p-4 flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-emerald-800">
                                                    {{ $appointment->appointment_date->format('H:i') }}
                                                </p>

                                                <p class="text-sm text-slate-700 mt-1">
                                                    <a href="{{ route('doctor.patient.view', $appointment->patient) }}"
                                                       class="font-semibold text-slate-900 hover:underline">
                                                        {{ $appointment->patient->full_name }}
                                                    </a>
                                                </p>

                                                <p class="text-xs text-slate-500 mt-1">
                                                    Appointment ID: {{ $appointment->id }}
                                                </p>
                                            </div>

                                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                               class="shrink-0 px-4 py-2 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="mx-auto h-12 w-12 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                <p class="mt-4 text-sm text-slate-500">No appointments scheduled for today.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Upcoming appointments --}}
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Upcoming Appointments</h3>
                            <p class="text-sm text-slate-500 mt-1">Next 7 days</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                            {{ $upcomingAppointments->count() }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($upcomingAppointments->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingAppointments as $appointment)
                                    <div class="rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 transition">
                                        <div class="p-4 flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ $appointment->appointment_date->format('M d, H:i') }}
                                                </p>

                                                <p class="text-sm text-slate-700 mt-1">
                                                    <a href="{{ route('doctor.patient.view', $appointment->patient) }}"
                                                       class="font-semibold text-slate-900 hover:underline">
                                                        {{ $appointment->patient->full_name }}
                                                    </a>
                                                </p>

                                                <p class="text-xs text-slate-500 mt-1">
                                                    Appointment ID: {{ $appointment->id }}
                                                </p>
                                            </div>

                                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                               class="shrink-0 px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="mx-auto h-12 w-12 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                <p class="mt-4 text-sm text-slate-500">No upcoming appointments.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Quick actions --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('doctor.appointments') }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                        View All Appointments
                    </a>

                    <a href="{{ route('doctor.availability') }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        Set Availability
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('emergency-modal');
            const dismissBtn = document.getElementById('emergency-dismiss-btn');
            const msgEl = document.getElementById('emergency-message');
            const timeEl = document.getElementById('emergency-time');

            function hideModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
            }

            function showModal(notification) {
                msgEl.textContent = notification.message || '';
                timeEl.textContent = 'Received at ' + (notification.created_at || '');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
            }

            if (dismissBtn) dismissBtn.addEventListener('click', hideModal);

            // click outside closes (UI only)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) hideModal();
            });

            // Polling for new emergency notifications
            let lastNotificationId = {{ $latestEmergency?->id ?? 'null' }};

            async function pollEmergency() {
                try {
                    const response = await fetch("{{ route('doctor.emergency.latest') }}", {
                        headers: { 'Accept': 'application/json' },
                    });

                    if (!response.ok) return;

                    const data = await response.json();
                    if (data.notification && data.notification.id !== lastNotificationId) {
                        lastNotificationId = data.notification.id;
                        showModal(data.notification);
                    }
                } catch (e) {
                    // ignore network errors
                } finally {
                    setTimeout(pollEmergency, 10000);
                }
            }

            pollEmergency();
        });
    </script>
</x-app-layout>
