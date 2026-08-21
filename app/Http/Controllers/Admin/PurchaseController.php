<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use App\Repositories\UnitRepository;
use App\Repositories\VendorRepository;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $repository, $vendors, $units;

    public function __construct(PurchaseRepository $repository, VendorRepository $vendors, UnitRepository $units)
    {
        $this->repository = $repository;
        $this->vendors = $vendors;
        $this->units = $units;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = $this->repository->with(['vendor', 'unit'])->all();
        return view('admin.purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchase = new Purchase;
        $vendors = $this->vendors->all();
        $units = $this->units->all();
        $products = \App\Models\Product::with('variations')->where('is_active', true)->get();

        return view('admin.purchase.create', compact('purchase', 'vendors', 'units', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'     => 'nullable|exists:vendors,id',
            'unit_id'       => 'required',
            'purchase_date' => 'required|date',
            'items'         => 'nullable|array',
            'items.*.product_id' => 'required_with:items',
            'items.*.quantity'   => 'required_with:items|numeric|min:0.1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ]);

        $data = $request->all();
        $data['reference_no'] = 'PUR-' . strtoupper(bin2hex(random_bytes(4)));

        $this->repository->createWithItems($data);

        if ($request->input('action') === 'save_and_add') {
            return redirect()
                ->route('admin.purchase.create')
                ->with('success', 'Purchase recorded. You can add another one now.');
        }

        return redirect()
            ->route('admin.purchase.index')
            ->with('success', 'Purchase recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with([
            'vendor', 
            'unit',
            'items.variation',
            'items.product.categories',
            'items.product.media'
        ])->findOrFail($id);
    
        $product = $purchase->items->first()->product ?? null;
    
        return view('admin.purchase.show', compact('purchase', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase = $this->repository->with(['items.variation'])->find($id);
        $vendors = $this->vendors->all();
        $units = $this->units->all();
        $products = \App\Models\Product::with('variations')->where('is_active', true)->get();

        return view('admin.purchase.create', compact('purchase', 'vendors', 'units', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'vendor_id'     => 'nullable|exists:vendors,id',
            'unit_id'       => 'required',
            'purchase_date' => 'required|date',
            'items'         => 'nullable|array',
            'items.*.product_id' => 'required_with:items',
            'items.*.quantity'   => 'required_with:items|numeric|min:0.1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ]);

        $this->repository->updateWithStockAdjustment($id, $request->all());

        return redirect()->route('admin.purchase.index')
            ->with('success', 'Purchase updated and inventory adjusted.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->repository->deleteWithStockReversal($id);
        return redirect()->route('admin.purchase.index')->with('success', 'Purchase removed.');
    }
}
