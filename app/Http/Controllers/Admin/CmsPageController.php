<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldData;
use App\Models\Page;
use App\Models\PageSectionField;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    public function show(Page $page)
    {
        $page->load(['sections' => function($query) {
            $query->where('is_listable', 0)->orderBy('order');
        }, 'sections.fields.fieldData', 'sections.fields.options']);

        return view('admin.cms.index', compact('page'));
    }

    public function updatePageData(Request $request, Page $page)
    {
        try {
            $request->validate([
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:255',
            ]);

            $page->update([
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]);

            foreach ($request->fields ?? [] as $fieldId => $value) {
                $field = PageSectionField::find($fieldId);
            
                if ($field) {
                    $finalValue = null;
            
                    if ($field->is_multiple && $field->field_type !== 'image') {
                        $values = is_array($value) ? $value : [$value];
                        $filteredValues = array_filter($values, function($val) {
                            return !empty($val) || $val === '0';
                        });
                        $finalValue = !empty($filteredValues) ? json_encode(array_values($filteredValues)) : null;
                    } else {
                        if ($field->field_type === 'image') {
                            if ($request->hasFile("fields.{$fieldId}")) {
                                $field->clearMediaCollection('page-images');
                                $field->addMediaFromRequest("fields.{$fieldId}")
                                    ->toMediaCollection('page-images');
                                $finalValue = $field->getFirstMediaUrl('page-images');
                            } else {
                                $finalValue = $field->fieldData->value ?? null;
                            }
                        } else {
                            $finalValue = is_array($value) ? implode(', ', $value) : $value;
                        }
                    }
            
                    FieldData::updateOrCreate(
                        ['page_section_field_id' => $fieldId],
                        ['value' => (string)$finalValue] 
                    );
                }
            }

            if ($request->has('field_data_ids')) {
                foreach ($request->field_data_ids as $fieldId => $dataId) {
                    $fieldDataRecord = FieldData::find($dataId);
            
                    if ($fieldDataRecord) {
                        $fieldDataRecord->update([
                            'alt_text' => $request->input("alt.{$fieldId}")
                        ]);
                    }
                }
            }

            return back()->with('success', 'Page content updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating page: ' . $e->getMessage());
        }
    }
}
