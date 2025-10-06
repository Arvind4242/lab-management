<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('test_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('fee', 10, 2)->nullable();
            $table->enum('gender', ['Both', 'Male', 'Female'])->default('Both');
            $table->timestamps();
        });

        // Pivot tables for many-to-many relations
        Schema::create('test_package_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
        });

        Schema::create('test_package_panel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_panel_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_package_panel');
        Schema::dropIfExists('test_package_test');
        Schema::dropIfExists('test_packages');
    }
};
