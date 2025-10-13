<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            // Add new columns for female and other
            $table->string('default_result_female')->nullable()->after('default_result');
            $table->string('default_result_other')->nullable()->after('default_result_female');
        });

        // Optional: initialize the new columns (e.g., copy male values)
        DB::table('tests')->update([
            'default_result_female' => DB::raw('default_result'),
            'default_result_other' => DB::raw('default_result'),
        ]);
    }

    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn(['default_result_female', 'default_result_other']);
        });
    }
};
