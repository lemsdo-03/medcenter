<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Medicine Categories</h2>
                <p class="text-sm text-slate-500 mt-1">Organise medicines into categories.</p>
            </div>
            <a href="{{ route('pharmacist.categories.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-6 space-y-4">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
                    <span class="font-semibold">Error:</span> {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                @if($categories->count() > 0)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold text-slate-600">
                                <th class="px-6 py-3">Category</th>
                                <th class="px-6 py-3">Medicines</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($categories as $category)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $category->medicines_count }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('pharmacist.categories.edit', $category) }}"
                                               class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('pharmacist.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-10 text-center">
                        <p class="text-lg font-semibold text-slate-900">No categories yet</p>
                        <p class="mt-1 text-sm text-slate-500">Add your first medicine category.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
