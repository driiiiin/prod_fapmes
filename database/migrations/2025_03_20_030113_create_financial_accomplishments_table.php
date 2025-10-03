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
        Schema::create('financial_accomplishments', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('project_name');
            $table->decimal('orig_budget', 20, 2)->nullable();
            $table->string('currency')->nullable();
            $table->decimal('rate', 20, 2)->nullable();
            $table->decimal('budget', 20, 2)->nullable();
            $table->decimal('lp', 20, 2)->nullable();
            $table->decimal('gp', 20, 2)->nullable();
            $table->decimal('gph_counterpart', 20, 2)->nullable();
            $table->decimal('disbursement', 20, 2)->nullable();
            $table->decimal('p_disbursement', 5, 2)->nullable();
            $table->string('encoded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_accomplishments');
    }
};
