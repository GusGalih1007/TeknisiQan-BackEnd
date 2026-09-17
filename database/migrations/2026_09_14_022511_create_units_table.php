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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('unitId')->primary();
            $table->string('unitNumber', 10);
            $table->string('unitName', 60);
            $table->uuid('compId');
            $table->uuid('roomId');
            $table->timestamps();

            $table->foreign('compId')->references('compId')->on('companies')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('roomId')->references('roomId')->on('rooms')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
