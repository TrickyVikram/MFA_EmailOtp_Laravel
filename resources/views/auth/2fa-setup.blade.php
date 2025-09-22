@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-qrcode"></i> Set up Google Authenticator</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Step 1: Install Google Authenticator</h5>
                                <p>Download and install the Google Authenticator app on your mobile device.</p>

                                <h5>Step 2: Scan QR Code</h5>
                                <p>Scan the QR code below with your Google Authenticator app:</p>

                                <div class="text-center mb-3">
                                    {!! $qrCodeSvg !!}
                                </div>

                                <h5>Manual Entry</h5>
                                <p>If you can't scan the QR code, manually enter this key:</p>
                                <div class="alert alert-info">
                                    <code style="font-size: 16px; font-weight: bold;">{{ $secret }}</code>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Step 3: Verify</h5>
                                <p>Enter the 6-digit code from your Google Authenticator app:</p>

                                <form method="POST" action="{{ route('2fa.setup.post') }}">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="one_time_password"
                                            class="form-label">{{ __('Verification Code') }}</label>
                                        <input id="one_time_password" type="text"
                                            class="form-control @error('one_time_password') is-invalid @enderror"
                                            name="one_time_password" placeholder="Enter 6-digit code" maxlength="6"
                                            required autofocus>

                                        @error('one_time_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Verify & Complete Setup') }}
                                        </button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                            {{ __('Skip for now') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
