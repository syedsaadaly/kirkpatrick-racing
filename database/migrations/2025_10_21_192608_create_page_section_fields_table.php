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
        Schema::create('page_section_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained()->onDelete('cascade'); // Each field belongs to a section
            $table->string('field_name'); // e.g., 'hero_title', 'team_member_name'
            $table->string('field_type'); // e.g., 'text', 'textarea', 'image', etc.
            $table->text('default_value')->nullable(); // Default value for the field (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section_fields');
    }
};
