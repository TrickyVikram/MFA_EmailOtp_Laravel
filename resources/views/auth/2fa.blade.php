@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">Authenticator OTP</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('2fa.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Enter OTP from Google Authenticator</label>
                            <input type="text" name="one_time_password" class="form-control" required>
                            @error('one_time_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
