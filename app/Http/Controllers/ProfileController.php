<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('Dashboard.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function disable2fa(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'google2fa_secret' => null,
            'mfa_enabled' => false,
        ]);

        // Clear 2FA session
        session()->forget('2fa_verified');

        return redirect()->route('profile')->with('success', 'Two-Factor Authentication has been disabled.');
    }

    public function enable2fa(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'mfa_enabled' => true,
        ]);

        return redirect()->route('2fa.setup')->with('success', 'Please complete the Google Authenticator setup.');
    }
}
