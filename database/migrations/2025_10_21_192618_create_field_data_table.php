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
        Schema::create('field_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_field_id')->constrained()->onDelete('cascade');
            $table->text('value')->comment('field types textarea, text, image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_data');
    }
};
