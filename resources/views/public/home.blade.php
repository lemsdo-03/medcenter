<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedCenter</title>

    {{-- Tailwind via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-slate-900">
    {{-- Top utility bar --}}
    <div class="bg-emerald-950 text-white text-xs">
        <div class="max-w-6xl mx-auto px-6 py-2 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="font-semibold">MedCenter</span>
                <span class="text-white/70 hidden sm:inline">Care that feels organized</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:underline">About</a>
                <a href="#" class="hover:underline">Careers</a>
                <a href="#" class="hover:underline font-semibold">MyPortal</a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="border-b border-slate-200 bg-white sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-700"></div>
                <div class="leading-tight">
                    <div class="font-semibold">MedCenter</div>
                    <div class="text-xs text-slate-500">Care that feels organized</div>
                </div>
            </div>

            <nav class="hidden lg:flex items-center gap-6 text-sm text-slate-700">
                <a class="hover:text-emerald-700" href="#">Find a Doctor</a>

                <div class="relative group">
                    <button class="hover:text-emerald-700 flex items-center gap-1">
                        Services <span class="text-slate-400">▾</span>
                    </button>

                    <div class="absolute left-0 top-full mt-3 hidden group-hover:block">
                        <div class="w-[560px] rounded-2xl border border-slate-200 bg-white shadow-lg p-5">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold">Centers of Excellence</p>
                                <a href="#" class="text-sm text-emerald-700 font-medium hover:underline">View all</a>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <a class="p-3 rounded-xl border border-slate-200 hover:bg-slate-50" href="#">Cancer</a>
                                <a class="p-3 rounded-xl border border-slate-200 hover:bg-slate-50" href="#">Digestive</a>
                                <a class="p-3 rounded-xl border border-slate-200 hover:bg-slate-50" href="#">Orthopedics</a>
                                <a class="p-3 rounded-xl border border-slate-200 hover:bg-slate-50" href="#">Women’s Health</a>
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                Tip: keep menus simple so it feels “hospital website”, not “student template”.
                            </div>
                        </div>
                    </div>
                </div>

                <a class="hover:text-emerald-700" href="#">Appointments</a>
                <a class="hover:text-emerald-700" href="#">Locations</a>
                <a class="hover:text-emerald-700" href="#">Patients</a>
            </nav>

            <div class="flex items-center gap-2">
                <input class="hidden md:block w-64 rounded-xl border-slate-200 focus:ring-0 text-sm"
                       placeholder="Search services, doctors..." />
                <a class="px-4 py-2.5 rounded-xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800" href="#">
                    MyPortal
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-slate-50">
        <div class="max-w-6xl mx-auto px-6 py-10">
            <div class="rounded-3xl overflow-hidden border border-slate-200 bg-white">
                <div class="relative">
                    {{-- Hero "image" placeholder (replace with an actual image later) --}}
                    <div class="h-[320px] bg-gradient-to-r from-emerald-900 via-slate-900 to-slate-900"></div>

                    <div class="absolute inset-0 p-8 flex items-end">
                        <div class="max-w-xl">
                            <p class="text-emerald-200 text-sm font-medium">Centers of Excellence</p>
                            <h1 class="text-white text-3xl md:text-4xl font-semibold mt-2">
                                Specialty care, easy to find.
                            </h1>
                            <p class="text-slate-200 mt-3">
                                Explore services, book appointments, and manage patient records with a clean portal.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a class="px-5 py-2.5 rounded-xl bg-white text-slate-900 text-sm font-semibold hover:bg-slate-100" href="#">
                                    Explore services
                                </a>
                                <a class="px-5 py-2.5 rounded-xl bg-white/10 text-white text-sm font-semibold border border-white/15 hover:bg-white/15" href="#">
                                    Find a doctor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service tiles (THIS should become 2-3 columns when Tailwind works) --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ([
                            'Cancer' => 'Learn more and book',
                            'Digestive' => 'Learn more and book',
                            'Orthopedics' => 'Learn more and book',
                            'Primary Care' => 'Learn more and book',
                            'Women’s Health' => 'Learn more and book',
                            'More Services' => 'Browse all services',
                        ] as $title => $sub)
                            <a href="#" class="p-5 rounded-2xl border border-slate-200 hover:bg-slate-50">
                                <p class="font-semibold text-slate-900">{{ $title }}</p>
                                <p class="text-sm text-slate-600 mt-1">{{ $sub }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3 promo tiles --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-3xl border border-slate-200 bg-white p-6">
                    <p class="font-semibold">Health Care Near Me</p>
                    <p class="text-sm text-slate-600 mt-1">Find the closest location fast.</p>
                    <button class="mt-4 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                        Use my location
                    </button>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6">
                    <p class="font-semibold">A Top Hospital</p>
                    <p class="text-sm text-slate-600 mt-1">Show awards/recognition here.</p>
                    <a class="inline-block mt-4 text-emerald-700 font-medium hover:underline" href="#">
                        See recognitions
                    </a>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6">
                    <p class="font-semibold">News & Updates</p>
                    <p class="text-sm text-slate-600 mt-1">Announcements and events.</p>
                    <a class="inline-block mt-4 text-emerald-700 font-medium hover:underline" href="#">
                        View all
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
            <div>
                <p class="font-semibold">MedCenter</p>
                <p class="text-slate-600 mt-2">Phone: 410-000-0000</p>
                <p class="text-slate-600">City, Country</p>
            </div>
            <div>
                <p class="font-semibold">Connect</p>
                <div class="mt-3 space-y-2 text-slate-600">
                    <a class="block hover:text-slate-900" href="#">Plan your visit</a>
                    <a class="block hover:text-slate-900" href="#">Pay your bill</a>
                    <a class="block hover:text-slate-900" href="#">Contact</a>
                </div>
            </div>
            <div>
                <p class="font-semibold">Patients</p>
                <div class="mt-3 space-y-2 text-slate-600">
                    <a class="block hover:text-slate-900" href="#">Appointments</a>
                    <a class="block hover:text-slate-900" href="#">Medical records</a>
                    <a class="block hover:text-slate-900" href="#">FAQs</a>
                </div>
            </div>
            <div>
                <p class="font-semibold">Portal</p>
                <div class="mt-3 space-y-2 text-slate-600">
                    <a class="block hover:text-slate-900" href="#">Login</a>
                    <a class="block hover:text-slate-900" href="#">Register</a>
                </div>
            </div>
        </div>

        <div class="text-xs text-slate-500 border-t border-slate-200">
            <div class="max-w-6xl mx-auto px-6 py-4">© {{ date('Y') }} MedCenter</div>
        </div>
    </footer>
</body>
</html>
