@extends('front.include.app')

@section('content')
    <div class="container my-5">
        <div class="container text-center py-5">
            <div class="card p-5 shadow-sm">
                <i class="fa fa-check-circle text-success fa-5x mb-3"></i>
                <h2>Thank You for Your Purchase!</h2>
                <p>Your payment was successful and your order is being processed.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
@endsection
