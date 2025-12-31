<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Add Medical Notes') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Patient: <span class="font-semibold text-slate-900">{{ $patient->full_name }}</span>
                </p>
            </div>

            <a href="{{ route('doctor.patient.view', $patient) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
                    <p class="font-semibold">Fix the following:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">New Note</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Link it to an appointment if needed (optional).
                    </p>
                </div>

                <form method="POST" action="{{ route('doctor.notes.store', $patient) }}" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Appointment --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Appointment (Optional)</label>
                            <select name="appointment_id"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                <option value="">Select Appointment</option>
                                @foreach($appointments as $apt)
                                    <option value="{{ $apt->id }}" {{ ($appointment && $appointment->id == $apt->id) ? 'selected' : '' }}>
                                        {{ $apt->appointment_date->format('M d, Y H:i') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Diagnosis --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Diagnosis</label>
                            <input type="text" name="diagnosis" value="{{ old('diagnosis') }}"
                                   placeholder="Enter diagnosis"
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        {{-- Notes --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Medical Notes <span class="text-rose-600">*</span></label>
                            <textarea name="notes" rows="8" required
                                      placeholder="Enter observations, treatment notes, examination findings, etc."
                                      class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-rose-600 text-sm mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prescription --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Prescription</label>
                            <textarea name="prescription" rows="4"
                                      placeholder="Enter prescription details, medication, dosage, etc."
                                      class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">{{ old('prescription') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Save Medical Notes
                        </button>

                        <a href="{{ route('doctor.patient.view', $patient) }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
