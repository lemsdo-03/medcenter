<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    Patient Information
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Profile, emergency contact, and medical notes.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('doctor.appointments') }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                    Back
                </a>

                <a href="{{ route('doctor.notes.create', $patient) }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                    Add Medical Notes
                </a>
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
                {{-- Top patient header --}}
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            @php
                                $initials = strtoupper(substr($patient->first_name ?? $patient->full_name ?? 'P', 0, 1));
                            @endphp
                            <div class="h-12 w-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700">
                                {{ $initials }}
                            </div>

                            <div>
                                <h3 class="text-2xl font-semibold text-slate-900">
                                    {{ $patient->full_name }}
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    DOB:
                                    <span class="font-medium text-slate-800">
                                        {{ optional($patient->date_of_birth)->format('F d, Y') ?? 'N/A' }}
                                    </span>
                                    @if($patient->date_of_birth)
                                        <span class="text-slate-400">•</span>
                                        <span class="text-slate-600">Age: {{ $patient->date_of_birth->age }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ ucfirst($patient->gender ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="p-6 space-y-6">

                    {{-- Info grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                        {{-- Personal --}}
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Personal Information</h4>
                            <dl class="mt-3 space-y-2 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Phone</dt>
                                    <dd class="font-medium text-slate-800">{{ $patient->phone ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Email</dt>
                                    <dd class="font-medium text-slate-800">{{ $patient->email ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Address</dt>
                                    <dd class="font-medium text-slate-800 text-right">{{ $patient->address ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Emergency --}}
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Emergency Contact</h4>
                            <dl class="mt-3 space-y-2 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Name</dt>
                                    <dd class="font-medium text-slate-800">{{ $patient->emergency_contact_name ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Phone</dt>
                                    <dd class="font-medium text-slate-800">{{ $patient->emergency_contact_phone ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Quick summary --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Quick Summary</h4>
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Appointments</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">
                                        {{ $patient->appointments->count() ?? 0 }}
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Notes</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">
                                        {{ $patient->medicalNotes->count() ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Medical History + Allergies --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Medical History</h4>
                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">
                                {{ $patient->medical_history ?: 'No medical history recorded.' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Allergies</h4>
                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">
                                {{ $patient->allergies ?: 'No allergies recorded.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Appointments (with paid/unpaid) --}}
                    @if(($patient->appointments->count() ?? 0) > 0)
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                            <div class="p-5 border-b border-slate-200">
                                <h4 class="text-sm font-semibold text-slate-900">Appointments</h4>
                                <p class="text-sm text-slate-500 mt-1">Payment status is taken from the appointment paid/unpaid field.</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr class="text-left text-xs font-semibold text-slate-600">
                                            <th class="px-4 py-3">Date</th>
                                            <th class="px-4 py-3">Doctor</th>
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        @foreach($patient->appointments->sortByDesc('appointment_date') as $appt)
                                            @php
                                                $paid = (bool) ($appt->is_paid ?? false);
                                            @endphp
                                            <tr class="hover:bg-slate-50/70">
                                                <td class="px-4 py-3 text-sm text-slate-800">
                                                    {{ optional($appt->appointment_date)->format('M d, Y • H:i') ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-800">
                                                    {{ $appt->doctor->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                        {{ ucfirst($appt->status ?? 'scheduled') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    @if($paid)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                                            Paid
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-100">
                                                            Unpaid
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Medical Notes --}}
                    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                        <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-3">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">Medical Notes</h4>
                                <p class="text-sm text-slate-500 mt-1">Latest notes appear first.</p>
                            </div>
                        </div>

                        <div class="p-5">
                            @if(($patient->medicalNotes->count() ?? 0) > 0)
                                <div class="space-y-3">
                                    @foreach($patient->medicalNotes->sortByDesc('created_at') as $note)
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $note->doctor->name ?? 'Doctor' }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-1">
                                                        {{ optional($note->created_at)->format('M d, Y') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $note->notes }}</p>

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
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                    <p class="mt-4 text-lg font-semibold text-slate-900">No notes yet</p>
                                    <p class="mt-1 text-sm text-slate-500">Add the first medical note for this patient.</p>
                                    <a href="{{ route('doctor.notes.create', $patient) }}"
                                       class="inline-flex mt-6 items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                        Add Medical Notes
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
