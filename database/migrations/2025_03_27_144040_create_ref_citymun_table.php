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
        Schema::create('ref_citymun', function (Blueprint $table) {
            $table->string('regcode');
            $table->string('provcode');
            $table->string('citycode')->primary();
            $table->string('regcode_9');
            $table->string('provcode_9');
            $table->string('citycode_9');
            $table->string('cityname');
            $table->string('geographic_level');
            $table->string('old_names')->nullable();
            $table->string('cityclass');
            $table->string('incomeclass');
            $table->string('addedby');
            $table->string('UserLevelID');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_citymun');
    }
};
