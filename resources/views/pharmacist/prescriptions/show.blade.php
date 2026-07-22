<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Prescription Details</h2>
                <p class="text-sm text-slate-500 mt-1">Received {{ $prescription->created_at->format('M d, Y') }}</p>
            </div>
            <a href="{{ route('pharmacist.prescriptions.index') }}"
               class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-6 space-y-4">

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
                    <span class="font-semibold">Error:</span> {{ session('error') }}
                </div>
            @endif

            {{-- FR-47: prescription owner (patient) information --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500">Patient</p>
                        <p class="font-medium text-slate-900">{{ $prescription->patient->full_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Doctor</p>
                        <p class="font-medium text-slate-900">Dr. {{ $prescription->doctor->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Phone</p>
                        <p class="font-medium text-slate-900">{{ $prescription->patient->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Allergies</p>
                        <p class="font-medium {{ !empty($prescription->patient->allergies) ? 'text-rose-700' : 'text-slate-900' }}">
                            {{ $prescription->patient->allergies ?: 'None recorded' }}
                        </p>
                    </div>
                </div>

                @if($prescription->diagnosis)
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Diagnosis</p>
                        <p class="text-slate-700">{{ $prescription->diagnosis }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-slate-500 mb-2">Prescription</p>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-slate-700 whitespace-pre-wrap">{{ $prescription->prescription }}</p>
                    </div>
                </div>

                {{-- FR-43: dispense action / status --}}
                <div class="pt-2 flex items-center justify-end gap-2">
                    @if($prescription->dispense)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Dispensed</span>
                        <a href="{{ route('pharmacist.dispenses.invoice', $prescription->dispense) }}"
                           class="px-5 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                            View Invoice
                        </a>
                    @else
                        <a href="{{ route('pharmacist.dispenses.create', $prescription) }}"
                           class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Dispense Medicine
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
