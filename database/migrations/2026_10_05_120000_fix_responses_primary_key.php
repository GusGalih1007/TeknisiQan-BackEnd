<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename the typo column from reponsesId to responsesId
        Schema::table('responses', function (Blueprint $table) {
            $table->renameColumn('reponsesId', 'responsesId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->renameColumn('responsesId', 'reponsesId');
        });
    }
};
