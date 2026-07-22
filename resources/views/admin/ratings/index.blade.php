<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Ratings</h2>
                <p class="text-sm text-slate-500 mt-1">Patient ratings submitted about doctors.</p>
            </div>
            <a href="{{ route('admin.complaints.index') }}"
               class="px-4 py-2 rounded-2xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50">
                View Complaints
            </a>
        </div>
    </x-slot>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Patient</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Doctor</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Rating</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-700">Date</th>
                    <th class="text-right px-6 py-3 font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ratings as $r)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="px-6 py-4 text-slate-900">{{ $r->patient->full_name }}</td>
                        <td class="px-6 py-4 text-slate-700">Dr. {{ $r->doctor->name }}</td>
                        <td class="px-6 py-4 text-slate-700">
                            @if($r->rating)
                                {{ $r->rating }}/5
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $r->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.ratings.show', $r) }}" class="text-sm text-emerald-700 font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No ratings submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ratings->withQueryString()->links() }}
    </div>
</x-app-layout>
