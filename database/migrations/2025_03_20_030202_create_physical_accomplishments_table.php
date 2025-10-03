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
        Schema::create('physical_accomplishments', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('project_name');
            $table->string('project_type');
            $table->string('year')->nullable();
            $table->string('quarter')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('actual', 10, 2)->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->string('project_type1')->nullable();
            $table->string('year1')->nullable();
            $table->string('quarter1')->nullable();
            $table->decimal('weight1', 10, 2)->nullable();
            $table->decimal('actual1', 10, 2)->nullable();
            $table->decimal('target1', 10, 2)->nullable();
            $table->decimal('overall_accomplishment', 10, 2)->nullable();
            $table->decimal('overall_target', 10, 2)->nullable();
            $table->decimal('slippage', 10, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->decimal('slippage_end_of_quarter', 10, 2)->nullable();
            $table->text('outcome_file')->nullable();
            $table->string('encoded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_accomplishments');
    }
};
