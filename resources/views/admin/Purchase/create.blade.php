@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ $purchase->exists ? 'Edit Purchase Order' : 'Create New Purchase' }}</h5>
                    <p class="text-muted mb-0">
                        {{ $purchase->exists ? 'Update details for ' . $purchase->reference_no : 'Add a new inventory purchase' }}
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.purchase.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Purchases
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ $purchase->exists ? route('admin.purchase.update', $purchase->id) : route('admin.purchase.store') }}"
        method="POST" id="purchaseForm">
        @csrf
        @if ($purchase->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Select Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="main_product_select" class="form-select select2" required
                                    {{ $purchase->exists ? 'disabled' : '' }}>
                                    <option value="">-- Choose Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-variations='@json($product->variations)'
                                            {{ old('product_id', $purchase->items->first()->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($purchase->exists)
                                    <input type="hidden" name="product_id"
                                        value="{{ $purchase->items->first()->product_id }}">
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Supplier</label>
                                <select name="vendor_id" class="form-select">
                                    <option value="">-- Choose Supplier --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ old('vendor_id', $purchase->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Unit <span class="text-danger">*</span></label>
                                <select name="unit_id" class="form-select" required>
                                    <option value="">-- Choose Unit --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $purchase->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Date</label>
                                <input type="date" name="purchase_date" class="form-control"
                                    value="{{ old('purchase_date', $purchase->purchase_date ?? date('Y-m-d')) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Internal remarks...">{{ old('notes', $purchase->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 {{ $purchase->exists ? '' : 'd-none' }}" id="variation_card">
                <div class="card mb-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Product / Variation</th>
                                        <th>Current Stock</th>
                                        <th style="width: 150px;">Purchase Qty</th>
                                        <th style="width: 150px;">Unit Cost</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="variation_rows">
                                    @if ($purchase->exists)
                                        @foreach ($purchase->items as $index => $item)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="fw-bold text-dark">
                                                        {{ $item->variation->name ?? ($item->variation->sku ?? 'Standard Item') }}
                                                    </div>
                                                    <input type="hidden" name="items[{{ $index }}][product_id]"
                                                        value="{{ $item->product_id }}">
                                                    <input type="hidden" name="items[{{ $index }}][variation_id]"
                                                        value="{{ $item->product_variation_id }}">
                                                </td>
                                                <td>
                                                    <span class="badge bg-200 text-dark">
                                                        {{ $item->variation->stock_quantity ?? '--' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        class="form-control form-control-sm qty_input"
                                                        value="{{ $item->quantity }}" step="1.00">
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][unit_price]"
                                                        class="form-control form-control-sm price_input"
                                                        value="{{ $item->unit_price }}" step="1.00">
                                                </td>
                                                <td class="text-end pe-3 fw-bold subtotal_display">
                                                    {{ number_format($item->subtotal, 2, '.', '') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.purchase.index') }}" class="btn btn-link text-secondary p-0">
                                <i class="fas fa-times-circle me-1"></i> Cancel
                            </a>
                            <div>
                                @if (!$purchase->exists)
                                    <button type="submit" name="action" value="save_and_add"
                                        class="btn btn-outline-primary px-4 me-2">
                                        <i class="fas fa-plus-circle me-1"></i> Save and Add Another
                                    </button>
                                @endif
                                <button type="submit" name="action" value="save_and_close" class="btn btn-primary px-5">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $purchase->exists ? 'Update Purchase' : 'Save and Close' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-script')
    <script>
        $(document).ready(function() {
            if ($('#variation_rows tr').length > 0) {
                calculateGrandTotal();
            }

            $('#main_product_select').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const variations = selectedOption.data('variations');
                const product_id = $(this).val();
                const product_name = selectedOption.text().trim();
                const container = $('#variation_rows');

                container.empty();

                if (!product_id) {
                    $('#variation_card').addClass('d-none');
                    return;
                }

                $('#variation_card').removeClass('d-none');

                if (variations && variations.length > 0) {
                    variations.forEach((v, index) => {
                        const variantDisplayName = v.name ? v.name : (v.sku ? v.sku :
                            'Standard Item');

                        container.append(generateRow(
                            index,
                            product_id,
                            v.id,
                            product_name,
                            variantDisplayName,
                            v.stock_quantity,
                            v.price
                        ));
                    });
                } else {
                    container.append(generateRow(0, product_id, '', product_name, 'Standard Item', '--',
                        '0.00'));
                }
                calculateGrandTotal();
            });

            function generateRow(index, prodId, varId, pName, vName, stock, price) {
                return `
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark">${vName}</div>
                            <input type="hidden" name="items[${index}][product_id]" value="${prodId}">
                            <input type="hidden" name="items[${index}][variation_id]" value="${varId}">
                        </td>
                        <td><span class="badge bg-200 text-dark">${stock}</span></td>
                        <td>
                            <input type="number" name="items[${index}][quantity]" 
                                class="form-control form-control-sm qty_input" 
                                placeholder="0" step="0.01">
                        </td>
                        <td>
                            <input type="number" name="items[${index}][unit_price]" 
                                class="form-control form-control-sm price_input" 
                                value="${price}" step="0.01">
                        </td>
                        <td class="text-end pe-3 fw-bold subtotal_display">0.00</td>
                    </tr>
                `;
            }

            $(document).on('input', '.qty_input, .price_input', function() {
                let row = $(this).closest('tr');
                let qty = parseFloat(row.find('.qty_input').val()) || 0;
                let price = parseFloat(row.find('.price_input').val()) || 0;
                let subtotal = qty * price;

                row.find('.subtotal_display').text(subtotal.toFixed(2));
                calculateGrandTotal();
            });

            function calculateGrandTotal() {
                let grandTotal = 0;
                $('.subtotal_display').each(function() {
                    grandTotal += parseFloat($(this).text()) || 0;
                });
                $('#grand_total_display').text(grandTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }
        });
    </script>
@endpush
