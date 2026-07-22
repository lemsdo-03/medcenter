<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Prescriptions</h2>
                <p class="text-sm text-slate-500 mt-1">Prescriptions received from doctors.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pharmacist.prescriptions.index') }}"
                   class="px-4 py-2 rounded-2xl text-sm font-semibold {{ !request('status') ? 'bg-slate-900 text-white' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}">All</a>
                <a href="{{ route('pharmacist.prescriptions.index', ['status' => 'pending']) }}"
                   class="px-4 py-2 rounded-2xl text-sm font-semibold {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}">Pending</a>
                <a href="{{ route('pharmacist.prescriptions.index', ['status' => 'dispensed']) }}"
                   class="px-4 py-2 rounded-2xl text-sm font-semibold {{ request('status') === 'dispensed' ? 'bg-emerald-700 text-white' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }}">Dispensed</a>
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

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                @if($prescriptions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold text-slate-600">
                                    <th class="px-6 py-3">Patient</th>
                                    <th class="px-6 py-3">Doctor</th>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($prescriptions as $prescription)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $prescription->patient->full_name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">Dr. {{ $prescription->doctor->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $prescription->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($prescription->dispense)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Dispensed</span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('pharmacist.prescriptions.show', $prescription) }}"
                                               class="text-sm text-emerald-700 font-semibold hover:underline">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">No prescriptions</p>
                        <p class="mt-1 text-sm text-slate-500">No prescriptions match this filter.</p>
                    </div>
                @endif
            </div>

            <div>
                {{ $prescriptions->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
