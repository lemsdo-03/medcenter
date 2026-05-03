@extends('layouts.portal-guest')

@section('title', 'Register | MedCenter')

@section('content')
<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
    <p class="text-sm text-slate-500"><b>New here?</b></p>
    <h2 class="text-2xl font-semibold mt-1">Create Account</h2>
    <p class="text-sm text-slate-600 mt-2"><b>Register as a patient to book appointments.</b></p>

    @if ($errors->any())
        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4">
            <p class="text-sm font-semibold text-rose-800">Fix the following:</p>
            <ul class="mt-2 list-disc list-inside text-sm text-rose-700 space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('patient.register') }}" class="mt-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="text-sm font-medium text-slate-700">First Name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
            <div>
                <label for="last_name" class="text-sm font-medium text-slate-700">Last Name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
        </div>

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
        </div>

        <div>
            <label for="phone" class="text-sm font-medium text-slate-700">Phone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="date_of_birth" class="text-sm font-medium text-slate-700">Date of Birth</label>
                <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
            <div>
                <label for="gender" class="text-sm font-medium text-slate-700">Gender</label>
                <select id="gender" name="gender" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0">
                    <option value="">Select</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
            <div>
                <label for="password_confirmation" class="text-sm font-medium text-slate-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0" />
            </div>
        </div>

        <button type="submit"
            class="w-full px-4 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800">
            Create Account
        </button>

        <p class="text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-emerald-700 font-semibold hover:underline">Sign in</a>
        </p>
    </form>
</section>
@endsection
