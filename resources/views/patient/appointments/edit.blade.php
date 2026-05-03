<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Edit Appointment</h2>
        <p class="text-sm text-slate-500 mt-1">With Dr. {{ $appointment->doctor->name }}</p>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" x-data="editForm()">
            <form method="POST" action="{{ route('patient.appointments.update', $appointment) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium text-slate-700">Date</label>
                    <input type="date" name="appointment_date" x-model="date" @change="loadSlots()"
                        min="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                    <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700">Time Slot</label>
                    <div x-show="loading" class="mt-2 text-sm text-slate-500">Loading available slots...</div>
                    <div x-show="!loading && date && slots.length === 0" class="mt-2 text-sm text-rose-600">No available slots for this date.</div>
                    <div x-show="!loading && slots.length > 0" class="mt-2 flex flex-wrap gap-2">
                        <template x-for="slot in slots" :key="slot">
                            <label class="cursor-pointer">
                                <input type="radio" name="appointment_time" :value="slot" class="hidden peer" x-model="selectedSlot">
                                <span class="inline-block px-4 py-2 rounded-2xl border text-sm font-medium transition
                                    peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-700
                                    border-slate-200 text-slate-700 hover:bg-slate-50"
                                    x-text="slot"></span>
                            </label>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                </div>

                <div>
                    <label for="notes" class="text-sm font-medium text-slate-700">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">{{ old('notes', $appointment->notes) }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <a href="{{ route('patient.appointments.index') }}"
                       class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editForm() {
            return {
                date: '{{ $appointment->appointment_date->format("Y-m-d") }}',
                slots: [],
                selectedSlot: '{{ $appointment->appointment_date->format("H:i") }}',
                loading: false,
                async loadSlots() {
                    if (!this.date) return;
                    this.loading = true;
                    this.selectedSlot = '';
                    try {
                        const res = await fetch(`{{ route('patient.doctors.slots', $appointment->doctor) }}?date=${this.date}`);
                        const data = await res.json();
                        this.slots = data.slots;
                    } catch (e) {
                        this.slots = [];
                    }
                    this.loading = false;
                },
                init() {
                    this.loadSlots();
                }
            }
        }
    </script>
</x-patient-layout>
