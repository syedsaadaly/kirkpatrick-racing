@extends('front.include.app')
@section('title', 'Your Cart')

@section('content')
    <div class="innerBan">
        <img src="{{ asset('front/images/innerimg.png') }}" class="w-100" alt="">
        <div class="overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-sm-12">
                        <h2>Your Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="contact-inner login-contact">
        <div class="container">
            @if ($cartItems->isEmpty())
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <p class="mb-4">Your cart is empty.</p>
                        <a href="{{ route('front.shop') }}" class="themeBtn">Continue Shopping</a>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8 col-sm-12">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Product</th>
                                        <th style="width: 110px;">Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        @php
                                            $itemImage = $item->attributes->image ?: asset('front/images/logo.png');
                                            $itemDetails = $item->attributes->details;
                                        @endphp
                                        <tr data-id="{{ $item->id }}">
                                            <td>
                                                <img src="{{ $itemImage }}"
                                                    style="width: 70px; height: 70px; object-fit: cover;" alt="{{ $item->name }}">
                                            </td>
                                            <td>
                                                <strong>{{ $item->name }}</strong><br>
                                                @if ($itemDetails)
                                                    <small class="text-muted">{{ $itemDetails }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" class="form-control qty-input" value="{{ $item->quantity }}"
                                                    min="1" data-id="{{ $item->id }}">
                                            </td>
                                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                            <td>
                                                <a href="{{ route('cart.remove', $item->id) }}" class="text-muted" title="Remove">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="cart-summary-box">
                            <h3 class="cart-summary-title">Summary</h3>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Total</span>
                                <strong>${{ number_format($cartTotal, 2) }}</strong>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="themeBtn d-block text-center">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).on('change', '.qty-input', function() {
                let id = $(this).data('id');
                let quantity = $(this).val();

                $.ajax({
                    url: "{{ route('cart.update') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        quantity: quantity
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message ?? 'Unable to update quantity.');
                        window.location.reload();
                    }
                });
            });
        </script>
    @endpush
@endsection
