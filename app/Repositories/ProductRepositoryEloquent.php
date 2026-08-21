<?php

namespace App\Repositories;

use App\Models\Product;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
    public function model()
    {
        return Product::class;
    }

    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);

            if (empty($data['sku'])) {
                $data['sku'] = $this->generateUniqueSKU();
            }

            $product = parent::create($data);

            if (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (request()->hasFile('main_image')) {
                $product->clearMediaCollection('products');
                $product->addMediaFromRequest('main_image')->toMediaCollection('products');
            }

            if (request()->hasFile('gallery_images')) {
                foreach (request()->file('gallery_images') as $image) {
                    $product->addMedia($image)->toMediaCollection('products');
                }
            }

            if (!empty($data['variations'])) {
                foreach ($data['variations'] as $variationData) {
                    $variation = $product->variations()->create([
                        'name'           => $variationData['name'],
                        'sku'            => !empty($variationData['sku']) ? $variationData['sku'] : $this->generateUniqueSKU(),
                        'price'          => !empty($variationData['price']) ? $variationData['price'] : $data['base_price'],
                        'sale_price'     => !empty($variationData['sale_price']) ? $variationData['sale_price'] : $data['sale_price'] ?? null,
                        'description'    => $variationData['description'] ?? null,
                        'stock_quantity' => !empty($variationData['stock_quantity']) ? $variationData['stock_quantity'] : $data['stock_quantity'],
                        'manage_stock'   => $variationData['manage_stock'] ?? true,
                        'weight'         => $variationData['weight'] ?? null,
                        'is_active'      => $variationData['is_active'] ?? true,
                    ]);

                    $variation->variationOptions()->sync(
                        $this->buildOptionSyncData($variationData['option_ids'], $variationData['option_labels'] ?? [])
                    );
                }
            }

            return $product;
        });
    }

    private function buildOptionSyncData(array $optionIds, array $optionLabels): array
    {
        $sync = [];
        foreach ($optionIds as $optionId) {
            $sync[$optionId] = ['label' => $optionLabels[$optionId] ?? null];
        }
        return $sync;
    }

    public function updateProduct(array $data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $product = $this->find($id);

            if ($data['name'] !== $product->name && empty($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
            }

            $product = parent::update($data, $id);

            if (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (request()->hasFile('main_image')) {
                $product->clearMediaCollection('products');
                $product->addMediaFromRequest('main_image')->toMediaCollection('products');
            }

            if (isset($data['remove_main_image']) && $data['remove_main_image']) {
                $product->clearMediaCollection('products');
            }

            if (request()->hasFile('gallery_images')) {
                foreach (request()->file('gallery_images') as $image) {
                    $product->addMedia($image)->toMediaCollection('products');
                }
            }

            if (isset($data['remove_gallery_images']) && is_array($data['remove_gallery_images'])) {
                $mediaItems = $product->getMedia('products');
                foreach ($data['remove_gallery_images'] as $mediaId) {
                    $mediaItem = $mediaItems->firstWhere('id', $mediaId);
                    if ($mediaItem) {
                        $mediaItem->delete();
                        // $product->clearMediaCollection('products');
                    }
                }
            }

            if (isset($data['deleted_variations'])) {
                $product->variations()->whereIn('id', $data['deleted_variations'])->delete();
            }

            if (isset($data['variations']) && is_array($data['variations'])) {
                foreach ($data['variations'] as $key => $variationData) {
                    if (str_starts_with($key, 'new_') || empty($variationData['id'])) {
                        $variation = $product->variations()->create([
                            'sku'            => !empty($variationData['sku']) ? $variationData['sku'] : $this->generateUniqueSKU(),
                            'price'          => !empty($variationData['price']) ? $variationData['price'] : $data['base_price'],
                            'sale_price'     => !empty($variationData['sale_price']) ? $variationData['sale_price'] : ($data['sale_price'] ?? null),
                            'description'    => $variationData['description'] ?? null,
                            'stock_quantity' => $variationData['stock_quantity'] ?? 0,
                            'is_active'      => isset($variationData['is_active']),
                        ]);
                    } else {
                        $variation = $product->variations()->findOrFail($variationData['id']);
                        $variation->update([
                            'sku'            => !empty($variationData['sku']) ? $variationData['sku'] : $variation->sku,
                            'price'          => !empty($variationData['price']) ? $variationData['price'] : $data['base_price'],
                            'sale_price'     => !empty($variationData['sale_price']) ? $variationData['sale_price'] : ($data['sale_price'] ?? null),
                            'description'    => $variationData['description'] ?? null,
                            'stock_quantity' => $variationData['stock_quantity'] ?? 0,
                            'is_active'      => isset($variationData['is_active']),
                        ]);
                    }

                    if (!empty($variationData['option_ids'])) {
                        $variation->variationOptions()->sync(
                            $this->buildOptionSyncData($variationData['option_ids'], $variationData['option_labels'] ?? [])
                        );
                    }
                }
            }

            return $product;
        });
    }

    public function deleteProduct($id)
    {
        return DB::transaction(function () use ($id) {
            $product = $this->find($id);
            $product->clearMediaCollection('products');
            $product->categories()->detach();
            return parent::delete($id);
        });
    }

    public function getProductsWithRelations()
    {
        return $this->model->with(['categories', 'media', 'variations'])->latest()->get();
    }

    public function bulkAction($action, $ids)
    {
        return DB::transaction(function () use ($action, $ids) {
            $productIds = explode(',', $ids);

            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    break;
                case 'feature':
                    Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                    break;
                case 'unfeature':
                    Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                    break;
                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    break;
            }

            return true;
        });
    }

    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    private function slugExists($slug, $excludeId = null)
    {
        $query = Product::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    private function generateUniqueSKU()
    {
        do {
            $sku = 'PROD-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
