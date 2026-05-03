<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Notifications</h2>
        <p class="text-sm text-slate-500 mt-1">Stay updated on your appointments.</p>
    </x-slot>

    <div class="space-y-3">
        @forelse($notifications as $notif)
            <div class="rounded-2xl border {{ $notif->is_read ? 'border-slate-200 bg-white' : 'border-emerald-200 bg-emerald-50' }} p-5 flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-slate-900">{{ $notif->title }}</p>
                        @if(!$notif->is_read)
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600 mt-1">{{ $notif->message }}</p>
                    <p class="text-xs text-slate-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                @if(!$notif->is_read)
                    <form method="POST" action="{{ route('patient.notifications.read', $notif) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs text-emerald-700 font-semibold hover:underline whitespace-nowrap">
                            Mark read
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center">
                <p class="text-slate-500">No notifications yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</x-patient-layout>
