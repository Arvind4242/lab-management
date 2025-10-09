<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('test_panel_id')->nullable()->after('client_name');
            $table->foreign('test_panel_id')
                  ->references('id')
                  ->on('test_panels')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['test_panel_id']);
            $table->dropColumn('test_panel_id');
        });
    }
};

