<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                Edit Staff
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Update staff info. Leave password blank to keep the current password.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-6">

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Staff account</h3>
                        <p class="text-sm text-slate-600 mt-1">
                            Editing: <span class="font-semibold">{{ $staff->name }}</span>
                        </p>
                    </div>

                    <a href="{{ route('admin.staff.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                        Back
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.staff.update', $staff) }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-slate-700" />
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                            :value="$staff->name"
                            required
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-700" />
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                            :value="$staff->email"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Passwords (optional) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('New Password (optional)')" class="text-sm font-medium text-slate-700" />
                            <x-text-input
                                id="password"
                                name="password"
                                type="password"
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="text-xs text-slate-500 mt-2">Leave blank to keep current password.</p>
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-slate-700" />
                            <x-text-input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                            />
                        </div>
                    </div>

                    {{-- Role --}}
                    <div>
                        <x-input-label for="role" :value="__('Role')" class="text-sm font-medium text-slate-700" />

                        @if($staff->role === 'admin')
                            <input
                                id="role"
                                type="text"
                                value="Admin"
                                disabled
                                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-100 text-slate-700"
                            />
                            <input type="hidden" name="role" value="admin">
                            <p class="text-xs text-slate-500 mt-2">Admin role cannot be changed.</p>
                        @else
                            <select
                                id="role"
                                name="role"
                                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                            >
                                <option value="doctor" {{ $staff->role=='doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="receptionist" {{ $staff->role=='receptionist' ? 'selected' : '' }}>Receptionist</option>
                            </select>
                        @endif

                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    {{-- Actions --}}
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.staff.index') }}"
                           class="px-4 py-3 rounded-2xl border border-slate-200 bg-white text-slate-800 text-sm font-semibold hover:bg-slate-50 transition">
                            Cancel
                        </a>

                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                            Update Staff
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
