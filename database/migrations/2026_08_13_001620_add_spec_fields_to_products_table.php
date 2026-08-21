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
        Schema::table('products', function (Blueprint $table) {
            $table->string('wheelbase')->nullable()->after('height');
            $table->string('range')->nullable()->after('wheelbase');
            $table->string('top_speed')->nullable()->after('range');
            $table->string('power')->nullable()->after('top_speed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['wheelbase', 'range', 'top_speed', 'power']);
        });
    }
};
