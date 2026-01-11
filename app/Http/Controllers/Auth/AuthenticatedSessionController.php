<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view. lareavel breez
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate(); //create a new session

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * log out
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout(); //“log the current user out of the normal website session

        $request->session()->invalidate();

        $request->session()->regenerateToken();

   return redirect()->route('login'); //redirect to login page
    }
}
