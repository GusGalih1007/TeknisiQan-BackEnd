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
        Schema::create('responses', function (Blueprint $table) {
            $table->uuid('reponsesId')->primary();
            $table->uuid('reportId');
            $table->text('solution');
            $table->json('photo');
            $table->datetime('responseDate');
            $table->enum('status', ['processed', 'delayed', 'solved'])->default('processed');
            $table->uuid('technicianId');
            $table->timestamps();

            $table->foreign('reportId')->references('reportId')->on('reports')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('technicianId')->references('userId')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
