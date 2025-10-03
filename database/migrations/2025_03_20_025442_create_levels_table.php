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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('level1')->nullable();
            $table->string('level2')->nullable();
            $table->string('level3')->nullable();
            $table->decimal('l_budget', 20, 2)->nullable();
            $table->string('encoded_by')->nullable();
            $table->longText('outcome')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
