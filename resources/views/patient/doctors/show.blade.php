<x-patient-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Dr. {{ $doctor->name }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $doctor->specialty ?? 'General Practice' }}</p>
            </div>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('patient.chat.start', $doctor) }}">
                    @csrf
                    <button type="submit" class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        Start Chat
                    </button>
                </form>
                <a href="{{ route('patient.appointments.create', $doctor) }}"
                   class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                    Book Appointment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-semibold text-slate-900 mb-4">Weekly Availability</h3>

        @if($availabilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($availabilities as $avail)
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50">
                        <p class="font-semibold text-slate-900 capitalize">{{ $avail->day_of_week }}</p>
                        <p class="text-sm text-slate-600 mt-1">
                            {{ \Carbon\Carbon::createFromTimeString($avail->start_time)->format('h:i A') }}
                            -
                            {{ \Carbon\Carbon::createFromTimeString($avail->end_time)->format('h:i A') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-500">No availability set for this doctor.</p>
        @endif
    </div>

    {{-- Check slots for a specific date --}}
    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" x-data="slotChecker()">
        <h3 class="font-semibold text-slate-900 mb-4">Check Available Slots</h3>

        <div class="flex items-end gap-3">
            <div class="flex-1">
                <label class="text-sm font-medium text-slate-700">Select Date</label>
                <input type="date" x-model="date" min="{{ date('Y-m-d') }}"
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
            <button @click="checkSlots()" :disabled="!date || loading"
                class="px-5 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition disabled:opacity-50">
                <span x-show="!loading">Check</span>
                <span x-show="loading">Loading...</span>
            </button>
        </div>

        <div x-show="slots !== null" class="mt-4">
            <p class="text-sm font-medium text-slate-700 mb-2" x-text="'Available slots for ' + dateLabel"></p>
            <template x-if="slots && slots.length > 0">
                <div class="flex flex-wrap gap-2">
                    <template x-for="slot in slots" :key="slot">
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm" x-text="slot"></span>
                    </template>
                </div>
            </template>
            <template x-if="slots && slots.length === 0">
                <p class="text-sm text-slate-500">No available slots for this date.</p>
            </template>
        </div>
    </div>

    <script>
        function slotChecker() {
            return {
                date: '',
                slots: null,
                dateLabel: '',
                loading: false,
                async checkSlots() {
                    this.loading = true;
                    try {
                        const res = await fetch(`{{ route('patient.doctors.slots', $doctor) }}?date=${this.date}`);
                        const data = await res.json();
                        this.slots = data.slots;
                        this.dateLabel = data.date;
                    } catch (e) {
                        this.slots = [];
                    }
                    this.loading = false;
                }
            }
        }
    </script>
</x-patient-layout>
