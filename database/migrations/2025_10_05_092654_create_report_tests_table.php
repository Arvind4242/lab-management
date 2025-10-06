<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('report_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade'); // parent Report
            $table->foreignId('test_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('test_panel_id')->nullable()->constrained('test_panels')->onDelete('set null');
            $table->string('test_name')->nullable(); // make nullable to prevent form errors
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('report_tests');
    }
};
