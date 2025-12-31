@extends('layouts.portal-guest')

@section('title', 'Login | MedCenter')

@section('content')
<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
    <p class="text-sm text-slate-500"><b>Welcome back</b></p>
    <h2 class="text-2xl font-semibold mt-1">Sign in</h2>
    <p class="text-sm text-slate-600 mt-2"><b>Enter your email and password to continue.</b></p>

    @if (session('status'))
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
               
            />
        </div>

        <div>
            <label for="password" class="text-sm font-medium text-slate-700">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-300 focus:ring-0"
                
            />
        </div>

        <button type="submit"
            class="w-full px-4 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-800">
            Log in
        </button>

        
    </form>
</section>
@endsection
