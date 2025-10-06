<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

 public function up(): void
{
    // Check if 'tests' table already exists
    if (!Schema::hasTable('tests')) {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->foreignId('category_id')->constrained('test_categories')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // units table
            $table->string('input_type'); // text, number, etc.
            $table->text('default_result')->nullable();
            $table->boolean('optional')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('method')->nullable();
            $table->string('instrument')->nullable();
            $table->text('interpretation')->nullable();
            $table->timestamps();
        });
    }
}


    public function down(): void {
        Schema::dropIfExists('tests');
    }
};

