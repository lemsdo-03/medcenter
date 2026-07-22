<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">Add Category</h2>
            <p class="text-sm text-slate-500 mt-1">Create a new medicine category.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('pharmacist.categories.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="text-sm font-medium text-slate-700">Category Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <a href="{{ route('pharmacist.categories.index') }}"
                           class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
