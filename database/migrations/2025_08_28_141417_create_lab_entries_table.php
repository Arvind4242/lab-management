<?php

// database/migrations/xxxx_xx_xx_create_lab_entries_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lab_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->string('test_type');
            $table->decimal('amount', 10, 2);
            $table->date('test_date');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lab_entries');
    }
};

