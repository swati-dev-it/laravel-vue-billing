@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f8fafc;
    }

    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
    }

    .login-box {
        width: 100%;
        max-width: 400px;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .login-logo {
        height: 60px;
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #1f2937;
        border-color: #1f2937;
    }

    .btn-primary:hover {
        background-color: #111827;
        border-color: #111827;
    }

    .forgot-link {
        font-size: 0.9rem;
    }

    .remember-me {
        font-size: 0.9rem;
    }
</style>

<div class="login-container">
    <img src="/images/taptik_logo.png" alt="Logo" class="login-logo" />

    <div class="login-box">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label remember-me" for="remember">Remember me</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif

                <button type="submit" class="btn btn-primary px-4">
                    LOG IN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
