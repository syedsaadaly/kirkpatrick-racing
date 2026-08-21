<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopDemoSeeder extends Seeder
{
    public function run(): void
    {
        $imagesPath = public_path('front/images');

        // Categories
        $electricBikes = Category::firstOrCreate(
            ['slug' => 'electric-bikes'],
            ['name' => 'Electric Bikes', 'description' => 'High-performance electric dirt bikes.', 'is_active' => true]
        );
        Category::firstOrCreate(
            ['slug' => 'performance-parts'],
            ['name' => 'Performance Parts', 'description' => 'Precision gear and upgrades.', 'is_active' => true]
        );
        Category::firstOrCreate(
            ['slug' => 'riding-gear'],
            ['name' => 'Riding Gear', 'description' => 'Apparel and safety equipment.', 'is_active' => true]
        );
        Category::firstOrCreate(
            ['slug' => 'accessories'],
            ['name' => 'Accessories', 'description' => 'Custom mods and add-ons.', 'is_active' => true]
        );

        // Color variation + options
        $colorVariation = Variation::firstOrCreate(
            ['slug' => 'color'],
            ['name' => 'Color', 'description' => 'Bike color', 'is_active' => true, 'sort_order' => 1]
        );

        $colors = ['White', 'Black', 'Gold', 'Red', 'Silver', 'Blue'];
        $colorOptions = [];
        foreach ($colors as $i => $color) {
            $colorOptions[$color] = VariationOption::firstOrCreate(
                ['variation_id' => $colorVariation->id, 'slug' => Str::slug($color)],
                ['value' => $color, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $description = 'Peak Power: 7000W. Battery: Samsung 72V 25Ah. Front Suspension: Fast Ace Suspension. '
            . 'Rear Suspension: Fast Ace Shock Absorber. Handlebar Width: 28.34" (72cm). Weight Capacity: 280lbs.';

        $products = [
            ['name' => 'STRIKE SHADOW 72V', 'price' => 2899.00, 'image' => 'S72-White1.webp', 'colors' => ['White', 'Black', 'Gold']],
            ['name' => 'ETM™ RTR SPORT', 'price' => 2999.00, 'image' => 'Sport Right Side.webp', 'colors' => ['White', 'Black', 'Gold']],
            ['name' => 'The Rizzler™ SPORT', 'price' => 2999.00, 'image' => 'New XL Right Side.webp', 'colors' => ['White', 'Black', 'Gold']],
            ['name' => 'The Rizzler™ Bike', 'price' => 2999.00, 'image' => 'Rizzler Front.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'STRIKE SHADOW 60V', 'price' => 2099.00, 'image' => 'S60-BLack2.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'The Rizzler™ SPORT XL', 'price' => 2999.00, 'image' => 'Rizzler Girls.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'ETM™ RTR Lite - The Affordable 110 Killer', 'price' => 2199.00, 'image' => 'Right Shroud.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'STRIKE SHADOW 48V', 'price' => 1599.00, 'image' => 'S48-Red2.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'STRIKE SHADOW LX6', 'price' => 2199.00, 'image' => 'LX6W.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'STRIKE SHADOW LX4', 'price' => 1899.00, 'image' => 'LX4 White 2.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'ETM™ RTR ALPHA - The Worlds Fastest Pit Bike', 'price' => 4299.00, 'image' => 'Alpha.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'ETM RTR XL ALPHA - The Biggest, The Fastest, The Strongest', 'price' => 4499.00, 'image' => 'XL Alpha.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
            ['name' => 'ETM RTR Complete Body Plastic Set', 'price' => 4499.00, 'image' => 'Plastics Grey.webp', 'colors' => ['White', 'Black', 'Red', 'Silver', 'Blue']],
        ];

        foreach ($products as $index => $data) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $suffix = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . (++$suffix);
            }

            $sku = 'BIKE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $description,
                    'short_description' => 'Pro-grade electric motorsports performance.',
                    'base_price' => $data['price'],
                    'sku' => $sku,
                    'stock_quantity' => 15,
                    'manage_stock' => true,
                    'is_active' => true,
                    'is_featured' => $index < 3,
                    'wheelbase' => '45.8 IN',
                    'range' => '120 KM',
                    'top_speed' => '85 KM/H',
                    'power' => '5000W',
                ]
            );

            $product->categories()->syncWithoutDetaching([$electricBikes->id]);

            $imageFile = $imagesPath . '/' . $data['image'];
            if ($product->getMedia('products')->isEmpty() && file_exists($imageFile)) {
                $product->addMedia($imageFile)
                    ->preservingOriginal()
                    ->toMediaCollection('products');
            }

            foreach ($data['colors'] as $color) {
                $option = $colorOptions[$color];

                $variation = ProductVariation::firstOrCreate(
                    ['product_id' => $product->id, 'sku' => $sku . '-' . strtoupper(substr($color, 0, 2))],
                    [
                        'name' => $color,
                        'price' => $data['price'],
                        'stock_quantity' => 5,
                        'manage_stock' => true,
                        'is_active' => true,
                    ]
                );

                $variation->variationOptions()->syncWithoutDetaching([$option->id]);
            }
        }
    }
}
