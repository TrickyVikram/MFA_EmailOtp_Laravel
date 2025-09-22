@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar rounded">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">
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
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-check-circle"></i> MFA Authentication Complete</h5>
                            </div>
                            <div class="card-body">
                                <h6>Welcome back, <strong>{{ Auth::user()->name }}</strong>!</h6>
                                <p class="text-muted">You have successfully completed Multi-Factor Authentication.</p>
                                <p>Your account is secured with:</p>
                                <ul>
                                    <li><i class="fas fa-envelope text-success"></i> Email OTP Verification</li>
                                    @if (Auth::user()->google2fa_secret)
                                        <li><i class="fas fa-mobile-alt text-success"></i> Google Authenticator (Enabled)
                                        </li>
                                    @else
                                        <li><i class="fas fa-mobile-alt text-warning"></i> Google Authenticator (Not Setup)
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-shield-alt"></i> Security Status</h6>
                            </div>
                            <div class="card-body">
                                @if (!Auth::user()->google2fa_secret)
                                    <div class="alert alert-warning p-2">
                                        <small><strong>Action Required:</strong></small><br>
                                        <small>Setup Google Authenticator for enhanced security.</small>
                                    </div>
                                    <a href="{{ route('2fa.setup') }}" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-qrcode"></i> Setup 2FA
                                    </a>
                                @else
                                    <div class="alert alert-success p-2">
                                        <small><i class="fas fa-check"></i> Your account is fully secured!</small>
                                    </div>
                                    <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-cog"></i> Manage Security
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-info-circle"></i> Quick Info</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                        <p><strong>Account Type:</strong> <span class="badge bg-primary">Premium User</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Last Login:</strong> {{ now()->format('M d, Y H:i') }}</p>
                                        <p><strong>MFA Status:</strong>
                                            @if (Auth::user()->google2fa_secret)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning">Partial</span>
                                            @endif
                                        </p>
                                    </div>
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
