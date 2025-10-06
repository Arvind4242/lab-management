<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('test_panels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('test_categories')->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('hide_interpretation')->default(false);
            $table->boolean('hide_method_instrument')->default(false);
            $table->text('tests')->nullable(); // store as JSON or comma-separated
            $table->longText('interpretation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_panels');
    }
};
