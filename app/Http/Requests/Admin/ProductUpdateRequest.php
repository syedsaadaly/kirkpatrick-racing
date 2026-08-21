<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId,
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories.*' => 'exists:categories,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'remove_gallery_images' => 'sometimes|array',
            'remove_gallery_images.*' => 'exists:media,id',
            'height' => 'nullable|numeric|min:0',
            'wheelbase' => 'nullable|string|max:255',
            'range' => 'nullable|string|max:255',
            'top_speed' => 'nullable|string|max:255',
            'power' => 'nullable|string|max:255',
        ];
    }
}
