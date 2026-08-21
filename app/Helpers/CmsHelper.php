<?php

namespace App\Helpers;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\PageSectionItem;

class CmsHelper
{
    /**
     * Get page data by slug
     *
     * @param $pageSlug
     * @param null $sectionSlug
     * @param null $fieldSlug
     * @return array
     */
    public static function getPageData($pageSlug, $sectionSlug = null, $fieldSlug = null): array
    {
        $query = Page::with([
            'sections',
            'sections.fields',
            'sections.fields.media',
            'sections.fields.fieldData',
            'sections.items.fieldData.field'
        ])->where('slug', $pageSlug);

        $query = $query->with(['sections' => function ($sectionQuery) use ($sectionSlug, $fieldSlug) {

            if ($sectionSlug) {
                // $sectionQuery->where('slug', $sectionSlug);
                $sectionQuery->where('section_name', $sectionSlug);
            }

            $sectionQuery->with(['fields' => function ($fieldQuery) use ($fieldSlug) {
                if ($fieldSlug) {
                    // $fieldQuery->where('slug', $fieldSlug);
                    $fieldQuery->where('field_name', $fieldSlug);
                }
            }]);
        }]);

        if ($sectionSlug) {
            $query = $query->whereHas('sections', function ($query) use ($sectionSlug) {
                // $query->where('slug', $sectionSlug);
                $query->where('section_name', $sectionSlug);
            });
        }

        if ($fieldSlug) {
            $query = $query->whereHas('sections.fields', function ($query) use ($fieldSlug) {
                // $query->where('slug', $fieldSlug);
                $query->where('field_name', $fieldSlug);
            });
        }

        $results = $query->first();

        if (!$results) {
            return [];
        }

        return collect($results->sections)
            ->mapWithKeys(function ($section) {
                $sectionData = collect($section['fields'])
                    ->mapWithKeys(function ($field) {
                        if ($field->field_type === 'image') {
                            $mediaUrl = $field->getFirstMediaUrl('page-images');
                            $value = $mediaUrl ?: ($field->fieldData->value ?? '');
                        } else {
                            $value = $field->fieldData->value ?? '';
                        }

                        $value = $field->is_multiple && $field->field_type !== 'image'
                            ? ($value ? json_decode($value, true) : [])
                            : $value;

                        return [$field['field_name'] => $value];
                    })
                    ->toArray();

                if ($section->is_listable) {
                    $sectionData['_items'] = collect($section->items)
                        ->map(function ($item) {
                            return collect($item->fieldData)
                                ->mapWithKeys(function ($fieldData) {
                                    $value = $fieldData->value;

                                    if ($fieldData->field->field_type === 'image') {
                                        $mediaUrl = $fieldData->getFirstMediaUrl('section-items');
                                        $value = $mediaUrl ?: $value;
                                    }

                                    return [
                                        $fieldData->field->field_name => $value
                                    ];
                                })
                                ->toArray();
                        })
                        ->toArray();
                }

                return [$section['section_name'] => $sectionData];
            })
            ->toArray();
    }

    public static function returnPage($action, $currentRouteName = null, $nextRouteName = null, $message = 'Saved successfully', $withInput = false, $pageId = null)
    {
        switch ($action) {
            case 'save':
            $redirect = redirect()->route($currentRouteName, $pageId)->with('success', $message);
                break;

            case 'save_close':
                $redirect = redirect()->route($nextRouteName, $pageId)->with('success', $message);
                break;

            default:
                $redirect = redirect()->back()->with('error', 'Action not recognized');
                break;
        }

        if ($withInput) {
            $redirect = $redirect->withInput();
        }

        return $redirect;
    }
}
