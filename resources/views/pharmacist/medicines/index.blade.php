<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Inventory</h2>
                <p class="text-sm text-slate-500 mt-1">Available medicines and stock levels.</p>
            </div>
            <a href="{{ route('pharmacist.medicines.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                Add Medicine
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif

            {{-- FR-41: search by name or code --}}
            <form method="GET" action="{{ route('pharmacist.medicines.index') }}" class="flex gap-2">
                <input name="search" type="text" value="{{ request('search') }}"
                    placeholder="Search by name or code..."
                    class="flex-1 rounded-2xl border-slate-200 bg-white focus:border-emerald-300 focus:ring-0 text-sm" />
                <button type="submit"
                    class="px-5 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('pharmacist.medicines.index') }}"
                       class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                        Clear
                    </a>
                @endif
            </form>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                @if($medicines->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold text-slate-600">
                                    <th class="px-6 py-3">Medicine</th>
                                    <th class="px-6 py-3">Code</th>
                                    <th class="px-6 py-3">Category</th>
                                    <th class="px-6 py-3">Quantity</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">Expiry</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($medicines as $medicine)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $medicine->name }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $medicine->code }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-700">{{ $medicine->category->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($medicine->isLowStock())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                    {{ $medicine->quantity }} (low)
                                                </span>
                                            @else
                                                <span class="text-slate-800">{{ $medicine->quantity }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700">${{ number_format($medicine->price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($medicine->expiry_date)
                                                <span class="{{ $medicine->isExpired() ? 'text-rose-700 font-semibold' : 'text-slate-700' }}">
                                                    {{ $medicine->expiry_date->format('M d, Y') }}{{ $medicine->isExpired() ? ' (expired)' : '' }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('pharmacist.medicines.edit', $medicine) }}"
                                               class="text-sm text-emerald-700 font-semibold hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">No medicines found</p>
                        <p class="mt-1 text-sm text-slate-500">{{ request('search') ? 'Try a different search.' : 'Add your first medicine.' }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
