{{-- FR-72: Night Mode. Self-contained floating toggle + styles + script (persisted in localStorage). --}}
<style>
    /* Night Mode overrides for the common Tailwind utilities used across the app */
    html.night-mode body { background-color: #0f172a !important; color: #e2e8f0 !important; }

    html.night-mode .bg-white { background-color: #1e293b !important; }
    html.night-mode .bg-slate-50 { background-color: #0f172a !important; }
    html.night-mode .bg-slate-100 { background-color: #334155 !important; }

    html.night-mode .text-slate-900 { color: #f1f5f9 !important; }
    html.night-mode .text-slate-800 { color: #e2e8f0 !important; }
    html.night-mode .text-slate-700 { color: #cbd5e1 !important; }
    html.night-mode .text-slate-600 { color: #94a3b8 !important; }
    html.night-mode .text-slate-500 { color: #94a3b8 !important; }

    html.night-mode .border-slate-200 { border-color: #334155 !important; }
    html.night-mode .border-slate-300 { border-color: #475569 !important; }
    html.night-mode .divide-slate-200 > :not([hidden]) ~ :not([hidden]) { border-color: #334155 !important; }

    html.night-mode .shadow-sm, html.night-mode .shadow-xl { box-shadow: none !important; }

    /* keep form fields readable */
    html.night-mode input, html.night-mode select, html.night-mode textarea {
        background-color: #0f172a !important;
        color: #e2e8f0 !important;
    }

    #nightModeToggle {
        position: fixed; right: 1.25rem; bottom: 1.25rem; z-index: 60;
        height: 3rem; width: 3rem; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        background-color: #0f172a; color: #fff; border: 1px solid #334155;
        box-shadow: 0 10px 25px rgba(0,0,0,.15); cursor: pointer; transition: transform .15s;
    }
    #nightModeToggle:hover { transform: scale(1.05); }
    html.night-mode #nightModeToggle { background-color: #facc15; color: #0f172a; }
</style>

<button id="nightModeToggle" type="button" title="Toggle Night Mode" aria-label="Toggle Night Mode">
    {{-- moon icon (light mode) --}}
    <svg id="nightModeMoon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
    {{-- sun icon (night mode) --}}
    <svg id="nightModeSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
</button>

<script>
    (function () {
        var root = document.documentElement;
        var moon = document.getElementById('nightModeMoon');
        var sun = document.getElementById('nightModeSun');

        function applyIcons() {
            var on = root.classList.contains('night-mode');
            moon.classList.toggle('hidden', on);
            sun.classList.toggle('hidden', !on);
        }

        // apply saved preference
        if (localStorage.getItem('nightMode') === 'on') {
            root.classList.add('night-mode');
        }
        applyIcons();

        document.getElementById('nightModeToggle').addEventListener('click', function () {
            root.classList.toggle('night-mode');
            localStorage.setItem('nightMode', root.classList.contains('night-mode') ? 'on' : 'off');
            applyIcons();
        });
    })();
</script>
