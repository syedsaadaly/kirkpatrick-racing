@extends('account.layouts.app')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="mb-2">Welcome back, {{ auth()->user()->name }}!</h5>
            <p class="text-muted mb-0">Here's a quick look at your account.</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $ordersCount }}</h4>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-soft-success text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">${{ number_format($totalSpent, 2) }}</h4>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Recent Orders</h6>
            <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            @if ($orders->isEmpty())
                <p class="text-muted mb-0">You haven't placed any orders yet. <a href="{{ route('front.shop') }}">Start shopping</a>.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th width="80"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $order->order_number }}</span></td>
                                    <td><small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small></td>
                                    <td class="text-end">${{ number_format($order->total_amount, 2) }}</td>
                                    <td><span class="badge bg-soft-success text-success text-capitalize">{{ $order->status }}</span></td>
                                    <td>
                                        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
