<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFAController extends Controller
{
    public function index()
    {
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate(['one_time_password' => 'required|digits:6']);

        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            session(['2fa_verified' => true]);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['one_time_password' => 'Invalid OTP']);
    }

    public function setup()
    {
        $user = Auth::user();

        if (!$user->google2fa_secret) {
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $google2fa = new Google2FA();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Generate QR code as SVG
        $qrCodeSvg = QrCode::size(300)->generate($qrCodeUrl);

        return view('auth.2fa-setup', [
            'qrCodeSvg' => $qrCodeSvg,
            'secret' => $user->google2fa_secret,
            'qrCodeUrl' => $qrCodeUrl
        ]);
    }

    public function setupPost(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6'
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // Enable MFA after successful verification
            $user->update(['mfa_enabled' => true]);
            
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard')->with('success', 'Google Authenticator has been set up successfully!');
        }

        return back()->withErrors(['one_time_password' => 'Invalid verification code. Please try again.']);
    }
}
