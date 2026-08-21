@extends('account.layouts.app')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Order {{ $order->order_number }}</h5>
                    <p class="text-muted mb-0">Placed {{ $order->created_at->format('d M, Y H:i') }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-light"><h6 class="mb-0">Status</h6></div>
                <div class="card-body">
                    <span class="badge bg-soft-success text-success text-capitalize">{{ $order->status }}</span>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-light"><h6 class="mb-0">Shipping</h6></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->name }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $order->address }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p class="mb-0"><strong>Country:</strong> {{ $order->country }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light"><h6 class="mb-0">Items</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name }}
                                            @if (!empty($item->attributes['details']))
                                                <br><small class="text-muted">{{ $item->attributes['details'] }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end">${{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
