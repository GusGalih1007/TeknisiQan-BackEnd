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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('compId')->primary();
            $table->string('name', 60);
            $table->uuid('leaderId');
            $table->text('address');
            $table->text('logo');
            $table->timestamps();

            $table->foreign('leaderId')->references('userId')->on('users')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('compId')->after('photo')->nullable();

            $table->foreign('compId')->references('compId')->on('companies')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
