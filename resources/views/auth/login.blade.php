@extends('layouts.guest')

@section('content')

<div class="container-fluid vh-100">
    <div class="row h-100">

        
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center"
             style="background:linear-gradient(135deg,#198754,#43b97f);">

            <div class="text-center text-white px-5">

                <div class="mb-4">
                    <i class="bi bi-tree-fill" style="font-size:80px;"></i>
                </div>

                <h1 class="fw-bold">
                    AgroStock
                </h1>

                <h4 class="mb-4">
                    Inventory Management System
                </h4>

                <p class="lead">
                    Sistem Inventaris Pertanian Berbasis Laravel
                </p>

            </div>

        </div>

        
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">

            <div class="card shadow-lg border-0" style="width:420px; border-radius:15px;">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold text-success">
                            Login
                        </h2>

                        <p class="text-muted">
                            Silakan masuk ke sistem
                        </p>

                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember">

                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>

                        </div>

                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-success btn-lg">

                                Login

                            </button>

                        </div>

                        @if(Route::has('password.request'))

                            <div class="text-center mt-3">

                                <a href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>

                            </div>

                        @endif

                    </form>

                    <hr>

                    <div class="text-center text-muted small">

                        © {{ date('Y') }} AgroStock

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection