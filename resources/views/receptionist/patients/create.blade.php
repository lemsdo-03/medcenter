<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Add New Patient') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Register a patient. Optionally book an appointment right away.
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
                    <h3 class="text-lg font-semibold text-slate-900">Register New Patient</h3>

                    <form method="POST" action="{{ route('receptionist.patients.store') }}" class="mt-4">
                        @csrf

                        @php
                            $label = "block text-sm font-medium text-slate-700 mb-1";
                            $field = "w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0";
                            $error = "text-xs text-rose-600 mt-1";
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="{{ $label }}">First Name <span class="text-rose-600">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="{{ $field }}">
                                @error('first_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Last Name <span class="text-rose-600">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="{{ $field }}">
                                @error('last_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Date of Birth <span class="text-rose-600">*</span></label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="{{ $field }}">
                                @error('date_of_birth') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Gender <span class="text-rose-600">*</span></label>
                                <select name="gender" required class="{{ $field }}">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                  
                                </select>
                                @error('gender') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="{{ $field }}">
                                @error('phone') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="{{ $field }}">
                                @error('email') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Address</label>
                                <textarea name="address" rows="2" class="{{ $field }}">{{ old('address') }}</textarea>
                                @error('address') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="{{ $field }}">
                            </div>

                            <div>
                                <label class="{{ $label }}">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="{{ $field }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Medical History</label>
                                <textarea name="medical_history" rows="3" class="{{ $field }}">{{ old('medical_history') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="{{ $label }}">Allergies</label>
                                <textarea name="allergies" rows="2" class="{{ $field }}">{{ old('allergies') }}</textarea>
                            </div>
                        </div>

                        {{-- Appointment Booking Section --}}
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="book_appointment" id="book_appointment" value="1"
                                       {{ old('book_appointment') ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-emerald-700 focus:ring-0">
                                <span class="text-sm font-medium text-slate-700">
                                    Book an appointment for this patient
                                </span>
                            </label>

                            <div id="appointment_section" class="hidden mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="{{ $label }}">Doctor <span class="text-rose-600">*</span></label>
                                        <select name="doctor_id" id="doctor_id" class="{{ $field }}">
                                            <option value="">Select Doctor</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="{{ $label }}">Appointment Date <span class="text-rose-600">*</span></label>
                                        <input type="date" name="appointment_date" id="appointment_date"
                                               value="{{ old('appointment_date') }}"
                                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                               class="{{ $field }}">
                                        @error('appointment_date') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                        <p class="text-xs text-slate-500 mt-1">Future dates only.</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="{{ $label }}">Available Time Slots</label>
                                        <div id="available_slots" class="min-h-[72px] p-4 rounded-3xl border border-slate-200 bg-slate-50">
                                            <p class="text-slate-500 text-sm">Select a doctor and date to view available time slots.</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="{{ $label }}">Selected Time <span class="text-rose-600">*</span></label>
                                        <input type="time" name="appointment_time" id="appointment_time"
                                               value="{{ old('appointment_time') }}"
                                               class="{{ $field }}" readonly>
                                        @error('appointment_time') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                        <p class="text-xs text-slate-500 mt-1">Click a slot above to select.</p>
                                    </div>

                                    <div>
                                        <label class="{{ $label }}">Appointment Notes</label>
                                        <textarea name="appointment_notes" rows="2" class="{{ $field }}">{{ old('appointment_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                Register Patient
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookAppointmentCheckbox = document.getElementById('book_appointment');
            const appointmentSection = document.getElementById('appointment_section');

            const doctorSelect = document.getElementById('doctor_id');
            const appointmentDate = document.getElementById('appointment_date');
            const appointmentTime = document.getElementById('appointment_time');
            const availableSlotsDiv = document.getElementById('available_slots');

            function setAppointmentRequired(isRequired) {
                if (!doctorSelect || !appointmentDate || !appointmentTime) return;

                doctorSelect.required = isRequired;
                appointmentDate.required = isRequired;
                appointmentTime.required = isRequired;

                doctorSelect.disabled = !isRequired;
                appointmentDate.disabled = !isRequired;
                appointmentTime.disabled = !isRequired;
            }

            function resetAppointmentUI() {
                if (doctorSelect) doctorSelect.value = '';
                if (appointmentDate) appointmentDate.value = '';
                if (appointmentTime) appointmentTime.value = '';

                availableSlotsDiv.innerHTML =
                    '<p class="text-slate-500 text-sm">Select a doctor and date to view available time slots.</p>';
            }

            // Toggle appointment section
            bookAppointmentCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    appointmentSection.classList.remove('hidden');
                    setAppointmentRequired(true);
                } else {
                    appointmentSection.classList.add('hidden');
                    setAppointmentRequired(false);
                    resetAppointmentUI();
                }
            });

            function loadAvailableSlots() {
                const doctorId = doctorSelect.value;
                const date = appointmentDate.value;

                if (!doctorId || !date) {
                    availableSlotsDiv.innerHTML = '<p class="text-slate-500 text-sm">Select a doctor and date to view available time slots.</p>';
                    appointmentTime.value = '';
                    return;
                }

                // Validate date is future
                const selectedDate = new Date(date);
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(0, 0, 0, 0);

                if (selectedDate < tomorrow) {
                    availableSlotsDiv.innerHTML = '<p class="text-rose-600 text-sm">Please select a future date (not today/past).</p>';
                    appointmentTime.value = '';
                    return;
                }

                availableSlotsDiv.innerHTML = '<p class="text-slate-500 text-sm">Loading available slots...</p>';

                fetch(`{{ route('receptionist.appointments.availability.slots') }}?doctor_id=${doctorId}&date=${date}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => { throw new Error(data.error || 'Failed to load available slots'); });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        availableSlotsDiv.innerHTML = `<p class="text-rose-600 text-sm">${data.error}</p>`;
                        appointmentTime.value = '';
                        return;
                    }

                    if (data.slots && data.slots.length > 0) {
                        let html = `<p class="text-sm font-medium text-slate-700 mb-3">Available slots for ${data.date}:</p>`;
                        html += '<div class="flex flex-wrap gap-2">';

                        data.slots.forEach(slot => {
                            html += `
                                <button type="button"
                                    class="slot-btn inline-flex items-center px-4 py-2 rounded-2xl border border-emerald-100 bg-emerald-50 text-emerald-800 text-sm font-semibold hover:bg-emerald-100 transition"
                                    data-time="${slot}">
                                    ${slot}
                                </button>`;
                        });

                        html += '</div>';
                        availableSlotsDiv.innerHTML = html;

                        document.querySelectorAll('.slot-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                document.querySelectorAll('.slot-btn').forEach(b => {
                                    b.classList.remove('bg-emerald-700', 'text-white', 'border-emerald-700');
                                    b.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-100');
                                });

                                this.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-100');
                                this.classList.add('bg-emerald-700', 'text-white', 'border-emerald-700');

                                appointmentTime.value = this.dataset.time;
                            });
                        });
                    } else {
                        availableSlotsDiv.innerHTML = `<p class="text-slate-500 text-sm">No available slots found for ${data.date}. Choose another date.</p>`;
                        appointmentTime.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    availableSlotsDiv.innerHTML = `<p class="text-rose-600 text-sm">${error.message || 'Error loading slots. Try again.'}</p>`;
                    appointmentTime.value = '';
                });
            }

            if (doctorSelect) doctorSelect.addEventListener('change', loadAvailableSlots);
            if (appointmentDate) appointmentDate.addEventListener('change', loadAvailableSlots);

            // Init state
            if (bookAppointmentCheckbox.checked) {
                appointmentSection.classList.remove('hidden');
                setAppointmentRequired(true);
                if (doctorSelect.value && appointmentDate.value) loadAvailableSlots();
            } else {
                setAppointmentRequired(false);
            }
        });
    </script>
</x-app-layout>
