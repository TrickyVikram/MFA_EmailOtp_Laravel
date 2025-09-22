<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user has Google 2FA secret and hasn't verified 2FA in this session
        if ($user->google2fa_secret && !session('2fa_verified')) {
            return redirect()->route('2fa.verify');
        }

        // If user doesn't have Google 2FA secret, allow access but suggest setup
        return $next($request);
    }
}
