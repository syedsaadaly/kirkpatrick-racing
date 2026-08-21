@extends('front.include.app')
@section('title', 'Login')

@section('content')
    <div class="innerBan">
        <img src="{{ asset('front/images/innerimg.png') }}" class="w-100" alt="">
        <div class="overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-sm-12">
                        <h2>Login</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="contact-inner login-contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-sm-12">
                    <div class="toplogin">
                        <h3 class="page-form-heading">Welcome Back</h3>
                        <p>Log in to continue your journey with Kirkpatrick Racing.</p>
                    </div>
                    
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="contactform loginform">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="demo@gmail.com" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="*******" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="keepLoggedIn" 
                                           name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="keepLoggedIn">
                                        Keep me logged in
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Forgot Password</a>
                                @endif
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="themeBtn">Log in</button>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection