<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Dispense Medicine</h2>
                <p class="text-sm text-slate-500 mt-1">For {{ $prescription->patient->full_name ?? 'patient' }}.</p>
            </div>
            <a href="{{ route('pharmacist.prescriptions.show', $prescription) }}"
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

            {{-- Doctor's prescription for reference --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs text-slate-500 mb-2">Doctor's Prescription</p>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-slate-700 whitespace-pre-wrap">{{ $prescription->prescription }}</p>
                </div>
            </div>

            @if($medicines->count() === 0)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900 text-sm">
                    No medicines exist in the inventory yet. Add medicines before dispensing.
                </div>
            @else
            {{-- FR-43: select medicines + quantities to dispense against this prescription --}}
            <form method="POST" action="{{ route('pharmacist.dispenses.store', $prescription) }}"
                  x-data="{ rows: [{ medicine_id: '', quantity: 1 }] }" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                @csrf

                <div class="space-y-3">
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-700" x-show="index === 0">Medicine</label>
                                <select :name="'items[' + index + '][medicine_id]'" x-model="row.medicine_id" required
                                    class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                                    <option value="">— Select medicine —</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}">
                                            {{ $medicine->name }} ({{ $medicine->code }}) — {{ $medicine->quantity }} in stock, ${{ number_format($medicine->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-28">
                                <label class="text-sm font-medium text-slate-700" x-show="index === 0">Qty</label>
                                <input type="number" min="1" :name="'items[' + index + '][quantity]'" x-model="row.quantity" required
                                    class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            </div>
                            <button type="button" @click="rows.splice(index, 1)" x-show="rows.length > 1"
                                class="px-3 py-2.5 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                &times;
                            </button>
                        </div>
                    </template>
                </div>

                <button type="button" @click="rows.push({ medicine_id: '', quantity: 1 })"
                    class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                    + Add another medicine
                </button>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-200">
                    <a href="{{ route('pharmacist.prescriptions.show', $prescription) }}"
                       class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                        Dispense &amp; Generate Invoice
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>
</x-app-layout>
