@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2">Purchase Order: <span class="text-primary">#{{ $purchase->reference_no }}</span></h5>
                    <p class="mb-0">Status: <span class="badge bg-success">Received</span></p>
                </div>
                <div class="col-auto">
                    <button class="btn btn-falcon-default btn-sm me-2" onclick="window.print();">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <a href="{{ route('admin.purchase.edit', $purchase->id) }}" class="btn btn-falcon-primary btn-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.purchase.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Items Purchased</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <h6 class="px-3 py-2 fw-bold">{{ $product->name }}</h6>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Product / Variation</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Cost</th>
                                    <th class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->items as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">
                                                {{ $item->variation->name ?? ($item->variation->sku ?? 'Standard Item') }}
                                            </div>
                                        </td>
                                        <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end pe-3 fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tr class="border-top">
                            <td class="ps-3 fw-bold text-end" style="width: 80%;">Grand Total:</td>
                            <td class="pe-3 fw-bold text-end text-primary">
                                {{ number_format($purchase->grand_total, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if ($purchase->notes)
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Internal Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted italic">{{ $purchase->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Purchase Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-400 small fw-bold text-uppercase">Supplier</label>
                        <p class="fw-bold mb-0 text-dark">{{ $purchase->vendor->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-400 small fw-bold text-uppercase">Purchase Date</label>
                        <p class="mb-0 text-dark">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="mb-0">
                        <label class="text-400 small fw-bold text-uppercase">Unit of Measurement</label>
                        <p class="mb-0 text-dark">{{ $purchase->unit->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">System Log</h6>
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-500">Created At:</span>
                        <span class="text-700">{{ $purchase->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-500">Last Updated:</span>
                        <span class="text-700">{{ $purchase->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
