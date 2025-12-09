<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_results', function (Blueprint $table) {
            $table->unsignedInteger('display_order')
                  ->default(0)
                  ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('report_results', function (Blueprint $table) {
            $table->dropColumn('display_order');
        });
    }
};
