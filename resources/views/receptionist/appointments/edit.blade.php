<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Edit Appointment') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update appointment details, status, notes, and payment.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if(session('error'))
                <div class="rounded-3xl border border-rose-200 bg-rose-50 text-rose-800 px-6 py-4">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Appointment Details</h3>

                    <form method="POST" action="{{ route('receptionist.appointments.update', $appointment) }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Patient <span class="text-rose-600">*</span>
                                </label>
                                <select name="patient_id" required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Doctor <span class="text-rose-600">*</span>
                                </label>
                                <select name="doctor_id" required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Date <span class="text-rose-600">*</span>
                                </label>
                                <input type="date" name="appointment_date"
                                       value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}"
                                       required
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                @error('appointment_date')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Time <span class="text-rose-600">*</span>
                                </label>
                                <input type="time" name="appointment_time"
                                       value="{{ old('appointment_time', $appointment->appointment_time ?? optional($appointment->appointment_date)->format('H:i')) }}"
                                       required
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                @error('appointment_time')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Status <span class="text-rose-600">*</span>
                                </label>
                                <select name="status" required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                    <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                                <textarea name="notes" rows="3"
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                                          placeholder="Optional notes...">{{ old('notes', $appointment->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_paid" value="1"
                                           {{ old('is_paid', $appointment->is_paid) ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-emerald-700 focus:ring-0">
                                    <span class="ml-2 text-sm text-slate-700">Paid</span>
                                </label>
                            </div>

                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Update Appointment
                            </button>

                            <a href="{{ route('receptionist.appointments.index') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
