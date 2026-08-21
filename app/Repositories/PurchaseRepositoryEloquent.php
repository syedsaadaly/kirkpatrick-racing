<?php

namespace App\Repositories;

use App\Models\ProductVariation;
use App\Models\Purchase;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PurchaseRepository;
use App\Validators\PurchaseValidator;
use Illuminate\Support\Facades\DB;

/**
 * Class PurchaseRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PurchaseRepositoryEloquent extends BaseRepository implements PurchaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Purchase::class;
    }

    public function createWithItems(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $purchase = parent::create($attributes);
            $grandTotal = 0;

            foreach ($attributes['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $grandTotal += $subtotal;

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'product_variation_id' => $item['variation_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                if (!empty($item['variation_id'])) {
                    $variation = ProductVariation::find($item['variation_id']);
                    if ($variation && $variation->manage_stock) {
                        $variation->increment('stock_quantity', $item['quantity']);
                    }
                }
            }

            $purchase->update(['grand_total' => $grandTotal]);
            return $purchase;
        });
    }

    public function deleteWithStockReversal($id)
    {
        return DB::transaction(function () use ($id) {
            $purchase = $this->find($id);

            foreach ($purchase->items as $item) {
                if ($item->product_variation_id) {
                    $variation = ProductVariation::find($item->product_variation_id);
                    if ($variation && $variation->manage_stock) {
                        $variation->decrement('stock_quantity', $item->quantity);
                    }
                }
            }

            return $purchase->delete();
        });
    }

    public function updateWithStockAdjustment($id, array $attributes)
    {
        return DB::transaction(function () use ($id, $attributes) {
            $purchase = $this->find($id);

            foreach ($purchase->items as $oldItem) {
                $stockModel = $oldItem->product_variation_id
                    ? \App\Models\ProductVariation::find($oldItem->product_variation_id)
                    : \App\Models\Product::find($oldItem->product_id);

                if ($stockModel && $stockModel->manage_stock) {
                    $stockModel->decrement('stock_quantity', $oldItem->quantity);
                }
            }

            $purchase->items()->delete();

            $purchase->update([
                'vendor_id'     => $attributes['vendor_id'],
                'unit_id'       => $attributes['unit_id'],
                'purchase_date' => $attributes['purchase_date'],
                'notes'         => $attributes['notes'] ?? null,
            ]);

            $grandTotal = 0;

            if (!empty($attributes['items'])) {
                foreach ($attributes['items'] as $item) {
                    $qty = (float)($item['quantity'] ?? 0);
                    $price = (float)($item['unit_price'] ?? 0);
                    $subtotal = $qty * $price;
                    $grandTotal += $subtotal;

                    $purchase->items()->create([
                        'product_id'           => $item['product_id'],
                        'product_variation_id' => $item['variation_id'] ?: null,
                        'quantity'             => $qty,
                        'unit_price'           => $price,
                        'subtotal'             => $subtotal,
                    ]);

                    $newStockModel = !empty($item['variation_id'])
                        ? \App\Models\ProductVariation::find($item['variation_id'])
                        : \App\Models\Product::find($item['product_id']);

                    if ($newStockModel && $newStockModel->manage_stock) {
                        $newStockModel->increment('stock_quantity', $qty);
                    }
                }
            }

            $purchase->update(['grand_total' => $grandTotal]);

            return $purchase->load('items');
        });
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
