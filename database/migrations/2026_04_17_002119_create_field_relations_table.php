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
        Schema::create('field_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('field_id')->nullable();
            $table->unsignedBigInteger('related_page_id')->nullable();
            $table->unsignedBigInteger('related_section_id')->nullable();
            $table->unsignedBigInteger('related_field_id')->nullable();
            $table->string('link_type')->default('section')->nullable();
            $table->string('relationship_type')->default('one_to_one')->nullable();
            $table->boolean('is_model')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_relations');
    }
};
