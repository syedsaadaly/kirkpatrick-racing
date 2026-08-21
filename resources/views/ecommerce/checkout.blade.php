@extends('front.include.app')
@section('title', 'Checkout')

@section('content')
    <script src="https://js.stripe.com/v3/"></script>

    <div class="innerBan">
        <img src="{{ asset('front/images/innerimg.png') }}" class="w-100" alt="">
        <div class="overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-sm-12">
                        <h2>Checkout</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="contact-inner login-contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-sm-12">

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="toplogin">
                        <h3 class="page-form-heading">Order Summary</h3>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    @php $itemDetails = $item->attributes->details; @endphp
                                    <tr>
                                        <td>
                                            {{ $item->name }}
                                            @if ($itemDetails)
                                                <br><small class="text-muted">{{ $itemDetails }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>${{ number_format($total, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="toplogin">
                        <h3 class="page-form-heading">Shipping Details</h3>
                    </div>

                    <form id="payment-form" action="{{ route('checkout.store') }}" method="POST" class="contactform loginform">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Shipping Address</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="country" class="form-control @error('country') is-invalid @enderror" required>
                                        <option value="US" selected>United States</option>
                                        <option value="CA" {{ old('country') === 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="GB" {{ old('country') === 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="AU" {{ old('country') === 'AU' ? 'selected' : '' }}>Australia</option>
                                    </select>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Payment Information</label>
                                    <div id="card-element" class="form-control" style="height: auto; padding: 12px;"></div>
                                    <div id="card-errors" role="alert" class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                            <input type="hidden" name="payment_intent_id" id="payment_intent_id">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <button id="submit-button" type="submit" class="themeBtn">Pay &amp; Place Order</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const stripe = Stripe("{{ $stripeKey }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (event) => {
            if (document.getElementById('payment_intent_id').value) {
                return;
            }

            event.preventDefault();
            document.getElementById('submit-button').disabled = true;

            const { paymentIntent, error } = await stripe.confirmCardPayment("{{ $clientSecret }}", {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: form.name.value,
                        email: form.email.value,
                    }
                }
            });

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                document.getElementById('submit-button').disabled = false;
            } else if (paymentIntent.status === 'succeeded') {
                document.getElementById('payment_intent_id').value = paymentIntent.id;
                form.submit();
            }
        });
    </script>
@endsection
