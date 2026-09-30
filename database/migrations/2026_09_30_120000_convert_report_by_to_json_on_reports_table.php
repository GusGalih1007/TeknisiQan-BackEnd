<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->json('reportByJson')->nullable()->after('problem');
        });

        DB::table('reports')->whereNotNull('reportBy')->update([
            'reportByJson' => DB::raw("JSON_OBJECT('userId', reportBy)"),
        ]);

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reportBy']);
            $table->dropColumn('reportBy');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->renameColumn('reportByJson', 'reportBy');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('reportByName');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->json('reportBy')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->renameColumn('reportBy', 'reportByJson');
            $table->uuid('reportBy')->nullable()->after('problem');
            $table->string('reportByName', 60)->nullable()->after('reportBy');
        });

        DB::table('reports')->update([
            'reportBy' => DB::raw("JSON_UNQUOTE(JSON_EXTRACT(reportByJson, '$.userId'))"),
        ]);

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('reportByJson');
        });
    }
};
