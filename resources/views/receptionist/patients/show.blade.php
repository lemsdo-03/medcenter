<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Patient Details') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    View patient information, emergency contact, and recent appointments.
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
                                {{ $patient->full_name }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Patient #{{ $patient->id }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('receptionist.patients.edit', $patient) }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-amber-50 text-amber-700 text-sm font-semibold border border-amber-100 hover:bg-amber-100 transition">
                                Edit
                            </a>

                            <a href="{{ route('receptionist.patients.index') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @php
                        $gender = strtolower($patient->gender ?? '');
                        $genderStyles = [
                            'male' => 'bg-blue-50 text-blue-700 border-blue-100',
                            'female' => 'bg-rose-50 text-rose-700 border-rose-100',
                            'other' => 'bg-slate-100 text-slate-700 border-slate-200',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Personal Information</h4>

                            <dl class="mt-4 space-y-3">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">
                                        {{ optional($patient->date_of_birth)->format('F d, Y') ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gender</dt>
                                    <dd class="mt-2">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $genderStyles[$gender] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ ucfirst($patient->gender ?? 'N/A') }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">{{ $patient->phone ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">{{ $patient->email ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 whitespace-pre-wrap">{{ $patient->address ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h4 class="text-sm font-semibold text-slate-900">Emergency Contact</h4>

                            <dl class="mt-4 space-y-3">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">{{ $patient->emergency_contact_name ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900">{{ $patient->emergency_contact_phone ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($patient->medical_history)
                            <div class="md:col-span-2 rounded-3xl border border-slate-200 bg-white p-5">
                                <h4 class="text-sm font-semibold text-slate-900">Medical History</h4>
                                <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $patient->medical_history }}</p>
                            </div>
                        @endif

                        @if($patient->allergies)
                            <div class="md:col-span-2 rounded-3xl border border-slate-200 bg-white p-5">
                                <h4 class="text-sm font-semibold text-slate-900">Allergies</h4>
                                <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $patient->allergies }}</p>
                            </div>
                        @endif
                    </div>

                    @if($patient->appointments->count() > 0)
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

                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="text-sm font-semibold text-slate-900">
                                    Recent Appointments ({{ $patient->appointments->count() }})
                                </h4>
                                <span class="text-xs text-slate-500">Showing up to 5</span>
                            </div>

                            <div class="mt-4 overflow-x-auto rounded-3xl border border-slate-200">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                            <th class="py-3 px-4">Date & Time</th>
                                            <th class="py-3 px-4">Doctor</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4 text-right">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($patient->appointments->take(5) as $appointment)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                                    {{ $appointment->appointment_date->format('M d, Y H:i') }}
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
                                                    <a href="{{ route('receptionist.appointments.show', $appointment) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
