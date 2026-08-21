<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\XhrResponse;
use App\Http\Controllers\Controller;
use App\Imports\FieldDataImport;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PageSectionItem;
use App\Models\FieldData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListableSectionController extends Controller
{
    public function index(Page $page)
    {
        $page->load(['sections' => function ($query) {
            $query->where('is_listable', 1)
                ->withCount('items')
                ->with(['fields', 'items.fieldData']);
        }]);

        $listableSections = $page->sections->where('is_listable', 1);

        if ($listableSections->count() < 1) {
            return redirect()->route('admin.cms-builder.page.view', $page->id);
        }

        return view('admin.cms.listable-sections', compact('page', 'listableSections'));
    }

    public function createItem(Page $page, PageSection $section)
    {
        $section->load('fields');
        return view('admin.cms.create-item', compact('page', 'section'));
    }

    public function storeItem(Request $request, Page $page, PageSection $section)
    {
        try {
            DB::beginTransaction();

            $maxOrder = PageSectionItem::where('page_section_id', $section->id)->max('order') ?? 0;

            $item = PageSectionItem::create([
                'page_section_id' => $section->id,
                'order' => $maxOrder + 1,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]);

            foreach ($request->fields ?? [] as $fieldId => $value) {
                $field = $section->fields->where('id', $fieldId)->first();
                $altText = $request->input("alt.$fieldId");

                if ($field) {
                    $fieldData = FieldData::create([
                        'page_section_field_id' => $fieldId,
                        'value' => $value,
                        'item_id' => $item->id,
                        'alt_text' => $altText,
                    ]);

                    if ($field->field_type === 'image' && $request->hasFile("fields.{$fieldId}")) {
                        $fieldData->clearMediaCollection('section-items');
                        $fieldData->addMediaFromRequest("fields.{$fieldId}")
                            ->toMediaCollection('section-items');

                        $fieldData->value = $fieldData->getFirstMediaUrl('section-items');
                        $fieldData->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.cms-builder.pages.listable-sections.index', $page->id)
                ->with('success', 'Item created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating item: ' . $e->getMessage());
        }
    }

    public function editItem(Page $page, PageSection $section, PageSectionItem $item)
    {
        $item->load('fieldData.field');
        $section->load(['fields.options' => function($q) {
            $q->orderBy('order', 'asc');
        }]);

        return view('admin.cms.edit-item', compact('page', 'section', 'item'));
    }

    public function updateItem(Request $request, Page $page, PageSection $section, PageSectionItem $item)
    {
        try {
            DB::beginTransaction();

            $item->update([
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]);

            foreach ($request->fields ?? [] as $fieldId => $value) {
                $field = $section->fields->where('id', $fieldId)->first();

                if ($field) {
                    $processedValue = is_array($value) ? implode(', ', $value) : $value;
                    $fieldData = FieldData::where('page_section_field_id', $fieldId)
                        ->where('item_id', $item->id)
                        ->first();

                    if ($fieldData) {
                        if ($field->field_type === 'image' && $request->hasFile("fields.{$fieldId}")) {
                            $fieldData->clearMediaCollection('section-items');
                            $fieldData->addMediaFromRequest("fields.{$fieldId}")
                                ->toMediaCollection('section-items');

                            $fieldData->value = $fieldData->getFirstMediaUrl('section-items');
                            $fieldData->save();
                        } else {
                            $fieldData->value = $processedValue;
                            $fieldData->save();
                        }
                    } else {
                        $fieldData = FieldData::create([
                            'page_section_field_id' => $fieldId,
                            'value' => $processedValue,
                            'item_id' => $item->id,
                            'alt_text' => $request->input("alt.$fieldId"),
                        ]);

                        if ($field->field_type === 'image' && $request->hasFile("fields.{$fieldId}")) {
                            $fieldData->clearMediaCollection('section-items');
                            $fieldData->addMediaFromRequest("fields.{$fieldId}")
                                ->toMediaCollection('section-items');

                            $fieldData->value = $fieldData->getFirstMediaUrl('section-items');
                            $fieldData->save();
                        }
                    }
                }
            }

            if ($request->has('field_data_ids')) {
                foreach ($request->field_data_ids as $index => $id) {
                    $fieldDataRecord = FieldData::find($id);

                    if ($fieldDataRecord) {
                        $newAltText = $request->input("alt.{$index}");

                        $fieldDataRecord->update([
                            'alt_text' => $newAltText
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.cms-builder.pages.listable-sections.index', $page->id)
                ->with('success', 'Item updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating item: ' . $e->getMessage());
        }
    }

    public function importData(Request $request)
    {

        $request->validate([
            'module_file' => 'required|file|mimes:csv,xlsx'
        ]);

        $pageSection = PageSection::findOrFail($request->section_id);

        try {
            $file = $request->file('module_file');
            $csvHeaders = Excel::toCollection(null, $file)->first()->first()->toArray();
            $filePath = $file->store('csv_imports');
            $fileName = $file->getClientOriginalName();

            $dbFields = $pageSection->fields->toArray();
            $sectionName = $pageSection->section_name;

            $html = view('admin.cms.partials.csv-mapping-form',
                compact('dbFields', 'csvHeaders', 'filePath', 'pageSection', 'sectionName', 'fileName'))->render();

            $data = [
                'html' => $html,
                'fileName' => $fileName,
                'sectionName' => $sectionName
            ];

            return XhrResponse::success($data);
        } catch (\Exception $exception) {
            return XhrResponse::error([], $exception->getMessage());
        }
    }

    public function processImport(Request $request)
    {
        $filePath = $request->input('file_path');
        $sectionId = $request->input('section_id');
        $mappings = $request->input('mappings');

        $fieldMap = [];
        foreach ($mappings as $dbFieldId => $mapData) {
            if ($mapData['csv_header'] !== 'skip_column') {
                $fieldMap[$mapData['csv_header']] = [
                    'id' => (int)$dbFieldId,
                    'type' => $mapData['db_field_type'],
                    'download_media' => isset($mapData['download_media']) ? 1 : 0,
                ];
            }
        }

        if (empty($fieldMap)) {
            // ... error handling ...
        }

        try {
            $fullPath = Storage::path($filePath);

            $import = new FieldDataImport($fieldMap, $sectionId);

            Excel::import($import, $fullPath);

            $totalImported = $import->getImportedCount();

            Storage::delete($filePath);

            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully imported!',
                'total_imported' => $totalImported,
            ]);

        } catch (\Exception $exception) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Import failed: ' . $exception->getMessage(),
                'total_imported' => 0,
            ], 500);
        }
    }

    public function destroyItem(Page $page, PageSection $section, PageSectionItem $item)
    {
        try {
            FieldData::where('item_id', $item->id)->delete();

            $item->delete();

            return redirect()->route('admin.cms-builder.pages.listable-sections.index', $page->id)
                ->with('success', 'Item deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting item: ' . $e->getMessage());
        }
    }

    /**
     * Handles the bulk deletion of items based on the provided request.
     *
     * @param Request $request The incoming HTTP request containing the IDs.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:page_section_items,id'
        ]);

        try {
            $items = PageSectionItem::whereIn('id', $request->ids)->get();

            foreach ($items as $item) {
                $item->fieldData()->delete();
                $item->delete();
            }

            return XhrResponse::success([], count($request->ids) . ' items deleted successfully.');

        } catch (\Exception $e) {
            return XhrResponse::error([], 'Error: ' . $e->getMessage(), 500);
        }
    }
}
