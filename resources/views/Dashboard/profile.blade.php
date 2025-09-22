@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar rounded">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile') }}">
                                <i class="fas fa-user"></i> Profile & Security
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Profile & Security Settings</h1>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-user"></i> Profile Information</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', Auth::user()->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input id="username" type="text"
                                            class="form-control @error('username') is-invalid @enderror" name="username"
                                            value="{{ old('username', Auth::user()->username) }}" required>
                                        <small class="form-text text-muted">Only letters, numbers, and underscores allowed</small>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-shield-alt"></i> Security Settings</h6>
                            </div>
                            <div class="card-body">
                                <!-- Google 2FA Status -->
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                    <div>
                                        <h6 class="mb-1">Google Authenticator</h6>
                                        <small class="text-muted">Two-factor authentication using Google
                                            Authenticator</small>
                                    </div>
                                    <div>
                                        @if (Auth::user()->mfa_enabled && Auth::user()->google2fa_secret)
                                            <span class="badge bg-success mb-2">Enabled</span><br>
                                            <form method="POST" action="{{ route('2fa.disable') }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to disable 2FA?')">
                                                    <i class="fas fa-times"></i> Disable
                                                </button>
                                            </form>
                                        @elseif (Auth::user()->google2fa_secret && !Auth::user()->mfa_enabled)
                                            <span class="badge bg-warning mb-2">Setup Incomplete</span><br>
                                            <a href="{{ route('2fa.setup') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-qrcode"></i> Complete Setup
                                            </a>
                                        @else
                                            <span class="badge bg-secondary mb-2">Disabled</span><br>
                                            <form method="POST" action="{{ route('2fa.enable') }}"
                                                style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Enable MFA
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Password Change -->
                                <div class="p-3 border rounded">
                                    <h6 class="mb-1">Change Password</h6>
                                    <small class="text-muted mb-3 d-block">Update your account password</small>
                                    <a href="{{ route('password.request') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-key"></i> Change Password
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Log -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-history"></i> Security Activity</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Activity</th>
                                                <th>Date & Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="fas fa-sign-in-alt text-success"></i> Login via MFA</td>
                                                <td>{{ now()->format('M d, Y H:i') }}</td>
                                                <td><span class="badge bg-success">Success</span></td>
                                            </tr>
                                            @if (Auth::user()->google2fa_secret)
                                                <tr>
                                                    <td><i class="fas fa-qrcode text-primary"></i> Google 2FA Setup</td>
                                                    <td>{{ Auth::user()->updated_at->format('M d, Y H:i') }}</td>
                                                    <td><span class="badge bg-primary">Completed</span></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .sidebar {
            min-height: 100vh;
        }

        .nav-link {
            color: #6c757d;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }

        .nav-link:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
@endsection
