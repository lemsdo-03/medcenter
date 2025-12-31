<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Patients') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Search, view, and manage registered patients.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            @if(session('success'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-6 py-4">
                    <p class="text-sm font-medium"><span class="font-semibold">Success:</span> {{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-3xl border border-amber-200 bg-amber-50 text-amber-800 px-6 py-4">
                    <p class="text-sm font-medium"><span class="font-semibold">Warning:</span> {{ session('warning') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-3xl border border-rose-200 bg-rose-50 text-rose-800 px-6 py-4">
                    <p class="text-sm font-medium"><span class="font-semibold">Error:</span> {{ session('error') }}</p>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Patient List</h3>
                            <p class="mt-1 text-sm text-slate-500">Use search to quickly find a patient.</p>
                        </div>

                        <a href="{{ route('receptionist.patients.create') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Add New Patient
                        </a>
                    </div>

                    <form method="GET" action="{{ route('receptionist.patients.index') }}" class="mt-6">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex-1 relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search by name..."
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0 pl-10">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                                Search
                            </button>

                            @if(request('search'))
                                <a href="{{ route('receptionist.patients.index') }}"
                                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    @if($patients->count() > 0)
                        <div class="overflow-x-auto rounded-3xl border border-slate-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                        <th class="py-3 px-4">ID</th>
                                        <th class="py-3 px-4">Name</th>
                                        <th class="py-3 px-4">Date of Birth</th>
                                        <th class="py-3 px-4">Gender</th>
                                        <th class="py-3 px-4">Phone</th>
                                        <th class="py-3 px-4">Email</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($patients as $patient)
                                        @php
                                            $gender = strtolower($patient->gender ?? '');
                                            $genderStyles = [
                                                'male' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'female' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                'other' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            ];
                                        @endphp

                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-3 px-4 font-semibold text-slate-900">
                                                {{ $patient->id }}
                                            </td>

                                            <td class="py-3 px-4">
                                                <div class="font-semibold text-slate-900">{{ $patient->full_name }}</div>
                                            </td>

                                            <td class="py-3 px-4 text-slate-800 whitespace-nowrap">
                                                {{ optional($patient->date_of_birth)->format('M d, Y') ?? 'N/A' }}
                                            </td>

                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center rounded-full px-3 py-1 border text-xs font-semibold {{ $genderStyles[$gender] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                    {{ ucfirst($patient->gender ?? 'N/A') }}
                                                </span>
                                            </td>

                                            <td class="py-3 px-4 text-slate-800">
                                                {{ $patient->phone ?? 'N/A' }}
                                            </td>

                                            <td class="py-3 px-4 text-slate-800">
                                                {{ $patient->email ?? 'N/A' }}
                                            </td>

                                            <td class="py-3 px-4 text-right">
                                                <div class="inline-flex gap-2">
                                                    <a href="{{ route('receptionist.patients.show', $patient) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                                        View
                                                    </a>

                                                    <a href="{{ route('receptionist.patients.edit', $patient) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-100 hover:bg-amber-100 transition">
                                                        Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                            <p class="mt-4 text-lg font-semibold text-slate-900">No patients found</p>

                            @if(request('search'))
                                <p class="mt-1 text-sm text-slate-500">Try a different search term.</p>
                                <a href="{{ route('receptionist.patients.index') }}"
                                   class="mt-5 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition">
                                    Clear Search
                                </a>
                            @else
                                <p class="mt-1 text-sm text-slate-500">Add your first patient to get started.</p>
                                <a href="{{ route('receptionist.patients.create') }}"
                                   class="mt-5 inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                                    Add New Patient
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
