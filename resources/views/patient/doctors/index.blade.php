<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Doctors</h2>
        <p class="text-sm text-slate-500 mt-1">Browse our doctors and book appointments.</p>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($doctors as $doctor)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-700 flex items-center justify-center text-white font-semibold text-lg">
                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Dr. {{ $doctor->name }}</h3>
                        @if($doctor->specialty)
                            <p class="text-sm text-emerald-700">{{ $doctor->specialty }}</p>
                        @else
                            <p class="text-sm text-slate-500">General Practice</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('patient.doctors.show', $doctor) }}"
                       class="flex-1 text-center px-4 py-2 rounded-2xl border border-slate-200 text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        View Availability
                    </a>
                    <a href="{{ route('patient.appointments.create', $doctor) }}"
                       class="flex-1 text-center px-4 py-2 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                        Book
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-8 text-center">
                <p class="text-slate-500">No doctors available at the moment.</p>
            </div>
        @endforelse
    </div>
</x-patient-layout>
