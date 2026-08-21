@extends('account.layouts.app')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">My Orders</h5>
                    <p class="text-muted mb-0">Your order history with Kirkpatrick Racing</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4>No Orders Yet</h4>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('front.shop') }}" class="btn btn-primary">
                        <i class="fas fa-store me-1"></i> Start Shopping
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th width="80">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $order->order_number }}</span></td>
                                    <td><small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small></td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td><span class="badge bg-soft-success text-success text-capitalize">{{ $order->status }}</span></td>
                                    <td>
                                        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection
