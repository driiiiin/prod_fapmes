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
        Schema::create('ref_prov', function (Blueprint $table) {
            $table->string('regcode');
            $table->string('provcode')->primary();
            $table->string('regcode_9');
            $table->string('provcode_9');
            $table->string('provname');
            $table->string('old_names')->nullable();
            $table->string('incomeclass')->nullable();
            $table->string('addedby')->nullable();
            $table->string('UserLevelID')->nullable();
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_prov');
    }
};
