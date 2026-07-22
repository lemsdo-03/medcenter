<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Assistant</h2>
        <p class="text-sm text-slate-500 mt-1">Ask about hours, location, doctors, booking and more.</p>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div x-data="chatbot()" x-init="init()"
             class="rounded-3xl border border-slate-200 bg-white shadow-sm flex flex-col" style="height: 70vh;">

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-4" x-ref="messages">
                <template x-for="(msg, i) in messages" :key="i">
                    <div>
                        {{-- bubble --}}
                        <div :class="msg.from === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.from === 'user'
                                    ? 'bg-emerald-700 text-white rounded-2xl rounded-br-sm px-4 py-2 max-w-[80%] text-sm whitespace-pre-line'
                                    : 'bg-slate-100 text-slate-800 rounded-2xl rounded-bl-sm px-4 py-2 max-w-[80%] text-sm whitespace-pre-line'"
                                 x-text="msg.text"></div>
                        </div>

                        {{-- link buttons (e.g. map, chat) --}}
                        <template x-if="msg.links && msg.links.length">
                            <div class="mt-2 flex flex-wrap gap-2">
                                <template x-for="(link, li) in msg.links" :key="li">
                                    <a :href="link.url" target="_blank"
                                       class="px-3 py-1.5 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-semibold hover:bg-emerald-100 transition"
                                       x-text="link.label"></a>
                                </template>
                            </div>
                        </template>

                        {{-- quick-reply option buttons --}}
                        <template x-if="msg.options && msg.options.length && i === messages.length - 1">
                            <div class="mt-2 flex flex-wrap gap-2">
                                <template x-for="(opt, oi) in msg.options" :key="oi">
                                    <button type="button" @click="sendTopic(opt.topic, opt.label)"
                                        class="px-3 py-1.5 rounded-2xl border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 transition"
                                        x-text="opt.label"></button>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <div x-show="typing" class="flex justify-start">
                    <div class="bg-slate-100 text-slate-400 rounded-2xl px-4 py-2 text-sm">typing…</div>
                </div>
            </div>

            {{-- Input --}}
            <form @submit.prevent="sendText()" class="border-t border-slate-200 p-3 flex gap-2">
                <input x-model="input" type="text" placeholder="Type a message…"
                    class="flex-1 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0 text-sm" />
                <button type="submit"
                    class="px-5 py-2.5 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                    Send
                </button>
            </form>
        </div>
    </div>

    <script>
        function chatbot() {
            return {
                messages: [],
                input: '',
                typing: false,

                init() {
                    // FR-52: automatic greeting message when the chatbot opens
                    this.messages.push({
                        from: 'bot',
                        text: @json($greeting),
                        options: @json($menu),
                        links: [],
                    });
                    this.scroll();
                },

                async sendTopic(topic, label) {
                    this.messages.push({ from: 'user', text: label });
                    await this.ask({ topic: topic });
                },

                async sendText() {
                    const text = this.input.trim();
                    if (!text) return;
                    this.input = '';
                    this.messages.push({ from: 'user', text: text });
                    await this.ask({ message: text });
                },

                async ask(payload) {
                    this.typing = true;
                    this.scroll();
                    try {
                        const res = await fetch("{{ route('patient.chatbot.reply') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(payload),
                        });
                        const data = await res.json();
                        this.messages.push({
                            from: 'bot',
                            text: data.reply,
                            options: data.options || [],
                            links: data.links || [],
                        });
                    } catch (e) {
                        this.messages.push({ from: 'bot', text: 'Sorry, something went wrong. Please try again.', options: [], links: [] });
                    } finally {
                        this.typing = false;
                        this.scroll();
                    }
                },

                scroll() {
                    this.$nextTick(() => {
                        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                    });
                },
            };
        }
    </script>
</x-patient-layout>
