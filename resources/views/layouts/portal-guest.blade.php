<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MedCenter')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    {{-- Top bar --}}
    <header class="border-b border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="MedCenter" class="h-10 w-10 object-contain" />
                <div class="leading-tight">
                    <div class="font-semibold">MedCenter</div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

            {{-- Left: Brand panel --}}
            <section class="relative rounded-3xl overflow-hidden border border-slate-200 min-h-[520px]">
                <div class="absolute inset-0">
                    <img
                        id="loginHeroImg"
                        src="{{ asset('images/1.jpg') }}"
                        alt="MedCenter"
                        class="h-full w-full object-cover transition-opacity duration-500"
                    />
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-950/85 via-slate-950/65 to-slate-950/85"></div>
                </div>

                <div class="relative p-8 text-white h-full flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center">
                                <img src="{{ asset('images/logo.png') }}" alt="MedCenter logo" class="h-8 w-8 object-contain" />
                            </div>
                            <div class="leading-tight">
                                <div class="text-lg font-semibold">MedCenter</div>
                                <div class="text-sm text-white/70">Staff Portal</div>
                            </div>
                        </div>

                        <span class="text-xs px-3 py-1 rounded-full bg-white/10 border border-white/10">
                            Secure Login
                        </span>
                    </div>

                    <div class="mt-auto">
                        <h1 class="text-4xl font-semibold leading-tight tracking-tight">
                            Care starts here.
                        </h1>
                        <p class="mt-3 text-white/80 max-w-md">
                            Appointments, patient records, and clinical notes in one clean portal.
                        </p>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const imgEl = document.getElementById('loginHeroImg');
                        if (!imgEl) return;

                        const images = [
                            "{{ asset('images/1.jpg') }}",
                            "{{ asset('images/2.jpg') }}",
                            "{{ asset('images/3.jpg') }}",
                            "{{ asset('images/4.jpg') }}",
                            "{{ asset('images/5.jpg') }}",
                        ];

                        let i = 0;

                        setInterval(() => {
                            i = (i + 1) % images.length;
                            imgEl.classList.add('opacity-0');

                            setTimeout(() => {
                                imgEl.src = images[i];
                                imgEl.onload = () => imgEl.classList.remove('opacity-0');
                            }, 250);
                        }, 10000);
                    });
                </script>
            </section>

            {{-- Right: Page content --}}
            @yield('content')

        </div>
    </main>
</body>
</html>
