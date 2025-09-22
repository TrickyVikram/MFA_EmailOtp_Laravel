@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">Email OTP</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('otp.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Enter OTP</label>
                            <input type="text" name="otp" class="form-control" required>
                            @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
