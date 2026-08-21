<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories.*' => 'exists:categories,id',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'weight' => 'nullable|numeric|min:0',
            'wheelbase' => 'nullable|string|max:255',
            'range' => 'nullable|string|max:255',
            'top_speed' => 'nullable|string|max:255',
            'power' => 'nullable|string|max:255',

            'variations.*.name'           => 'nullable|string|max:255',
            'variations'                  => 'nullable|array',
            'variations.*.option_ids'     => 'required_with:variations|array|min:1',
            'variations.*.option_ids.*'   => 'exists:variation_options,id',
            'variations.*.option_labels'  => 'nullable|array',
            'variations.*.sku'            => 'nullable|string',
            'variations.*.price'          => 'nullable|numeric|min:0',
            'variations.*.sale_price'     => 'nullable|numeric|min:0',
            'variations.*.description'    => 'nullable|string',
            'variations.*.stock_quantity' => 'required_with:variations|integer|min:0',
            'variations.*.is_active'      => 'boolean',
        ];
    }
}
