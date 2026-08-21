<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\PageSectionItem;
use App\Models\FieldData;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class FieldDataImport implements ToCollection, WithHeadingRow
{

    protected $fieldMap;
    protected $sectionId;
    protected $totalImported = 0;

    public function __construct(array $fieldMap, int $sectionId)
    {
        $this->fieldMap = $fieldMap;
        $this->sectionId = $sectionId;
    }

    /**
     * @param Collection $rows
     * @return void
     * @throws \Exception
     */
    public function collection(Collection $rows)
    {
        $currentMaxOrder = PageSectionItem::where('page_section_id', $this->sectionId)->max('order') ?? 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                $item = PageSectionItem::create([
                    'page_section_id' => $this->sectionId,
                    'order' => ++$currentMaxOrder,
                ]);

                foreach ($this->fieldMap as $csvHeader => $fieldData) {

                    $csvKey = strtolower(str_replace(' ', '_', $csvHeader));

                    if ($row->has($csvKey)) {
                        $csvValue = $row[$csvKey];
                        $dbFieldId = $fieldData['id'];
                        $dbFieldType = $fieldData['type'];
                        $finalValue = $csvValue;

                        if ($dbFieldType === 'image' && $fieldData['download_media'] == 1 && !empty($csvValue)) {

                            // --- IMAGE DOWNLOAD LOGIC ---
                            $finalValue = '';
                            if ($this->isValidUrl($csvValue)) {
                                try {
                                    $mediaItem = $item->addMediaFromUrl($csvValue)
                                        ->toMediaCollection('section-items');
                                    $finalValue = $mediaItem->file_name;

                                } catch (FileCannotBeAdded $e) {
                                    $finalValue = '';
                                    // throw new \Exception("Import failed on row {1 + $this->totalImported}: " . $e->getMessage());
                                }
                            }
                        }

                        $item->fieldData()->create([
                            'page_section_field_id' => $dbFieldId,
                            'value' => $finalValue ?? '',
                        ]);
                    }
                }

                $this->totalImported++;
                DB::commit();

            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Import failed on row {1 + $this->totalImported}: " . $e->getMessage());
        }
    }

    public function getImportedCount(): int
    {
        return $this->totalImported;
    }

    /**
     * Helper function to quickly validate URL syntax and basic availability
     *
     * @param $url
     * @return bool
     */
    protected function isValidUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        try {
            $response = Http::head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
