<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('doctor.chat.index') }}" class="text-slate-500 hover:text-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">{{ $conversation->patient->full_name }}</h2>
                <p class="text-sm text-slate-500">Patient conversation</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto" x-data="doctorChat()" x-init="init()">
        {{-- Messages --}}
        <div id="chat-messages" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm mb-4 max-h-[500px] overflow-y-auto space-y-4">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_type === 'doctor' ? 'justify-end' : 'justify-start' }}" data-msg-id="{{ $msg->id }}">
                    <div class="max-w-[70%] {{ $msg->sender_type === 'doctor' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-900' }} rounded-2xl px-4 py-3">
                        @if($msg->body)
                            <p class="text-sm">{{ $msg->body }}</p>
                        @endif
                        @if($msg->file_path)
                            <div class="mt-2">
                                @if(str_starts_with($msg->file_type, 'image/'))
                                    <img src="{{ asset('storage/' . $msg->file_path) }}" alt="{{ $msg->file_name }}" class="rounded-xl max-w-full max-h-48">
                                @else
                                    <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-3 py-2 rounded-xl {{ $msg->sender_type === 'doctor' ? 'bg-emerald-800 text-white' : 'bg-slate-200 text-slate-800' }} text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $msg->file_name }}
                                    </a>
                                @endif
                            </div>
                        @endif
                        <p class="text-xs {{ $msg->sender_type === 'doctor' ? 'text-emerald-200' : 'text-slate-400' }} mt-1">{{ $msg->created_at->format('h:i A') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Send Reply --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="POST" action="{{ route('doctor.chat.reply', $conversation) }}" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <textarea name="body" rows="2" placeholder="Type a reply..."
                        class="block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0 text-sm resize-none"></textarea>
                </div>
                <button type="submit" class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                    Send
                </button>
            </form>

            <form method="POST" action="{{ route('doctor.chat.file', $conversation) }}" enctype="multipart/form-data" class="mt-3 flex items-center gap-3">
                @csrf
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx"
                    class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-2xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" />
                <button type="submit" class="px-4 py-2 rounded-2xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                    Send File
                </button>
            </form>
        </div>
    </div>

    <script>
        function playNotifSound() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            osc.type = 'sine';
            gain.gain.value = 0.15;
            osc.start();
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            osc.stop(ctx.currentTime + 0.3);
        }

        function doctorChat() {
            return {
                lastMsgId: {{ $messages->last()?->id ?? 0 }},
                init() {
                    const el = document.getElementById('chat-messages');
                    el.scrollTop = el.scrollHeight;
                    this.pollMessages();
                },
                async pollMessages() {
                    try {
                        const res = await fetch(`{{ route('doctor.chat.poll', $conversation) }}?after_id=${this.lastMsgId}`);
                        const data = await res.json();
                        if (data.messages.length > 0) {
                            const container = document.getElementById('chat-messages');
                            let hasNewFromOther = false;
                            data.messages.forEach(msg => {
                                if (container.querySelector(`[data-msg-id="${msg.id}"]`)) return;
                                if (msg.sender_type === 'patient') hasNewFromOther = true;
                                const isDoctor = msg.sender_type === 'doctor';
                                let html = `<div class="flex ${isDoctor ? 'justify-end' : 'justify-start'}" data-msg-id="${msg.id}">
                                    <div class="max-w-[70%] ${isDoctor ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-900'} rounded-2xl px-4 py-3">`;
                                if (msg.body) html += `<p class="text-sm">${msg.body}</p>`;
                                if (msg.file_path) {
                                    if (msg.file_type && msg.file_type.startsWith('image/')) {
                                        html += `<img src="${msg.file_path}" class="rounded-xl max-w-full max-h-48 mt-2">`;
                                    } else {
                                        html += `<a href="${msg.file_path}" target="_blank" class="mt-2 inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium ${isDoctor ? 'bg-emerald-800 text-white' : 'bg-slate-200 text-slate-800'}">${msg.file_name}</a>`;
                                    }
                                }
                                html += `<p class="text-xs ${isDoctor ? 'text-emerald-200' : 'text-slate-400'} mt-1">${msg.created_at}</p></div></div>`;
                                container.insertAdjacentHTML('beforeend', html);
                                this.lastMsgId = msg.id;
                            });
                            if (hasNewFromOther) playNotifSound();
                            container.scrollTop = container.scrollHeight;
                        }
                    } catch (e) {}
                    setTimeout(() => this.pollMessages(), 3000);
                }
            }
        }
    </script>
</x-app-layout>
