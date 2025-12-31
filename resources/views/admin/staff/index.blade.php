<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                    {{ __('Staff Management') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Manage doctor and receptionist accounts.
                </p>
            </div>

            <a href="{{ route('admin.staff.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Staff
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-6 space-y-4">

            {{-- Compact flash messages (UI only) --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
                    <span class="font-semibold">Error:</span> {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Staff Members</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Total: <span class="font-semibold text-slate-900">{{ $staff->count() }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($staff->count() > 0)
                    <div class="p-6">
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50 sticky top-0">
                                    <tr class="text-left text-xs font-semibold text-slate-600">
                                        <th class="px-4 py-3">Staff</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Role</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach($staff as $user)
                                        @php
                                            $role = $user->role;
                                            $roleClass = match ($role) {
                                                'admin' => 'bg-slate-900 text-white',
                                                'doctor' => 'bg-emerald-50 text-emerald-800 border border-emerald-100',
                                                'receptionist' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                                            };
                                            $initial = strtoupper(substr($user->name, 0, 1));
                                        @endphp

                                        <tr class="hover:bg-slate-50/70">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center font-semibold text-slate-700">
                                                        {{ $initial }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                                                        <div class="text-xs text-slate-500">ID: {{ $user->id }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-sm text-slate-700">
                                                {{ $user->email }}
                                            </td>

                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                                                    {{ ucfirst($role) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.staff.edit', $user) }}"
                                                       class="px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                                                        Edit
                                                    </a>

                                                    <form method="POST"
                                                          action="{{ route('admin.staff.destroy', $user) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-4 py-2 rounded-2xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 transition">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="mx-auto h-14 w-14 rounded-3xl bg-slate-100 border border-slate-200"></div>
                        <p class="mt-4 text-lg font-semibold text-slate-900">No staff members found</p>
                        <p class="mt-1 text-sm text-slate-500">Get started by adding your first staff member.</p>
                        <a href="{{ route('admin.staff.create') }}"
                           class="inline-flex mt-6 items-center justify-center px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Add Staff Member
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
