<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->integer('age');
            $table->string('gender');
            $table->date('test_date');
            $table->string('referred_by')->nullable(); // new field
            $table->string('client_name')->nullable();  // new field
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('reports');
    }
};
