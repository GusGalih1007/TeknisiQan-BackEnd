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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('reportId')->primary();
            $table->string('ticketNumber', 10);
            $table->uuid('unitId');
            $table->text('problem');
            $table->uuid('reportBy');
            $table->uuid('compId');
            $table->datetime('reportDate');
            $table->json('photo')->nullable();
            $table->timestamps();

            $table->foreign('unitId')->references('unitId')->on('units')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('compId')->references('compId')->on('companies')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('reportBy')->references('userId')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
