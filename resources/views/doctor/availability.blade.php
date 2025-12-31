<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Set Availability') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Add your weekly time slots so reception can book appointments correctly.
                </p>
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
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Weekly Availability</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Tip: Use multiple slots if you have breaks (example: 09:00–12:00 and 14:00–17:00).
                            </p>
                        </div>

                        <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $availabilities->count() }} saved slot{{ $availabilities->count() === 1 ? '' : 's' }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('doctor.availability.store') }}" id="availabilityForm" class="p-6">
                    @csrf

                    <div id="availabilityContainer" class="space-y-3">
                        @if($availabilities->count() > 0)
                            @foreach($availabilities as $index => $availability)
                                <div class="availability-row rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Day</label>
                                            <select name="availabilities[{{ $index }}][day_of_week]" required
                                                    class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                                <option value="monday" {{ $availability->day_of_week == 'monday' ? 'selected' : '' }}>Monday</option>
                                                <option value="tuesday" {{ $availability->day_of_week == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                <option value="wednesday" {{ $availability->day_of_week == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                <option value="thursday" {{ $availability->day_of_week == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                                <option value="friday" {{ $availability->day_of_week == 'friday' ? 'selected' : '' }}>Friday</option>
                                                <option value="saturday" {{ $availability->day_of_week == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                                <option value="sunday" {{ $availability->day_of_week == 'sunday' ? 'selected' : '' }}>Sunday</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                                            <input type="time"
                                                   name="availabilities[{{ $index }}][start_time]"
                                                   value="{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}"
                                                   required
                                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                                            <input type="time"
                                                   name="availabilities[{{ $index }}][end_time]"
                                                   value="{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}"
                                                   required
                                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                        </div>

                                        <div class="flex md:justify-end">
                                            <button type="button"
                                                    class="js-remove-row w-full md:w-auto px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="availability-row rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Day</label>
                                        <select name="availabilities[0][day_of_week]" required
                                                class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                            <option value="sunday">Sunday</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                                        <input type="time" name="availabilities[0][start_time]" required
                                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                                        <input type="time" name="availabilities[0][end_time]" required
                                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                    </div>

                                    <div class="flex md:justify-end">
                                        <button type="button"
                                                class="js-remove-row w-full md:w-auto px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <button type="button" id="addRow"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            + Add Time Slot
                        </button>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Save Availability
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('availabilityContainer');
            const addBtn = document.getElementById('addRow');

            let rowIndex = {{ $availabilities->count() > 0 ? $availabilities->count() : 1 }};

            // remove (event delegation)
            container.addEventListener('click', (e) => {
                const btn = e.target.closest('.js-remove-row');
                if (!btn) return;

                const row = btn.closest('.availability-row');
                if (!row) return;

                row.remove();
            });

            addBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'availability-row rounded-2xl border border-slate-200 bg-slate-50 p-4';
                row.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Day</label>
                            <select name="availabilities[${rowIndex}][day_of_week]" required
                                    class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                                <option value="saturday">Saturday</option>
                                <option value="sunday">Sunday</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                            <input type="time" name="availabilities[${rowIndex}][start_time]" required
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                            <input type="time" name="availabilities[${rowIndex}][end_time]" required
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        <div class="flex md:justify-end">
                            <button type="button"
                                    class="js-remove-row w-full md:w-auto px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                Remove
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(row);
                rowIndex++;
            });
        });
    </script>
</x-app-layout>
