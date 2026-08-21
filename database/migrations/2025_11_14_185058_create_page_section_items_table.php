<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::table('field_data', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->after('page_section_field_id')
                  ->constrained('page_section_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('field_data', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn('item_id');
        });

        Schema::dropIfExists('page_section_items');
    }
};
