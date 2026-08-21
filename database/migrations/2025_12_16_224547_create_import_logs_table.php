<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('PENDING_PREVIEW')
                ->comment('QUEUED, PENDING_PREVIEW, PROCESSING, COMPLETED, COMPLETED_WITH_ERRORS, FAILED');
            $table->string('temp_file_path')->nullable()->comment('Path to the extracted folder in storage/app/temp/');
            $table->timestamp('uploaded_at');
            $table->timestamp('completed_at')->nullable();
            $table->boolean('overwrite_media')->default(false);
            $table->text('error_message')->nullable();
            $table->text('note')->nullable();
            $table->json('extras')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
