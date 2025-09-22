<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function index() {
        return view('auth.otp');
    }

    public function verify(Request $request) {
        $request->validate(['otp' => 'required|digits:6']);

        $user = User::find(session('otp_user_id'));
        if (!$user) return redirect()->route('login');

        if ($user->email_otp != $request->otp || Carbon::now()->gt($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        // Clear OTP
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->save();

        // Login user and redirect to MFA
        Auth::login($user);
        return redirect()->route('2fa.verify');
    }
}
