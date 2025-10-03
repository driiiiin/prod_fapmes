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
        Schema::create('implementation_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('extension')->nullable();
            $table->string('encoded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementation_schedules');
    }
};