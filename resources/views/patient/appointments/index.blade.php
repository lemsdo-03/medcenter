<x-patient-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">My Appointments</h2>
                <p class="text-sm text-slate-500 mt-1">View and manage your appointments.</p>
            </div>
            <a href="{{ route('patient.doctors.index') }}"
               class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                Book New
            </a>
        </div>
    </x-slot>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Doctor</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Date & Time</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Status</th>
                    <th class="text-right px-6 py-3 font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $apt)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">Dr. {{ $apt->doctor->name }}</p>
                            @if($apt->doctor->specialty)
                                <p class="text-xs text-slate-500">{{ $apt->doctor->specialty }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            {{ $apt->appointment_date->format('M d, Y') }}
                            <span class="text-slate-400">at</span>
                            {{ $apt->appointment_date->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($apt->status === 'scheduled')
                                <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Scheduled</span>
                            @elseif($apt->status === 'completed')
                                <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">Completed</span>
                            @else
                                <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($apt->status === 'scheduled')
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('patient.appointments.edit', $apt) }}"
                                       class="text-sm text-emerald-700 font-semibold hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('patient.appointments.cancel', $apt) }}"
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-rose-700 font-semibold hover:underline">Cancel</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            No appointments yet.
                            <a href="{{ route('patient.doctors.index') }}" class="text-emerald-700 font-semibold hover:underline">Book your first appointment</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</x-patient-layout>
