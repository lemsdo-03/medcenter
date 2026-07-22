<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Invoice #{{ $dispense->id }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $dispense->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pharmacist.prescriptions.index') }}"
                   class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                    Back
                </a>
                <button type="button" onclick="window.print()"
                    class="px-4 py-2 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                    Print Invoice
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-6">
            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif

            {{-- FR-48: printable invoice --}}
            <div id="invoice" class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 pb-5">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">MedCenter Pharmacy</h3>
                        <p class="text-sm text-slate-500 mt-1">Medicine Invoice</p>
                    </div>
                    <div class="text-right text-sm text-slate-600">
                        <p>Invoice #{{ $dispense->id }}</p>
                        <p>{{ $dispense->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 py-5 text-sm">
                    <div>
                        <p class="text-slate-500">Patient</p>
                        <p class="font-semibold text-slate-900">{{ $dispense->patient->full_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Dispensed By</p>
                        <p class="font-semibold text-slate-900">{{ $dispense->pharmacist->name ?? '—' }}</p>
                    </div>
                    @if($dispense->medicalNote && $dispense->medicalNote->doctor)
                        <div>
                            <p class="text-slate-500">Prescribed By</p>
                            <p class="font-semibold text-slate-900">Dr. {{ $dispense->medicalNote->doctor->name }}</p>
                        </div>
                    @endif
                </div>

                <table class="min-w-full divide-y divide-slate-200 border-t border-slate-200">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-600">
                            <th class="py-3">Medicine</th>
                            <th class="py-3 text-center">Qty</th>
                            <th class="py-3 text-right">Unit Price</th>
                            <th class="py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($dispense->items as $item)
                            <tr>
                                <td class="py-3 text-sm text-slate-900">{{ $item->medicine_name }}</td>
                                <td class="py-3 text-sm text-slate-700 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-sm text-slate-700 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 text-sm text-slate-900 text-right">${{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-300">
                            <td colspan="3" class="py-3 text-right text-sm font-semibold text-slate-700">Total</td>
                            <td class="py-3 text-right text-lg font-bold text-slate-900">${{ number_format($dispense->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <p class="mt-6 text-center text-xs text-slate-400">Thank you for choosing MedCenter Pharmacy.</p>
            </div>
        </div>
    </div>
</x-app-layout>
