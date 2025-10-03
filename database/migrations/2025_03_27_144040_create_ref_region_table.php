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
        Schema::create('ref_region', function (Blueprint $table) {
            $table->string('regcode')->primary();
            $table->string('regcode_9');
            $table->string('nscb_reg_name');
            $table->string('regabbrev');
            $table->integer('UserLevelID');
            $table->string('addedby');
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_region');
    }
};
