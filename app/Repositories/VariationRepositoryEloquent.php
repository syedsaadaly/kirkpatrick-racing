<?php

namespace App\Repositories;

use App\Models\Variation;
use App\Models\VariationOption;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariationRepositoryEloquent extends BaseRepository implements VariationRepository
{
    public function model()
    {
        return Variation::class;
    }

    public function createVariation(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name']);
            }

            $variation = parent::create($data);

            if (isset($data['options']) && is_array($data['options'])) {
                foreach ($data['options'] as $optionData) {
                    $this->createVariationOption($variation->id, $optionData);
                }
            }

            return $variation;
        });
    }

    public function updateVariation(array $data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $variation = $this->find($id);

            if (!empty($data['name']) && $data['name'] !== $variation->name && empty($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $variation->id);
            }

            return parent::update($data, $id);
        });
    }

    public function deleteVariation($id)
    {
        return DB::transaction(function () use ($id) {
            $variation = $this->find($id);

            $variation->options()->delete();

            return parent::delete($id);
        });
    }

    public function getVariationsWithOptions()
    {
        return $this->model->with(['activeOptions'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function bulkAction($action, $ids)
    {
        return DB::transaction(function () use ($action, $ids) {
            $variationIds = explode(',', $ids);

            switch ($action) {
                case 'activate':
                    Variation::whereIn('id', $variationIds)->update(['is_active' => true]);
                    break;

                case 'deactivate':
                    Variation::whereIn('id', $variationIds)->update(['is_active' => false]);
                    break;

                case 'delete':
                    $variations = Variation::whereIn('id', $variationIds)->get();
                    foreach ($variations as $variation) {
                        $variation->options()->delete();
                        $variation->delete();
                    }
                    break;

                default:
                    throw new \Exception('Invalid action');
            }

            return true;
        });
    }

    public function createVariationOption($variationId, array $data)
    {
        if (empty($data['slug']) && !empty($data['value'])) {
            $data['slug'] = Str::slug($data['value']);
        }

        $data['variation_id'] = $variationId;

        return VariationOption::create($data);
    }

    public function updateVariationOption($optionId, array $data)
    {
        $option = VariationOption::findOrFail($optionId);

        if (!empty($data['value']) && $data['value'] !== $option->value && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['value']);
        }

        return $option->update($data);
    }

    public function deleteVariationOption($optionId)
    {
        $option = VariationOption::findOrFail($optionId);
        return $option->delete();
    }

    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Variation::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Variation::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
