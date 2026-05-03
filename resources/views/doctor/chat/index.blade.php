<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Messages</h2>
        <p class="text-sm text-slate-500 mt-1">Patient conversations.</p>
    </x-slot>

    <div class="space-y-3">
        @forelse($conversations as $conv)
            <a href="{{ route('doctor.chat.show', $conv) }}"
               class="block rounded-2xl border border-slate-200 bg-white p-5 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-700 flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                        {{ strtoupper(substr($conv->patient->first_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900">{{ $conv->patient->full_name }}</p>
                        @if($conv->latestMessage)
                            <p class="text-sm text-slate-500 truncate">
                                {{ $conv->latestMessage->sender_type === 'doctor' ? 'You: ' : '' }}{{ $conv->latestMessage->body ?? 'Sent a file' }}
                            </p>
                        @else
                            <p class="text-sm text-slate-400">No messages yet</p>
                        @endif
                    </div>
                    @if($conv->latestMessage)
                        <p class="text-xs text-slate-400 flex-shrink-0">{{ $conv->latestMessage->created_at->diffForHumans() }}</p>
                    @endif
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center">
                <p class="text-slate-500">No conversations yet.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
