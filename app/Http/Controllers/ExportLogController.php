<?php

namespace App\Http\Controllers;

use App\Jobs\BackupImportProcessJob;
use App\Models\ExportLog;
use App\Models\ImportLog;
use App\Models\PageSection;
use App\Models\PageSectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ExportLogController extends Controller
{
    private $currentImportLog;
    /**
     * Display a listing of the export logs.
     * Mapped to: GET /admin/export/logs
     */
    public function index()
    {

        $exportLogs = ExportLog::with('user')
            ->latest('exported_at')
            ->paginate(15);

        return view('admin.export.logs.index', compact('exportLogs'));
    }

    /**
     * Show the form for creating a new selective export.
     * Mapped to: GET /admin/export/logs/selective/create
     */
    public function createSelectiveExport()
    {
        $tableGroups = ExportLog::EXPORT_TABLES;
        $requiredDependencies = ExportLog::CMS_TABLE_DEPENDENCIES;

        return view('admin.export.logs.create', compact('tableGroups', 'requiredDependencies'));
    }

    /**
     * Show the form for creating a new selective export.
     * Mapped to: GET /admin/export/logs/selective/create
     */
    /**
     * Helper method to define static dependencies (jaisa createSelectiveExport mein tha)
     */
    protected function getRequiredDependencies()
    {
        return ExportLog::CMS_TABLE_DEPENDENCIES;
    }

    /**
     * Helper method to clean up temporary directory
     */
    protected function cleanupTempDirectory($dir)
    {
        if (is_dir($dir)) {
            File::deleteDirectory($dir);
        }
    }

    /**
     * Handle the submission of the selective export form.
     * Mapped to: POST /admin/export/logs/selective
     */
    public function storeSelectiveExport(Request $request)
    {
        // Validation
        $request->validate([
            'selected_tables' => 'required|array|min:1',
            'media_handling' => 'required|in:files,data_only',
        ], [
            'selected_tables.required' => 'Please select at least one table to export.',
            'media_handling.required' => 'Please select how media files should be handled.',
        ]);

        $selectedTables = $request->input('selected_tables');
        $mediaHandling = $request->input('media_handling');

        // --- Dependency Resolution (Finalizing the list) ---
        $finalTableList = $selectedTables;

        // --- Data Export Setup (Temporary folder) ---
        $tempDirName = 'export_temp_' . time();
        $tempPath = storage_path('app/temp/' . $tempDirName);
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // NEW CODE ADDITION: Sub-directories banana zaroori hai!
        $dataPath = $tempPath . '/data';
        $mediaPath = $tempPath . '/media';

        // Create the 'data' sub-directory (Jahaan pages.json file jaayegi)
        if (!is_dir($dataPath)) {
            mkdir($dataPath, 0777, true);
        }

        // Create the 'media' sub-directory (Agar media handling 'files' ho toh)
        if ($mediaHandling === 'files' && !is_dir($mediaPath)) {
            mkdir($mediaPath, 0777, true);
        }

        try {
            // --- Export Table Data ---
            foreach ($finalTableList as $tableName) {

                $records = [];
                DB::table($tableName)->orderBy('id')->chunk(100, function ($rows) use (&$records, $tableName) {
                    foreach ($rows as $row) {
                        $rowArray = (array)$row;

                        // Check if this table has dependencies defined
                        if ($tableName === 'pages') {
                            // Fetch Sections for this Page
                            $rowArray['page_sections'] = DB::table('page_sections')
                                ->where('page_id', $row->id)
                                ->get()
                                ->map(function ($section) {
                                    $sectionArray = (array)$section;

                                    // 1. Fetch Fields for this Section (Non-Listable)
                                    $sectionArray['page_section_fields'] = DB::table('page_section_fields')
                                        ->where('page_section_id', $section->id)
                                        ->get()
                                        ->map(function ($field) use ($section) {
                                            $fieldArray = (array)$field;

                                            if (!$section->is_listable) {
                                                $fieldArray['field_data'] = DB::table('field_data')
                                                    ->where('page_section_field_id', $field->id)
                                                    ->get()
                                                    ->map(function ($data) {
                                                        $dataArray = (array)$data;
                                                        // REWRITE URL TO ZIP PATH
                                                        if (str_contains($dataArray['value'], '/storage/')) {
                                                            $parts = explode('/storage/', $dataArray['value']);
                                                            $dataArray['value'] = 'media/' . end($parts);
                                                        }
                                                        return $dataArray;
                                                    })->toArray();
                                            }
                                            return $fieldArray;
                                        })->toArray();

                                    // 2. Fetch Items for this Section (Listable)
                                    $sectionArray['page_section_items'] = DB::table('page_section_items')
                                        ->where('page_section_id', $section->id)
                                        ->get()
                                        ->map(function ($item) {
                                            $itemArray = (array)$item;
                                            $itemArray['field_data'] = DB::table('field_data')
                                                ->where('item_id', $item->id)
                                                ->get()
                                                ->map(function ($data) {
                                                    $dataArray = (array)$data;
                                                    // REWRITE URL TO ZIP PATH
                                                    if (str_contains($dataArray['value'], '/storage/')) {
                                                        $parts = explode('/storage/', $dataArray['value']);
                                                        $dataArray['value'] = 'media/' . end($parts);
                                                    }
                                                    return $dataArray;
                                                })->toArray();
                                            return $itemArray;
                                        })->toArray();

                                    return $sectionArray;
                                })->toArray();
                        }

                        $records[] = $rowArray;
                    }
                });

                $jsonFormat = [
                    'table_name' => $tableName,
                    'records' => $records,
                    'records_exported' => count($records),
                ];

                file_put_contents("{$dataPath}/{$tableName}.json", json_encode($jsonFormat, JSON_PRETTY_PRINT));
            }

            // --- Handle Media ---
            if ($mediaHandling === 'files') {
                $allExportedMedia = [];
                $processedPaths = [];

                // 1. DYNAMIC SPATIE MEDIA EXPORT
                foreach ($finalTableList as $tableName) {
                    $modelClass = 'App\\Models\\' . \Str::studly(\Str::singular($tableName));
                    $exportedIds = DB::table($tableName)->pluck('id')->toArray();

                    if (!empty($exportedIds)) {
                        $mediaRecords = DB::table('media')
                            ->where('model_type', $modelClass)
                            ->whereIn('model_id', $exportedIds)
                            ->get();

                        foreach ($mediaRecords as $media) {
                            $relativePath = "{$media->id}/{$media->file_name}";
                            $sourceAbsolutePath = storage_path("app/public/{$relativePath}");

                            if (\File::exists($sourceAbsolutePath)) {
                                $destFolder = "{$mediaPath}/{$media->id}";
                                if (!is_dir($destFolder)) mkdir($destFolder, 0777, true);

                                \File::copy($sourceAbsolutePath, "{$destFolder}/{$media->file_name}");

                                $allExportedMedia[] = (array)$media;
                                $processedPaths[] = $relativePath;
                            }
                        }
                    }
                }

                // 2. MANUAL FIELD_DATA MEDIA EXPORT (Scans for files not in Spatie table)
                $manualMediaEntries = DB::table('field_data')
                    ->where('value', 'LIKE', '%/storage/%')
                    ->get();

                foreach ($manualMediaEntries as $entry) {
                    $urlParts = explode('/storage/', $entry->value);
                    $relativePath = end($urlParts);

                    if (in_array($relativePath, $processedPaths)) continue;

                    $sourceAbsolutePath = storage_path("app/public/" . $relativePath);

                    if (\File::exists($sourceAbsolutePath)) {
                        $pathInfo = pathinfo($relativePath);
                        $destFolder = "{$mediaPath}/" . $pathInfo['dirname'];

                        if (!is_dir($destFolder)) mkdir($destFolder, 0777, true);

                        \File::copy($sourceAbsolutePath, "{$destFolder}/" . $pathInfo['basename']);
                        $processedPaths[] = $relativePath;
                    }
                }

                // 3. Save Media Metadata for DB reference
                if (!empty($allExportedMedia)) {
                    file_put_contents("{$dataPath}/media.json", json_encode([
                        'table_name' => 'media',
                        'records' => $allExportedMedia,
                    ], JSON_PRETTY_PRINT));
                }
            }

            // --- Archiving and Private Storage ---
            $fileName = 'export_' . now()->format('Ymd_His') . '_' . uniqid() . '.zip';
            // Final path for the zip file (storage/app/private/exports)
            $zipFilePath = storage_path('app/private/exports/' . $fileName);
            $filePathForDB = 'exports/' . $fileName; // Path for database record

            // Check and create the exports directory if it doesn't exist
            if (!is_dir(storage_path('app/private/exports'))) {
                mkdir(storage_path('app/private/exports'), 0755, true);
            }

            // Create the ZIP Archive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Could not create ZIP archive at: ' . $zipFilePath);
            }

            // Add files from the temporary directory to the zip archive
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                // Skip directories (unless it's the root temp path)
                if (!$file->isDir()) {
                    $relativePath = substr($file->getPathname(), strlen($tempPath) + 1);
                    $zip->addFile($file->getPathname(), $relativePath);
                }
            }
            $zip->close();

            // --- Log Creation ---
            $log = ExportLog::create([
                'user_id' => Auth::id(),
                'exported_at' => now(),
                'selected_tables' => json_encode($finalTableList),
                'media_handling' => $mediaHandling,
                'backup_file_path' => $filePathForDB,
                'is_backup_available' => true,
                'notes' => $request->notes
            ]);

            // --- Cleanup ---
            $this->cleanupTempDirectory($tempPath);

            // --- Response ---
            return redirect()->route('admin.export.logs.index')
                ->with('success', 'Export process completed successfully. Log ID #' . $log->id . ' created.')
                ->with('download_url', route('admin.export.logs.download', $log->id));

        } catch (\Exception $e) {
            // Cleanup temp folder if export failed
            $this->cleanupTempDirectory($tempPath);

            // Check if a partially created zip file exists and try to delete it
            if (isset($zipFilePath) && file_exists($zipFilePath)) {
                File::delete($zipFilePath);
            }

            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage())->withInput();
        }
    }

    private function getModelMappings($tables)
    {
        // Aapki tables aur unke Models ka mapping
        $map = [
            'pages' => 'App\Models\Page',
            'posts' => 'App\Models\Post',
            'users' => 'App\Models\User',
            // Baaki models yahan add karein
        ];

        return array_intersect_key($map, array_flip($tables));
    }

    /**
     * Serves the saved backup file for download.
     * Mapped to: GET /admin/export/logs/{log}
     */
    public function download(ExportLog $log)
    {
        if (!$log->is_backup_available || !$log->backup_file_path) {
            return redirect()->route('admin.export.logs.index')
                ->with('error', 'Error: The requested backup file is not available or has been deleted.');
        }

        $relativePath = $log->backup_file_path;
        $absolutePath = storage_path($relativePath);

        // Check if the file exists on the server
        if (!file_exists(Storage::path($relativePath))) {
            return redirect()->route('admin.export.logs.index')
                ->with('error', 'Error: Backup file was found in the database but is missing from the server storage.');
        }

        try {
            $fileName = basename($absolutePath);

            return Storage::download($relativePath, $fileName);

        } catch (\Exception $e) {
            return redirect()->route('admin.export.logs.index')
                ->with('error', 'An error occurred during file download: ' . $e->getMessage());
        }
    }

    /**
     * Re-runs a fresh export using the parameters stored in a previous log entry.
     * Mapped to: POST /admin/export/logs/{log}/re-export
     */
    public function reExport(ExportLog $log)
    {
        // 1. Fetch old parameters from the log entry
        // Purane log se selected tables aur media handling choice nikal rahe hain
        $oldSelectedTables = json_decode($log->selected_tables, true);
        $oldMediaHandling = $log->media_handling;

        if (empty($oldSelectedTables)) {
            return redirect()->route('admin.export.logs.index')
                ->with('error', 'Re-Export failed: No tables were recorded in the original log entry.');
        }

        $finalTableList = array_unique($oldSelectedTables);
        $mediaHandling = $oldMediaHandling;

        $tempDirName = 're_export_temp_' . time();
        $tempPath = storage_path('app/temp/' . $tempDirName);
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        try {
            // $exportService->exportTables($finalTableList, $tempPath);
            file_put_contents($tempPath . '/table_data.json', json_encode(['re_export_tables' => $finalTableList, 'source_log_id' => $log->id]));

            if ($mediaHandling === 'files') {
                // $exportService->copyMediaFiles($tempPath);
                file_put_contents($tempPath . '/media_info.txt', 'Media files included for Re-Export.');
            }

            $fileName = 're_export_' . now()->format('Ymd_His') . '_from_log_' . $log->id . '.zip';
            $storagePath = 'private/';
            $filePath = $storagePath . $fileName;
            $zipFilePath = storage_path('app/' . $filePath);

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Could not create the ZIP archive.');
            }

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $relativePath = substr($file, strlen($tempPath) + 1);
                    $zip->addFile($file, $relativePath);
                }
            }
            $zip->close();

            $newLog = ExportLog::create([
                'user_id' => Auth::id(),
                'exported_at' => now(),
                'selected_tables' => json_encode($finalTableList),
                'media_handling' => $mediaHandling,
                'backup_file_path' => $filePath,
                'is_backup_available' => true,
                'notes' => 'Re-Export from Log ID: ' . $log->id,
            ]);

            $this->cleanupTempDirectory($tempPath);

            return redirect()->route('admin.export.logs.index')
                ->with('success', 'Re-Export based on Log ID #' . $log->id . ' completed successfully. New Log ID #' . $newLog->id . ' created.')
                ->with('download_url', route('admin.export.logs.download', $newLog->id));

        } catch (\Exception $e) {
            // Cleanup temp folder if export failed
            $this->cleanupTempDirectory($tempPath);
            return redirect()->route('admin.export.logs.index')
                ->with('error', 'Re-Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified export log and its associated backup file permanently.
     * Mapped to: DELETE /admin/export/logs/{log}
     */
    public function destroy(Request $request, ExportLog $log)
    {
        $logId = $log->id;
        $permanentDelete = $request->permanent_delete ?? false;
        $filePath = $log->backup_file_path;

        if ($filePath && $log->is_backup_available) {
            try {
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }

                $log->update([
                    'is_backup_available' => false,
                    'backup_file_path' => null,
                ]);

            } catch (\Exception $e) {
                return redirect()->route('admin.export.logs.index')
                    ->with('error', 'Error: Backup file could not be deleted from the server storage: ' . $e->getMessage());
            }
        }

        if ($permanentDelete) {
            try {
                $log->delete();
            } catch (\Exception $e) {
                return redirect()->route('admin.export.logs.index')
                    ->with('error', 'Error: Export log #' . $logId . ' could not be deleted from the database: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.export.logs.index')
            ->with('success', 'Export log #' . $logId . ' and its associated backup file have been permanently deleted (non-recoverable).');
    }

    /**
     * Display the view for creating an import log in the administration section.
     */
    public function createImport()
    {
        return view('admin.export.logs.create_import');
    }

    /**
     * Handles the import process for a backup file uploaded by the user.
     *
     * This method processes the backup file by validating the input,
     * uploading the ZIP file to a private temporary folder, extracting its contents,
     * and performing initial validation checks. If successful, it redirects the user
     * to the next step in the import process while storing the temporary folder path in the session.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance containing the backup file and options.
     * @return \Illuminate\Http\RedirectResponse Redirects to the appropriate page with success or error messages.
     * @throws \Exception If the ZIP file cannot be opened, or the required structure/file is missing.
     */
    public function storeImport(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:102400',
            'overwrite_records' => 'nullable|boolean',
        ]);

        $file = $request->file('backup_file');
        $tempDirName = 'import_temp_' . time() . '_' . uniqid();

        $baseTempPath = storage_path('app/private/temp');
        $specificTempPath = $baseTempPath . '/' . $tempDirName;
        $diskPathForDB = 'temp/' . $tempDirName;

        try {
            if (!File::isDirectory($specificTempPath)) {
                File::makeDirectory($specificTempPath, 0775, true);
            }

            $fileName = $file->getClientOriginalName();

            $file->move($specificTempPath, $fileName);

            $zipFilePath = $specificTempPath . '/' . $fileName;

            if (!File::exists($zipFilePath)) {
                throw new \Exception("File upload failed to move to: " . $zipFilePath);
            }

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath) !== TRUE) {
                throw new \Exception('Could not open the uploaded ZIP file.');
            }

            $zip->extractTo($specificTempPath);
            $zip->close();

            if (!File::isDirectory($specificTempPath . '/data')) {
                throw new \Exception('The backup file is missing the mandatory "data" directory.');
            }

            $importLog = ImportLog::create([
                'user_id' => Auth::id(),
                'status' => 'PENDING_PREVIEW',
                'temp_file_path' => $diskPathForDB,
                'uploaded_at' => now(),
                'overwrite_records' => $request->boolean('overwrite_records'),
                'note' => $request->input('note'),
            ]);

            File::delete($zipFilePath);

            return redirect()->route('admin.export.logs.import.preview', $importLog->id)
                ->with('success', 'File uploaded and extracted. Review the tables before restoration.');

        } catch (\Exception $e) {
            if (File::isDirectory($specificTempPath)) {
                File::deleteDirectory($specificTempPath);
            }

            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handles the preview process for an import log.
     *
     * This method validates the status of the provided import log to ensure
     * it is eligible for preview, processes the temporary files to extract
     * and count the records in the import data, and prepares a preview
     * for the admin to review the data before starting or continuing
     * the import process.
     *
     * @param ImportLog $importLog The import log instance being previewed.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function preview(ImportLog $importLog)
    {
        // Path jahan humne file extract ki thi
        $tempPath = storage_path('app/private/' . $importLog->temp_file_path);
        $dataPath = $tempPath . '/data';

        if (!File::isDirectory($dataPath)) {
            return redirect()->back()
                ->with('error', 'Extracted data directory not found.');
        }

        $tableFiles = [];
        $totalRecords = 0;

        // Scan all JSON files in the /data directory
        $files = File::files($dataPath);
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $tableName = str_replace('.json', '', $fileName);

            // Count records in JSON
            $content = json_decode(File::get($file->getPathname()), true);
            $count = is_array($content) ? count($content) : 0;

            $tableFiles[] = [
                'table' => $tableName,
                'count' => $count,
                'file' => $fileName
            ];
            $totalRecords += $count;
        }

        return view('admin.export.logs.import_preview', compact('importLog', 'tableFiles', 'totalRecords'));
    }

    /**
     * Confirms the initiation of the import process for the given import log.
     *
     * This method checks the status of the provided ImportLog to verify if
     * the import process can begin. If eligible, a background job is
     * dispatched to handle the import, the import log status is updated,
     * and a success message is returned. Otherwise, an error message is
     * returned if the process has already started or completed.
     *
     * @param ImportLog $importLog The import log instance to be processed.
     * @return \Illuminate\Http\RedirectResponse The response to redirect the user with a status message.
     */
    public function confirmImport(Request $request , ImportLog $importLog)
    {
//        if ($importLog->status !== 'PENDING_PREVIEW') {
//            return redirect()->route('admin.export.logs.import.index')->with('error', 'Import process already started or finished.');
//        }
        $dataOnly = $request->has('data_only');
        $this->importLogsData($importLog, $dataOnly);
//        BackupImportProcessJob::dispatch($importLog);

        $importLog->update(['status' => 'QUEUED']);

        return redirect()->route('admin.export.logs.import.index')
            ->with('success', "Import process started successfully in the background (Log ID: {$importLog->id}). Check the Import Logs for status updates.");
    }

    public function importLogsData(ImportLog $importLog, $dataOnly = false)
    {
        $this->currentImportLog = $importLog;
        $importLog->update(['status' => 'PROCESSING']);
        $tempPath = storage_path('app/private/' . $importLog->temp_file_path);

        // Initialize summary
        $importSummary = [
            'tables' => [],
            'media_stats' => [],
            'errors' => []
        ];

        $mapping = [];
        DB::beginTransaction();
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
                        // Pass $importSummary by reference
                        $this->insertRecord($tableName, $records, $tempPath, $mapping, $dataOnly, $importSummary);
                    }
                } catch (\Exception $e) {
                    $importSummary['errors'][] = "Table $tableName: " . $e->getMessage();
                }
            }

            Schema::enableForeignKeyConstraints();

            // Save detailed summary to the log
            $importLog->update([
                'status' => count($importSummary['errors']) > 0 ? 'COMPLETED_WITH_ERRORS' : 'COMPLETED',
                'completed_at' => now(),
                'error_message' => count($importSummary['errors']) > 0 ? json_encode($importSummary['errors']) : null,
                'extras' => [
                    'stats' => $importSummary['tables'],
                    'media_stats' => $importSummary['media_stats'],
                    'media_processed' => array_sum($importSummary['media_stats'])
                ],
                'note' => "Imported " . count($importSummary['tables']) . " tables and " . array_sum($importSummary['media_stats']) . " media files."
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $importLog->update(['status' => 'FAILED', 'error_message' => $e->getMessage()]);
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

    protected function checkNextTable($columnsToInsert, $record)
    {
        $keys2 = array_keys($record);

        $all_unique = array_merge(
            array_diff($columnsToInsert, $keys2),
            array_diff($keys2, $columnsToInsert)
        );

        unset($all_unique['id']);

        if (empty($all_unique)) {
            return null;
        }

        return $all_unique;
    }

    /**
     * Displays the imported logs index view with paginated import logs data.
     */
    public function importLogsIndex()
    {
        $importLogs = ImportLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.export.logs.import_index', compact('importLogs'));
    }

    public function retryImport(ImportLog $importLog)
    {
        if (!$importLog->canBeRetried()) {
            return back()->with('error', 'Cannot retry: Temporary files are missing or job is not in failed status.');
        }

        $importLog->update([
            'status' => 'QUEUED',
            'error_message' => null
        ]);

        BackupImportProcessJob::dispatch($importLog);

        return back()->with('success', 'Import job has been re-queued.');
    }

    public function detailImport(ImportLog $importLog)
    {
        $tempDataPath = storage_path("app/private/{$importLog->temp_file_path}/data");

        $tableFiles = [];
        $totalRecords = 0;
        $pagesData = [];

        if (File::exists($tempDataPath)) {
            $files = File::files($tempDataPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'json') {
                    $content = json_decode(File::get($file->getRealPath()), true);
                    $records = $content['records'] ?? $content;
                    $count = is_array($records) ? count($records) : 0;

                    $tableName = $file->getFilenameWithoutExtension();

                    $tableFiles[] = [
                        'table' => $tableName,
                        'count' => $count,
                        'file'  => $file->getFilename()
                    ];

                    $totalRecords += $count;

                    // Keep pages data specifically if needed for deep preview
                    if ($tableName === 'pages') {
                        $pagesData = $records;
                    }
                }
            }
        }

        // Sort tables alphabetically
        usort($tableFiles, fn($a, $b) => strcmp($a['table'], $b['table']));

        return view('admin.export.logs.details', compact('importLog', 'tableFiles', 'totalRecords', 'pagesData'));
    }
}
