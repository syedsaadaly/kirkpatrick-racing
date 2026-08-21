<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CmsHelper;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PageSectionField;
use App\Models\FieldData;
use App\Models\PageSectionItem;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::withCount('sections')->latest()->paginate(10);
        return view('admin.cms-builder.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $relatedPages = Page::select(['id', 'name'])->get();
        $cmsPages = Page::where('is_cms', 1)->select('id', 'name')->get();

        $pageRelationTypes = PageSection::$RELATIONSHIP_TYPES;
        $pageRelations = PageSection::ELOQUENT_METHOD_MAP;

        return view('admin.cms-builder.create', compact('relatedPages', 'pageRelationTypes', 'pageRelations', 'cmsPages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $page = Page::create([
            'name' => $request->input('page.name'),
            'slug' => \Str::slug($request->input('page.name')),
            'is_cms' => $request->is_cms ? 1 : 0,
            'icon' => $request->icon,
        ]);

        $incomingSections = $request->input('section', []);

        foreach ($incomingSections as $sectionKey => $sectionData) {
            $sectionInfo = $sectionData['data'] ?? [];
            $fieldsGroup = $sectionData['fields'] ?? [];

            if (!empty($sectionInfo['relation'])) {
                $relationship = PageSection::getRelationMapping($sectionInfo['relation']);
            }

            $pageSection = PageSection::create([
                'page_id' => $page->id,
                'section_name' => $sectionInfo['name'] ?? '',
                'order' => $sectionInfo['order'] ?? 0,
                'section_type' => $sectionInfo['section_type'] ?? '',
                'is_listable' => ($sectionInfo['is_listable'] ?? 0),
                'related_section_id' => $sectionInfo['related_section_id'] ?? null,
                'relation_type' => $relationship['direction'] ?? null,
                'relation' => $relationship['method'] ?? null,
            ]);

            if ($pageSection->is_listable) {
                PageSectionItem::create([
                    'page_section_id' => $pageSection->id,
                    'order' => 1,
                ]);
            }

            foreach ($fieldsGroup as $fieldIndex => $fieldData) {
                if (empty($fieldData['field_name'])) {
                    continue;
                }

                $fieldValue = $fieldData['value'] ?? '';

                if (
                    $fieldData['field_type'] === 'image' &&
                    $request->hasFile("section.$sectionKey.fields.$fieldIndex.value")
                ) {

                    $file = $request->file("section.$sectionKey.fields.$fieldIndex.value");
                    $fieldValue = $file->store('page-images', 'public');
                }

                $pageSectionField = PageSectionField::create([
                    'page_section_id' => $pageSection->id,
                    'field_name' => $fieldData['field_name'],
                    'field_type' => $fieldData['field_type'],
                    'is_multiple' => isset($fieldData['is_multiple']) ? 1 : 0,
                    'is_required' => isset($fieldData['is_required']) ? 1 : 0,
                    'is_hidden'   => isset($fieldData['is_hidden']) ? 1 : 0,
                    'is_related' => isset($fieldData['is_related']) ? 1 : 0,
                ]);

                if (isset($fieldData['is_related'])) {
                    $pageSectionField->relation()->create([
                        'page_id'            => $page->id,
                        'section_id'         => $pageSection->id,
                        'related_page_id'    => $fieldData['related_page_id'] ?? null,
                        'related_section_id' => $fieldData['related_section_id'] ?? null,
                        'related_field_id'   => $fieldData['related_field_id'] ?? null,
                        'link_type'          => $fieldData['link_type'] ?? 'section',
                        'is_model'           => ($fieldData['link_type'] ?? '') === 'module' ? 1 : 0,
                    ]);
                }

                if (in_array($fieldData['field_type'], ['radio', 'checkbox']) && !empty($fieldData['options'])) {
                    foreach ($fieldData['options'] as $idx => $optionLabel) {
                        if (trim($optionLabel) !== '') {
                            $pageSectionField->options()->create([
                                'option_label' => $optionLabel,
                                'option_value' => \Str::slug($optionLabel),
                                'order' => $idx,
                            ]);
                        }
                    }
                }

                if (!$pageSection->is_listable) {
                    FieldData::create([
                        'page_section_field_id' => $pageSectionField->id,
                        'value' => $fieldValue,
                    ]);
                }
            }
        }

        $action = $request->action;
        return CmsHelper::returnPage($action, 'admin.cms-builder.pages.edit', 'admin.cms-builder.pages.index', 'Page created successfully!', true, $page->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = Page::with(['sections.fields.fieldData'])->findOrFail($id);
        return view('admin.cms-builder.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::with([
            'sections.fields.fieldData',
            'sections.fields.options',
            'sections.fields.relation'
        ])->findOrFail($id);

        $cmsPages = Page::where('is_cms', 1)->select('id', 'name')->get();

        foreach ($page->sections as $section) {
            foreach ($section->fields as $field) {
                if ($field->relation && $field->relation->related_page_id) {
                    $field->relation->available_sections = PageSection::where('page_id', $field->relation->related_page_id)
                        ->select('id', 'section_name')
                        ->get();

                    if ($field->relation->related_section_id) {
                        $field->relation->available_fields = PageSectionField::where('page_section_id', $field->relation->related_section_id)
                            ->select('id', 'field_name')
                            ->get();
                    }
                }
            }
        }

        $relatedPages = Page::select(['id', 'name'])->whereNot('id', $id)->get();

        $pageRelationTypes = PageSection::$RELATIONSHIP_TYPES;
        $pageRelations = PageSection::ELOQUENT_METHOD_MAP;

        return view('admin.cms-builder.edit', compact('page', 'relatedPages', 'pageRelationTypes', 'pageRelations', 'cmsPages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $page = Page::findOrFail($id);

        $page->update([
            'name' => $request->input('page.name'),
            'slug' => \Str::slug($request->input('page.name')),
            'is_cms' => $request->is_cms ? 1 : 0,
            'icon' => $request->icon,
        ]);

        $incomingSections = $request->input('section', []);

        $existingSectionIds = $page->sections()->pluck('id')->toArray();
        $receivedSectionIds = [];

        foreach ($incomingSections as $sectionKey => $sectionData) {
            $sectionInfo = $sectionData['data'] ?? [];
            $fieldsGroup = $sectionData['fields'] ?? [];

            $rawId = $sectionInfo['id'] ?? null;
            $sectionId = str_replace('section-', '', $rawId);

            $isExisting = is_numeric($sectionId);

            if (!is_null($sectionInfo['relation'])) {
                $relationship = PageSection::getRelationMapping($sectionInfo['relation']);
            }

            if ($isExisting) {
                $pageSection = PageSection::find($sectionId);

                if (!is_null($sectionInfo['relation'])) {
                    $relationship = PageSection::getRelationMapping($sectionInfo['relation']);
                }

                $pageSection->update([
                    'section_name' => $sectionInfo['name'] ?? '',
                    'order' => $sectionInfo['order'] ?? 0,
                    'section_type' => $sectionInfo['section_type'] ?? '',
                    'is_listable' => ($sectionInfo['is_listable'] ?? 0),
                    // 'related_section_id' => $sectionInfo['related_section_id'] ?? null,
                    // 'relation_type' => $relationship['direction'] ?? null,
                    // 'relation' => $relationship['method'] ?? null,
                ]);

                $receivedSectionIds[] = $pageSection->id;
            } else {
                $pageSection = PageSection::create([
                    'page_id' => $page->id,
                    'section_name' => $sectionInfo['name'] ?? '',
                    'order' => $sectionInfo['order'] ?? 0,
                    'section_type' => $sectionInfo['section_type'] ?? '',
                    'is_listable' => ($sectionInfo['is_listable'] ?? 0),
                    // 'related_section_id' => $sectionInfo['related_section_id'] ?? null,
                    // 'relation_type' => $relationship['direction'] ?? null,
                    // 'relation' => $relationship['method'] ?? null,
                ]);
            }

            $existingFieldIds = $pageSection->fields()->pluck('id')->toArray();
            $submittedFieldIds = [];

            foreach ($fieldsGroup as $fieldIndex => $fieldData) {
                if (empty($fieldData['field_name'])) continue;

                $fieldId = $fieldData['id'] ?? null;
                $fieldAttributes = [
                    'field_name' => $fieldData['field_name'],
                    'field_type' => $fieldData['field_type'],
                    'is_multiple' => isset($fieldData['is_multiple']) ? 1 : 0,
                    'is_required' => isset($fieldData['is_required']) ? 1 : 0,
                    'is_hidden'   => isset($fieldData['is_hidden']) ? 1 : 0,
                    'is_related' => isset($fieldData['is_related']) ? 1 : 0,
                ];

                if ($pageSection->is_listable) {
                    if ($fieldId && in_array($fieldId, $existingFieldIds)) {
                        $field = PageSectionField::find($fieldId);
                        $field->update($fieldAttributes);
                        $submittedFieldIds[] = $fieldId;
                    } else {
                        $field = PageSectionField::create(array_merge($fieldAttributes, ['page_section_id' => $pageSection->id]));
                        $submittedFieldIds[] = $field->id;
                    }
                } else {
                    $fieldValue = $fieldData['value'] ?? '';
                    if ($fieldData['field_type'] === 'image' && $request->hasFile("section.$sectionKey.fields.$fieldIndex.value")) {
                        $file = $request->file("section.$sectionKey.fields.$fieldIndex.value");
                        $fieldValue = $file->store('page-images', 'public');
                    } elseif ($fieldData['field_type'] === 'image') {
                        $fieldValue = $fieldData['current_value'] ?? '';
                    }

                    if ($fieldId && in_array($fieldId, $existingFieldIds)) {
                        $field = PageSectionField::find($fieldId);
                        $field->update($fieldAttributes);
                        $field->fieldData()->updateOrCreate([], ['value' => $fieldValue]);
                        $submittedFieldIds[] = $fieldId;
                    } else {
                        $field = PageSectionField::create(array_merge($fieldAttributes, ['page_section_id' => $pageSection->id]));
                        FieldData::create(['page_section_field_id' => $field->id, 'value' => $fieldValue]);
                        $submittedFieldIds[] = $field->id;
                    }
                }

                if (isset($fieldData['is_related'])) {
                    $field->relation()->updateOrCreate(
                        ['field_id' => $field->id],
                        [
                            'page_id'            => $page->id,
                            'section_id'         => $pageSection->id,
                            'related_page_id'    => $fieldData['related_page_id'] ?? null,
                            'related_section_id' => $fieldData['related_section_id'] ?? null,
                            'related_field_id'   => $fieldData['related_field_id'] ?? null,
                            'link_type'          => $fieldData['link_type'] ?? 'section',
                            'is_model'           => ($fieldData['link_type'] ?? '') === 'module' ? 1 : 0,
                        ]
                    );
                } else {
                    $field->relation()->delete();
                }

                if (in_array($fieldData['field_type'], ['radio', 'checkbox'])) {
                    $field->options()->delete();
                    if (!empty($fieldData['options'])) {
                        foreach ($fieldData['options'] as $idx => $label) {
                            if (!empty($label)) {
                                $field->options()->create([
                                    'option_label' => $label,
                                    'option_value' => \Str::slug($label),
                                    'order' => $idx
                                ]);
                            }
                        }
                    }
                } else {
                    $field->options()->delete();
                }
            }

            $fieldsToDelete = array_diff($existingFieldIds, $submittedFieldIds);
            if (!empty($fieldsToDelete)) {
                PageSectionField::whereIn('id', $fieldsToDelete)->delete();
            }
        }

        $sectionsToDelete = array_diff($existingSectionIds, $receivedSectionIds);
        PageSection::whereIn('id', $sectionsToDelete)->delete();

        $action = $request->action;
        return CmsHelper::returnPage($action, 'admin.cms-builder.pages.edit', 'admin.cms-builder.pages.index', 'Page updated successfully!', true, $page->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.cms-builder.pages.index')->with('success', 'Page deleted successfully!');
    }

    public function getPageSections(Page $page)
    {
        $sections = $page->sections()->select('id', 'section_name')->orderBy('order')->get();
        return response()->json($sections);
    }
    
    public function getSectionFields(PageSection $section)
    {
        $fields = $section->fields()->select('id', 'field_name')->get();

        return response()->json($fields);
    }
}
