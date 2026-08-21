<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Repositories\VariationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariationController extends Controller
{
    protected $variationRepository;

    public function __construct(VariationRepository $variationRepository)
    {
        $this->variationRepository = $variationRepository;
    }

    public function index()
    {
        $variations = $this->variationRepository->getVariationsWithOptions();
        return view('admin.variations.index', compact('variations'));
    }

    public function create()
    {
        return view('admin.variations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:variations,slug',
            'description' => 'nullable|string',
            'options' => 'required|array|min:1',
            'options.*.value' => 'required|string|max:255',
            'options.*.color' => 'nullable|string|max:7',
        ]);

        $submittedSlugs = collect($request->input('options'))->map(fn ($option) => Str::slug($option['value']));
        if ($submittedSlugs->count() !== $submittedSlugs->unique()->count()) {
            return redirect()->back()
                ->with('error', 'Two options can\'t have the same value — please make each option value unique.')
                ->withInput();
        }

        try {
            $variation = $this->variationRepository->createVariation($request->all());

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating variation: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $variation = $this->variationRepository->find($id);
        return view('admin.variations.edit', compact('variation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255|unique:variations,slug,' . $id,
            'description'     => 'nullable|string',
            'is_active'       => 'boolean',
            'options'         => 'required|array|min:1',
            'options.*.value' => 'required|string|max:255',
            'options.*.color' => 'nullable|string|max:7',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        $submittedSlugs = collect($data['options'])->map(fn ($option) => Str::slug($option['value']));
        if ($submittedSlugs->count() !== $submittedSlugs->unique()->count()) {
            return redirect()->back()
                ->with('error', 'Two options can\'t have the same value — please make each option value unique.')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($data, $id) {
                $variation = Variation::findOrFail($id);

                $variation->update([
                    'name'        => $data['name'],
                    'slug'        => $data['slug'] ?? Str::slug($data['name']),
                    'description' => $data['description'] ?? null,
                    'is_active'   => $data['is_active'],
                ]);

                $submittedIds = [];

                foreach ($data['options'] as $option) {
                    if (!empty($option['id'])) {
                        $variation->options()->where('id', $option['id'])->update([
                            'value' => $option['value'],
                            'slug'  => Str::slug($option['value']),
                            'color' => $option['color'] ?? null,
                        ]);
                        $submittedIds[] = (int) $option['id'];
                    } else {
                        $newOption = $variation->options()->create([
                            'value' => $option['value'],
                            'slug'  => Str::slug($option['value']),
                            'color' => $option['color'] ?? null,
                        ]);
                        $submittedIds[] = $newOption->id;
                    }
                }

                $variation->options()->whereNotIn('id', $submittedIds)->delete();
            });

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating variation: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->variationRepository->deleteVariation($id);

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting variation: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids' => 'required|string'
        ]);

        try {
            $this->variationRepository->bulkAction($request->action, $request->ids);

            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeOption(Request $request, $variationId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $option = $this->variationRepository->createVariationOption($variationId, $request->all());

            return redirect()->back()
                ->with('success', 'Variation option added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error adding variation option: ' . $e->getMessage());
        }
    }

    public function updateOption(Request $request, $optionId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        try {
            $this->variationRepository->updateVariationOption($optionId, $request->all());

            return redirect()->back()
                ->with('success', 'Variation option updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating variation option: ' . $e->getMessage());
        }
    }

    public function destroyOption($optionId)
    {
        try {
            $this->variationRepository->deleteVariationOption($optionId);

            return redirect()->back()
                ->with('success', 'Variation option deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting variation option: ' . $e->getMessage());
        }
    }

    public function getOptions(Request $request)
    {
        $request->validate([
            'variation_ids' => 'required|array',
            'variation_ids.*' => 'exists:variations,id'
        ]);

        $productId = $request->input('product_id');

        $variations = Variation::with(['activeOptions'])
            ->whereIn('id', $request->variation_ids)
            ->get()
            ->map(function($variation) use ($productId) {
                return [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'slug' => $variation->slug,
                    'description' => $variation->description,
                    'is_active' => $variation->is_active,
                    'sort_order' => $variation->sort_order,
                    'active_options' => $variation->activeOptions->map(function($option) use ($productId, $variation) {
                        $isUsed = false;

                        if ($productId) {
                            $isUsed = ProductVariation::where('product_id', $productId)
                                ->whereHas('variationOptions', function($query) use ($option) {
                                    $query->where('variation_option_id', $option->id);
                                })
                                ->exists();
                        }

                        return [
                            'id' => $option->id,
                            'variation_id' => $option->variation_id,
                            'value' => $option->value,
                            'slug' => $option->slug,
                            'color' => $option->color,
                            'sort_order' => $option->sort_order,
                            'is_active' => $option->is_active,
                            'is_used' => $isUsed
                        ];
                    })->toArray()
                ];
            });

        return response()->json([
            'variations' => $variations
        ]);
    }
}
