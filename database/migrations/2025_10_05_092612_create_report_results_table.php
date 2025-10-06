<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (!Schema::hasTable('report_results')) {
        Schema::create('report_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('report_test_id')->constrained('report_tests')->onDelete('cascade');
            $table->string('test_name')->nullable();
            $table->string('parameter_name')->nullable();
            $table->string('value')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->timestamps();
        });
    }
}

    public function down() {
        Schema::dropIfExists('report_results');
    }
};
