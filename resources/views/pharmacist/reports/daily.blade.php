<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Daily Sales</h2>
                <p class="text-sm text-slate-500 mt-1">Pharmacy sales for a selected day.</p>
            </div>
            <a href="{{ route('pharmacist.reports.monthly') }}"
               class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                Monthly Report
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-6 space-y-4">

            <form method="GET" action="{{ route('pharmacist.reports.daily') }}" class="flex items-end gap-2">
                <div>
                    <label for="date" class="text-sm font-medium text-slate-700">Date</label>
                    <input id="date" name="date" type="date" value="{{ $date->format('Y-m-d') }}"
                        class="mt-1 block rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0 text-sm" />
                </div>
                <button type="submit"
                    class="px-5 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    View
                </button>
            </form>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Sales — {{ $date->format('M d, Y') }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">${{ number_format($total, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Transactions</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $dispenses->count() }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                @if($dispenses->count() > 0)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold text-slate-600">
                                <th class="px-6 py-3">Invoice</th>
                                <th class="px-6 py-3">Patient</th>
                                <th class="px-6 py-3">Items</th>
                                <th class="px-6 py-3">Time</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($dispenses as $dispense)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                        <a href="{{ route('pharmacist.dispenses.invoice', $dispense) }}" class="text-emerald-700 hover:underline">#{{ $dispense->id }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $dispense->patient->full_name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $dispense->items->sum('quantity') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $dispense->created_at->format('h:i A') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900 text-right">${{ number_format($dispense->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">No sales</p>
                        <p class="mt-1 text-sm text-slate-500">No medicines were dispensed on this day.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
