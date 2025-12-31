<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Appointment Details') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    View appointment info, participants, status, payment, and notes.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                Appointment #{{ $appointment->id }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $appointment->appointment_date->format('F d, Y \a\t H:i') }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('receptionist.appointments.edit', $appointment) }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-amber-50 text-amber-700 text-sm font-semibold border border-amber-100 hover:bg-amber-100 transition">
                                Edit
                            </a>

                            @if($appointment->status != 'cancelled')
                                <form method="POST" action="{{ route('receptionist.appointments.cancel', $appointment) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition"
                                            onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        Cancel
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('receptionist.appointments.index') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @php
                        $statusStyles = [
                            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-100',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                        ];
                        $statusDot = [
                            'scheduled' => 'bg-blue-500',
                            'completed' => 'bg-emerald-500',
                            'cancelled' => 'bg-rose-500',
                        ];

                        $payStyles = $appointment->is_paid
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                            : 'bg-amber-50 text-amber-700 border-amber-100';

                        $payDot = $appointment->is_paid ? 'bg-emerald-500' : 'bg-amber-500';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Appointment Information</h4>

                            <dl class="mt-4 space-y-3">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date & Time</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">
                                        {{ $appointment->appointment_date->format('F d, Y \a\t H:i') }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</dt>
                                    <dd class="mt-2">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $statusStyles[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            <span class="mr-2 h-2 w-2 rounded-full {{ $statusDot[$appointment->status] ?? 'bg-slate-400' }}"></span>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Payment Status</dt>
                                    <dd class="mt-2">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $payStyles }}">
                                            <span class="mr-2 h-2 w-2 rounded-full {{ $payDot }}"></span>
                                            {{ $appointment->is_paid ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Participants</h4>

                            <dl class="mt-4 space-y-3">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Patient</dt>
                                    <dd class="mt-1 text-sm font-medium">
                                        <a href="{{ route('receptionist.patients.show', $appointment->patient) }}"
                                           class="text-emerald-700 hover:underline">
                                            {{ $appointment->patient->full_name }}
                                        </a>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Doctor</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">
                                        {{ $appointment->doctor->name }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        @if($appointment->notes)
                            <div class="md:col-span-2 rounded-3xl border border-slate-200 bg-white p-5">
                                <h4 class="text-sm font-semibold text-slate-900">Notes</h4>
                                <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">
                                    {{ $appointment->notes }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
