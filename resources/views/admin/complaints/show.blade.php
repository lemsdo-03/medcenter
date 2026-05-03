<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ $rating->type === 'complaint' ? 'Complaint' : 'Rating' }} Details
                </h2>
                <p class="text-sm text-slate-500 mt-1">Submitted {{ $rating->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <a href="{{ route('admin.complaints.index') }}"
               class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-500">Type</p>
                    @if($rating->type === 'complaint')
                        <span class="text-xs px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">Complaint</span>
                    @else
                        <span class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Rating</span>
                    @endif
                </div>
                @if($rating->rating)
                    <div>
                        <p class="text-xs text-slate-500">Rating</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $rating->rating }}/5</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-500">Patient</p>
                    <p class="font-medium text-slate-900">{{ $rating->patient->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Doctor</p>
                    <p class="font-medium text-slate-900">Dr. {{ $rating->doctor->name }}</p>
                </div>
            </div>

            @if($rating->appointment)
                <div>
                    <p class="text-xs text-slate-500">Appointment Date</p>
                    <p class="text-slate-700">{{ $rating->appointment->appointment_date->format('M d, Y \a\t h:i A') }}</p>
                </div>
            @endif

            <div>
                <p class="text-xs text-slate-500 mb-2">Comment</p>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-slate-700 whitespace-pre-wrap">{{ $rating->comment }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
