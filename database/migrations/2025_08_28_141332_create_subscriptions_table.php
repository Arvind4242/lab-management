<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('lab_id');
    $table->enum('plan', ['free_trial', 'monthly', 'yearly']);
    $table->date('start_date');
    $table->date('end_date')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
});


    }

    public function down(): void {
        Schema::dropIfExists('subscriptions');
    }
};
