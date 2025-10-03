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
        Schema::create('ref_level2', function (Blueprint $table) {
            $table->string('level1_code');
            $table->string('level1_desc');
            $table->string('level2_code')->primary();
            $table->string('level2_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_level2');
    }
};

