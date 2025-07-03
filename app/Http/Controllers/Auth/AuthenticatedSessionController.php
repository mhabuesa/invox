<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
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
        $request->session()->regenerate();

        // remember
        $remember = $request->has('remember');

        // Store login data in cookies if "remember me" was selected
        if ($remember) {
            Cookie::queue('remember_email', $request->email, 60 * 24 * 30); // 30 days
            Cookie::queue('remember_password', encrypt($request->password), 60 * 24 * 30);
            Cookie::queue('remember_me', true, 60 * 24 * 30);
        } else {
            // Clear cookies if "remember me" was not selected
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
            Cookie::queue(Cookie::forget('remember_me'));
        }

        return redirect()->intended(route('index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
