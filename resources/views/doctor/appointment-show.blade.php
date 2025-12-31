<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    Appointment Details
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    View schedule, patient link, and notes for this appointment.
                </p>
            </div>

            <a href="{{ route('doctor.appointments') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                {{-- Top --}}
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-semibold text-slate-900">
                                Appointment #{{ $appointment->id }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $appointment->appointment_date->format('F d, Y') }}
                                <span class="text-slate-400">•</span>
                                {{ $appointment->appointment_date->format('H:i') }}
                            </p>
                        </div>

                        @php
                            $status = $appointment->status ?? 'scheduled';
                            $statusClass = match ($status) {
                                'scheduled' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                'completed' => 'bg-emerald-50 text-emerald-800 border border-emerald-100',
                                'cancelled' => 'bg-rose-50 text-rose-800 border border-rose-100',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                            };
                            $paid = (bool) ($appointment->is_paid ?? false);
                        @endphp

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>

                            @if($paid)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    Paid
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-100">
                                    Unpaid
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                        {{-- Appointment info --}}
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Appointment Information</h4>
                            <dl class="mt-3 space-y-2 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Date</dt>
                                    <dd class="font-medium text-slate-800">
                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Time</dt>
                                    <dd class="font-medium text-slate-800">
                                        {{ $appointment->appointment_date->format('H:i') }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Status</dt>
                                    <dd class="font-medium text-slate-800">
                                        {{ ucfirst($status) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Patient --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Patient</h4>
                            <div class="mt-3">
                                <a href="{{ route('doctor.patient.view', $appointment->patient) }}"
                                   class="inline-flex items-center gap-3 hover:bg-slate-50 rounded-2xl p-3 transition w-full">
                                    @php
                                        $initial = strtoupper(substr($appointment->patient->full_name ?? 'P', 0, 1));
                                    @endphp
                                    <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center font-semibold text-slate-700">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $appointment->patient->full_name }}
                                        </div>
                                        <div class="text-xs text-slate-500">Open patient information</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Actions</h4>
                            <p class="text-sm text-slate-500 mt-2">Write a note linked to this visit.</p>

                            <a href="{{ route('doctor.notes.create', ['patient' => $appointment->patient, 'appointment' => $appointment]) }}"
                               class="mt-4 inline-flex w-full items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Add Medical Notes
                            </a>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($appointment->notes)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Notes</h4>
                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                        </div>
                    @endif

                    {{-- Medical Notes --}}
                    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                        <div class="p-5 border-b border-slate-200">
                            <h4 class="text-sm font-semibold text-slate-900">Medical Notes</h4>
                            <p class="text-sm text-slate-500 mt-1">
                                Notes recorded for this appointment.
                            </p>
                        </div>

                        <div class="p-5">
                            @if($appointment->medicalNotes->count() > 0)
                                <div class="space-y-3">
                                    @foreach($appointment->medicalNotes->sortByDesc('created_at') as $note)
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $note->notes }}</p>

                                            @if($note->diagnosis || $note->prescription)
                                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @if($note->diagnosis)
                                                        <div class="rounded-2xl bg-white border border-slate-200 p-3">
                                                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Diagnosis</p>
                                                            <p class="mt-1 text-sm text-slate-800">{{ $note->diagnosis }}</p>
                                                        </div>
                                                    @endif

                                                    @if($note->prescription)
                                                        <div class="rounded-2xl bg-white border border-slate-200 p-3">
                                                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Prescription</p>
                                                            <p class="mt-1 text-sm text-slate-800">{{ $note->prescription }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <p class="mt-3 text-xs text-slate-500">
                                                Added on {{ $note->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                    <p class="mt-4 text-lg font-semibold text-slate-900">No notes yet</p>
                                    <p class="mt-1 text-sm text-slate-500">Add a note for this appointment.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
