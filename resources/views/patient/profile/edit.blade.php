<x-patient-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900 leading-tight">Edit Profile</h2>
        <p class="text-sm text-slate-500 mt-1">Update your personal information.</p>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('patient.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="text-sm font-medium text-slate-700">First Name</label>
                        <input id="first_name" name="first_name" type="text"
                            value="{{ old('first_name', $patient->first_name) }}" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="last_name" class="text-sm font-medium text-slate-700">Last Name</label>
                        <input id="last_name" name="last_name" type="text"
                            value="{{ old('last_name', $patient->last_name) }}" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', $patient->email) }}" required
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="phone" class="text-sm font-medium text-slate-700">Phone</label>
                    <input id="phone" name="phone" type="text"
                        value="{{ old('phone', $patient->phone) }}" required
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_of_birth" class="text-sm font-medium text-slate-700">Date of Birth</label>
                        <input id="date_of_birth" name="date_of_birth" type="date"
                            value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>
                    <div>
                        <label for="gender" class="text-sm font-medium text-slate-700">Gender</label>
                        <select id="gender" name="gender" required
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label for="address" class="text-sm font-medium text-slate-700">Address</label>
                    <input id="address" name="address" type="text"
                        value="{{ old('address', $patient->address) }}"
                        class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <hr class="border-slate-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="text-sm font-medium text-slate-700">New Password (optional)</label>
                        <input id="password" name="password" type="password"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                        <p class="text-xs text-slate-500 mt-2">Leave blank to keep current password.</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="text-sm font-medium text-slate-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-patient-layout>
