@extends('front.include.app')
@section('title', 'Sign Up')

@section('content')
    <!-- Begin: Main Slider -->
    <div class="innerBan">
        <img src="{{ asset('front/images/innerimg.png') }}" class="w-100" alt="">
        <div class="overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-sm-12">
                        <h2>Sign Up</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End: Main Slider -->

    <section class="contact-inner sign-inner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-sm-12">
                    <div class="toplogin">
                        <h3 class="page-form-heading">Create Your Account</h3>
                        <p>Join Kirkpatrick Racing and explore our full lineup.</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}" class="contactform loginform">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="John Doe" name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="demo@gmail.com" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           placeholder="+1 (333) 000-0000" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Create a strong password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           placeholder="Re-enter your password" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="themeBtn">Create Account</button>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <p>Already have an account? <a href="{{ route('login') }}">Log In</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection