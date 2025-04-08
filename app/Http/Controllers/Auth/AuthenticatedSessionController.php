<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt authentication
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Regenerate session for security
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ Role-based redirection
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.select-class');
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Authentication failed
        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Logout the user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
