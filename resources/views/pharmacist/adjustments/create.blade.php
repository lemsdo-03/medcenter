<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">Record Damage / Return</h2>
            <p class="text-sm text-slate-500 mt-1">Logging a record reduces the medicine's stock.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                @if(session('error'))
                    <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
                        <span class="font-semibold">Error:</span> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('pharmacist.adjustments.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="medicine_id" class="text-sm font-medium text-slate-700">Medicine</label>
                        <select id="medicine_id" name="medicine_id" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            <option value="">— Select medicine —</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}"
                                    {{ (string) old('medicine_id', $selected) === (string) $medicine->id ? 'selected' : '' }}>
                                    {{ $medicine->name }} ({{ $medicine->code }}) — {{ $medicine->quantity }} in stock
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('medicine_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="type" class="text-sm font-medium text-slate-700">Type</label>
                        <select id="type" name="type" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            <option value="damaged" {{ old('type') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="returned" {{ old('type') === 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <label for="quantity" class="text-sm font-medium text-slate-700">Quantity</label>
                        <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <div>
                        <label for="reason" class="text-sm font-medium text-slate-700">Reason (optional)</label>
                        <input id="reason" name="reason" type="text" value="{{ old('reason') }}"
                            placeholder="e.g. broken packaging, expired, patient return"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <a href="{{ route('pharmacist.adjustments.index') }}"
                           class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Save Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
