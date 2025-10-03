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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('project_name')->nullable();
            $table->string('short_title')->nullable();
            $table->string('funding_source')->nullable();
            $table->string('donor')->nullable();
            $table->string('depdev')->nullable();
            $table->string('management')->nullable();
            $table->string('gph')->nullable();
            $table->string('fund_type')->nullable();
            $table->string('fund_management')->nullable();
            $table->string('desk_officer')->nullable();
            $table->longText('alignment')->nullable();
            $table->string('environmental')->nullable();
            $table->longText('health_facility')->nullable();
            $table->longText('development_objectives')->nullable();
            $table->longText('sector')->nullable();
            $table->string('sites')->nullable();
            $table->longText('site_specific_reg')->nullable();
            $table->longText('site_specific_prov')->nullable();
            $table->longText('site_specific_city')->nullable();
            $table->text('agreement')->nullable();
            // $table->string('uhc')->nullable();
            // $table->string('uhc_is')->nullable();
            // $table->string('classification')->nullable();
            $table->string('status')->nullable();
            $table->string('completed_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
