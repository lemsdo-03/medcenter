<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">Pharmacy Dashboard</h2>
            <p class="text-sm text-slate-500 mt-1">Inventory overview and stock alerts.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-6">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Medicines</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['medicines'] }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Categories</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['categories'] }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pending Prescriptions</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['pending'] }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Today's Sales</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">${{ number_format($stats['todaySales'], 2) }}</p>
                </div>
            </div>

            {{-- FR-44: Low stock alert --}}
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Low Stock Alert</h3>
                    <p class="text-sm text-slate-500 mt-1">Medicines at or below their minimum quantity.</p>
                </div>

                @if($lowStock->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold text-slate-600">
                                    <th class="px-6 py-3">Medicine</th>
                                    <th class="px-6 py-3">Code</th>
                                    <th class="px-6 py-3">Category</th>
                                    <th class="px-6 py-3">Quantity</th>
                                    <th class="px-6 py-3">Min</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($lowStock as $medicine)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $medicine->name }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $medicine->code }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $medicine->category->name ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                {{ $medicine->quantity }} left
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $medicine->min_quantity }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('pharmacist.medicines.edit', $medicine) }}" class="text-sm text-emerald-700 font-semibold hover:underline">Restock</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">All stock levels are healthy</p>
                        <p class="mt-1 text-sm text-slate-500">No medicines are below their minimum quantity.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
