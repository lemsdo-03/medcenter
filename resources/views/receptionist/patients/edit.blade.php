<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Edit Patient') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Update patient details and medical information.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if ($errors->any())
                <div class="rounded-3xl border border-rose-200 bg-rose-50 text-rose-800 px-6 py-4">
                    <p class="text-sm font-semibold">Please fix the errors below:</p>
                    <ul class="list-disc ml-5 mt-2 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Patient Details</h3>

                    <form method="POST" action="{{ route('receptionist.patients.update', $patient) }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        @php
                            $label = "block text-sm font-medium text-slate-700 mb-1";
                            $field = "w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0";
                            $error = "text-xs text-rose-600 mt-1";
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="{{ $label }}">First Name <span class="text-rose-600">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required class="{{ $field }}">
                                @error('first_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Last Name <span class="text-rose-600">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required class="{{ $field }}">
                                @error('last_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Date of Birth <span class="text-rose-600">*</span></label>
                                <input type="date" name="date_of_birth"
                                       value="{{ old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d')) }}"
                                       required class="{{ $field }}">
                                @error('date_of_birth') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Gender <span class="text-rose-600">*</span></label>
                                <select name="gender" required class="{{ $field }}">
                                   
                                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                   
                                </select>
                                @error('gender') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" class="{{ $field }}">
                                @error('phone') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Email</label>
                                <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="{{ $field }}">
                                @error('email') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Address</label>
                                <textarea name="address" rows="2" class="{{ $field }}">{{ old('address', $patient->address) }}</textarea>
                                @error('address') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name"
                                       value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                       class="{{ $field }}">
                                @error('emergency_contact_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone"
                                       value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                                       class="{{ $field }}">
                                @error('emergency_contact_phone') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Medical History</label>
                                <textarea name="medical_history" rows="3" class="{{ $field }}">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                @error('medical_history') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Allergies</label>
                                <textarea name="allergies" rows="2" class="{{ $field }}">{{ old('allergies', $patient->allergies) }}</textarea>
                                @error('allergies') <p class="{{ $error }}">{{ $errorText ?? $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Update Patient
                            </button>

                            <a href="{{ route('receptionist.patients.index') }}"
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
