<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">Edit Medicine</h2>
            <p class="text-sm text-slate-500 mt-1">Update medicine information and stock.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('pharmacist.medicines.update', $medicine) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="text-sm font-medium text-slate-700">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $medicine->name) }}" required
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="code" class="text-sm font-medium text-slate-700">Code</label>
                            <input id="code" name="code" type="text" value="{{ old('code', $medicine->code) }}" required
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="category_id" class="text-sm font-medium text-slate-700">Category</label>
                        <select id="category_id" name="category_id"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            <option value="">— None —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="quantity" class="text-sm font-medium text-slate-700">Quantity</label>
                            <input id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity', $medicine->quantity) }}" required
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>
                        <div>
                            <label for="min_quantity" class="text-sm font-medium text-slate-700">Min Quantity</label>
                            <input id="min_quantity" name="min_quantity" type="number" min="0" value="{{ old('min_quantity', $medicine->min_quantity) }}" required
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            <x-input-error :messages="$errors->get('min_quantity')" class="mt-2" />
                        </div>
                        <div>
                            <label for="price" class="text-sm font-medium text-slate-700">Price</label>
                            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $medicine->price) }}" required
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="expiry_date" class="text-sm font-medium text-slate-700">Expiry Date</label>
                        <input id="expiry_date" name="expiry_date" type="date"
                            value="{{ old('expiry_date', optional($medicine->expiry_date)->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <a href="{{ route('pharmacist.medicines.index') }}"
                           class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
