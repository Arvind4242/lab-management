<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // First, ensure any NULLs are valid JSON (to prevent conversion failure)
        DB::statement("UPDATE test_panels SET tests = '[]' WHERE tests IS NULL OR tests = ''");

        // Now safely alter the column
        Schema::table('test_panels', function (Blueprint $table) {
            $table->json('tests')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('test_panels', function (Blueprint $table) {
            $table->longText('tests')->nullable()->change();
        });
    }
};
