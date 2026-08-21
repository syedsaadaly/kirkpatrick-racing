@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Orders</h5>
                    <p class="text-muted mb-0">Orders placed by customers at checkout</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if ($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Country</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th width="80">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $order->order_number }}</span></td>
                                    <td>
                                        {{ $order->name ?? optional($order->user)->name ?? 'Guest' }}
                                        @if ($order->email)
                                            <br><small class="text-muted">{{ $order->email }}</small>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small></td>
                                    <td>{{ $order->country }}</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-soft-success text-success text-capitalize">{{ $order->status }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4>No Orders Yet</h4>
                    <p class="text-muted">Orders placed through checkout will show up here.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
