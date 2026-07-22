<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Most Requested Medicines</h2>
                <p class="text-sm text-slate-500 mt-1">Monthly statistics of dispensed medicines.</p>
            </div>
            <a href="{{ route('pharmacist.reports.daily') }}"
               class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                Daily Report
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-6 space-y-4">

            <form method="GET" action="{{ route('pharmacist.reports.monthly') }}" class="flex items-end gap-2">
                <div>
                    <label for="month" class="text-sm font-medium text-slate-700">Month</label>
                    <input id="month" name="month" type="month" value="{{ $month->format('Y-m') }}"
                        class="mt-1 block rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0 text-sm" />
                </div>
                <button type="submit"
                    class="px-5 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    View
                </button>
            </form>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $month->format('F Y') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">Ranked by quantity dispensed.</p>
                </div>

                @if($medicines->count() > 0)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold text-slate-600">
                                <th class="px-6 py-3">#</th>
                                <th class="px-6 py-3">Medicine</th>
                                <th class="px-6 py-3">Quantity Dispensed</th>
                                <th class="px-6 py-3 text-right">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($medicines as $i => $medicine)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-500">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $medicine->medicine_name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $medicine->total_quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900 text-right">${{ number_format($medicine->total_sales, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">No data</p>
                        <p class="mt-1 text-sm text-slate-500">No medicines were dispensed in this month.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
