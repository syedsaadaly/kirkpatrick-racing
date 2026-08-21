<?php

namespace App\Jobs;

use App\Models\ExportLog;
use App\Models\ImportLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackupImportProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importLog;
    protected $tempPath;

    /**
     * Create a new job instance.
     */
    public function __construct(ImportLog $importLog)
    {
        $this->importLog = $importLog;
        $this->tempPath = storage_path('app/private/' . $importLog->temp_file_path);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $importLog = $this->importLog;
        $tempPath = $this->tempPath;
        $importLog->update(['status' => 'PROCESSING']);


        // Initialize summary
        $importSummary = [
            'tables' => [],
            'media_stats' => [],
            'errors' => []
        ];
        $dataOnly = false;

        $mapping = [];
        $errorLogs = [];
        $processedTables = 0;

        try {
            $cmsTablesSeq = ExportLog::EXPORT_TABLE_SEQUENCES;

            $allFiles = collect(File::files($tempPath . '/data'))
                ->keyBy(fn($file) => $file->getFilenameWithoutExtension());

            $backupFiles = collect($cmsTablesSeq)
                ->map(fn($tableName) => $allFiles->get($tableName))
                ->filter();

            Schema::disableForeignKeyConstraints();

            foreach ($backupFiles as $backupFile) {
                $tableName = str_replace('.json', '', $backupFile->getFilename());

                try {
                    $content = File::get($backupFile->getRealPath());
                    $fileData = json_decode($content, true);
                    $records = $fileData['records'] ?? $fileData;

                    if (is_array($records) && !empty($records)) {
                        // individual process row-wise track
                        $this->insertRecord($tableName, $records, $tempPath, $mapping, $dataOnly ,$importSummary);
                        $processedTables++;
                    }
                } catch (\Exception $e) {
                    // Table level error
                    $errorLogs[] = [
                        'table' => $tableName,
                        'error' => 'Critical Table Error: ' . $e->getMessage(),
                        'type' => 'table_error'
                    ];
                }

            }

            Schema::enableForeignKeyConstraints();

            // Status aur Error Message
            $finalStatus = count($errorLogs) > 0 ? 'COMPLETED_WITH_ERRORS' : 'COMPLETED';

            $importLog->update([
                'status' => $finalStatus,
                'completed_at' => now(),
                'error_message' => count($errorLogs) > 0 ? json_encode($errorLogs) : null,
                'note' => $importLog->note . " | Processed: $processedTables tables, Errors: " . count($errorLogs)
            ]);

        } catch (\Exception $e) {
            // Global system failure logic
            $importLog->update([
                'status' => 'FAILED',
                'error_message' => json_encode([['global_error' => $e->getMessage(), 'line' => $e->getLine()]])
            ]);
            throw $e;
        }
    }

    private function insertRecord($tableName, $records, $tempPath, &$mapping = [], $dataOnly, &$importSummary)
    {
        $shouldOverwrite = $this->currentImportLog->overwrite_records;

        foreach ($records as $record) {
            if (!is_array($record)) continue;

            $oldId = $record['id'] ?? null;
            $validColumns = \Schema::getColumnListing($tableName);
            $attributes = [];
            $nestedData = [];

            // Prepare attributes and handle foreign key mapping
            foreach ($record as $key => $value) {
                if (in_array($key, $validColumns)) {
                    $attributes[$key] = (str_ends_with($key, '_id') && isset($mapping[$key][$value]))
                        ? $mapping[$key][$value]
                        : $value;
                } elseif (is_array($value)) {
                    $nestedData[$key] = $value;
                }
            }
            unset($attributes['id']);

            // Determine if we are updating or inserting
            $uniqueIdentifier = $this->getUniqueIdentifier($tableName, $validColumns);
            $existingId = $this->findExistingRecordId($tableName, $attributes, $uniqueIdentifier, $oldId);

            if ($existingId) {
                if (!$shouldOverwrite) continue; // Ignore if overwrite is off
                $newId = $this->handleUpdate($tableName, $existingId, $attributes);
            } else {
                $newId = $this->handleInsert($tableName, $attributes);
            }

            // Track Stats & Update Mapping
            $importSummary['tables'][$tableName] = ($importSummary['tables'][$tableName] ?? 0) + 1;
            if ($oldId && $newId) {
                $mapping[\Str::singular($tableName) . '_id'][$oldId] = $newId;
            }

            // Media and Recursion (Same as before)
            $this->processRecordMedia($tableName, $newId, $attributes, $tempPath, $dataOnly, $importSummary);
            $this->processNestedData($tableName, $newId, $nestedData, $tempPath, $mapping, $dataOnly, $importSummary);
        }
    }

    /**
     * Handles logic for existing records
     */
    private function handleUpdate($tableName, $existingId, $attributes)
    {
        if ($tableName === 'pages') {
            $sectionIds = DB::table('page_sections')->where('page_id', $existingId)->pluck('id');
            $fieldDataIds = DB::table('field_data')
                ->whereIn('page_section_field_id', function($query) use ($sectionIds) {
                    $query->select('id')->from('page_section_fields')->whereIn('page_section_id', $sectionIds);
                })->pluck('id');

            foreach ($fieldDataIds as $fdId) {
                $model = \App\Models\FieldData::find($fdId);
                if ($model) {
                    $model->clearMediaCollection('section-items');
                    $model->clearMediaCollection('content_images');
                }
            }

            // 3. Delete the records from our CMS tables
            DB::table('field_data')->whereIn('id', $fieldDataIds)->delete();
            DB::table('page_sections')->where('page_id', $existingId)->delete();
            DB::table('pages')->where('id', $existingId)->delete();

            // 4. Re-insert the Page (The recursive process will re-add the new media)
            return DB::table('pages')->insertGetId($attributes);
        }

        DB::table($tableName)->where('id', $existingId)->update($attributes);
        return $existingId;
    }

    /**
     * Handles logic for brand new records
     */
    private function handleInsert($tableName, $attributes)
    {
        return DB::table($tableName)->insertGetId($attributes);
    }

    /**
     * Helper to find existing records based on unique identifiers or old IDs
     */
    private function findExistingRecordId($tableName, $attributes, $uniqueIdentifier, $oldId)
    {
        if ($uniqueIdentifier && isset($attributes[$uniqueIdentifier])) {
            return DB::table($tableName)->where($uniqueIdentifier, $attributes[$uniqueIdentifier])->value('id');
        }

        // Fallback to checking if the original ID exists in our DB (optional)
        if ($oldId) {
            return DB::table($tableName)->where('id', $oldId)->value('id');
        }

        return null;
    }
    private function processRecordMedia($tableName, $newId, $attributes, $tempPath, $dataOnly, &$importSummary)
    {
        if (!$dataOnly && $newId && $tableName === 'field_data') {
            if (isset($attributes['value']) && str_starts_with($attributes['value'], 'media/')) {
                $modelClass = $this->processSpatieMedia($newId, $attributes['value'], $tempPath);
                if ($modelClass) {
                    $importSummary['media_stats'][$modelClass] = ($importSummary['media_stats'][$modelClass] ?? 0) + 1;
                }
            }
        }
    }

    private function processNestedData($tableName, $newId, $nestedData, $tempPath, &$mapping, $dataOnly, &$importSummary)
    {
        foreach ($nestedData as $childTable => $childRecords) {
            if (\Schema::hasTable($childTable)) {
                $foreignKey = $this->resolveForeignKey($tableName, $childTable);
                if ($foreignKey && $newId) {
                    $preparedChildren = collect($childRecords)->map(function ($child) use ($foreignKey, $newId) {
                        $child[$foreignKey] = $newId;
                        return $child;
                    })->toArray();

                    $this->insertRecord($childTable, $preparedChildren, $tempPath, $mapping, $dataOnly, $importSummary);
                }
            }
        }
    }

    private function processSpatieMedia($fieldDataId, $relativeZipPath, $tempPath)
    {
        try {
            $fieldDataRecord = DB::table('field_data')->find($fieldDataId);
            if (!$fieldDataRecord) return;

            $fullSourcePath = $this->resolveMediaFilePath($tempPath, $relativeZipPath);
            if (!$fullSourcePath || !File::exists($fullSourcePath)) return;

            $model = null;
            $collection = 'content_images';


            if ($fieldDataRecord->item_id) {
                $model = \App\Models\FieldData::find($fieldDataId);
                $collection = 'section-items';
            } else {
                $model = \App\Models\PageSectionField::find($fieldDataRecord->page_section_field_id);
                $collection = 'page-images';
            }

            if ($model) {
                // Add to Spatie
                $media = $model->addMedia($fullSourcePath)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);

                // Update the field_data value column with the new public URL
                DB::table('field_data')
                    ->where('id', $fieldDataId)
                    ->update(['value' => asset($media->getUrl())]);
                return class_basename($model);
            }
        } catch (\Exception $e) {
            \Log::error("Media Import Error: " . $e->getMessage());
        }
    }

    /**
     * Helper to find the file inside nested ZIP folders
     */
    private function resolveMediaFilePath($tempPath, $relativeZipPath)
    {
        $path = rtrim($tempPath, '/') . '/' . ltrim($relativeZipPath, '/');
        if (File::exists($path)) return $path;

        $subDirs = File::directories($tempPath);
        foreach ($subDirs as $dir) {
            $path = $dir . '/' . ltrim($relativeZipPath, '/');
            if (File::exists($path)) return $path;
        }
        return null;
    }


    private function resolveForeignKey($parentTable, $childTable)
    {
        $fullSingular = \Str::singular($parentTable) . '_id';
        $parts = explode('_', $parentTable);
        $shortSingular = \Str::singular(end($parts)) . '_id';

        if (\Schema::hasColumn($childTable, $fullSingular)) return $fullSingular;
        if (\Schema::hasColumn($childTable, $shortSingular)) return $shortSingular;

        return null;
    }

    /**
     * Dynamically guess a unique identifier for upserting
     */
    private function getUniqueIdentifier($tableName, $columns)
    {
        if ($tableName === 'field_data') return null; // Force new insertion after the parent-level delete

        if (in_array('slug', $columns)) return 'slug';
        if (in_array('field_name', $columns)) return 'field_name';
        return null;
    }
}
