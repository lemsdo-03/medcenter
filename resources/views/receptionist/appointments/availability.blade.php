<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Check Doctor Availability') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Select a doctor and date to see available time slots.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Search</h3>

                    <form method="GET" action="{{ route('receptionist.appointments.availability') }}"
                          class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Doctor</label>
                            <select name="doctor_id" required
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ $date }}" required
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Check Availability
                        </button>
                    </form>
                </div>

                <div class="p-6">
                    @if($selectedDoctor)
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <h4 class="text-base font-semibold text-slate-900">
                                    {{ $selectedDoctor->name }}
                                </h4>
                                <p class="text-sm text-slate-500 mt-1">
                                    Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                </p>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ \Carbon\Carbon::parse($date)->format('l') }}
                            </span>
                        </div>

                        <div class="mt-6 space-y-4">
                            @if($availabilities->count() > 0)
                                @php $shownAny = false; @endphp

                                @foreach($availabilities as $availability)
                                    @if(count($availability->available_slots) > 0)
                                        @php $shownAny = true; @endphp

                                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                                <h5 class="text-sm font-semibold text-slate-900">
                                                    {{ ucfirst($availability->day_of_week) }}
                                                </h5>

                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                                </span>
                                            </div>

                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($availability->available_slots as $slot)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                                        {{ $slot }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                @if(!$shownAny)
                                    <div class="text-center py-10">
                                        <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                        <p class="mt-4 text-lg font-semibold text-slate-900">No slots available</p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            Availability exists, but all time slots are booked for this date.
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-10">
                                    <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                                    <p class="mt-4 text-lg font-semibold text-slate-900">No availability set</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        This doctor has no schedule on {{ \Carbon\Carbon::parse($date)->format('l') }}.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                            <p class="mt-4 text-lg font-semibold text-slate-900">Select a doctor and date</p>
                            <p class="mt-1 text-sm text-slate-500">Then click “Check Availability”.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
