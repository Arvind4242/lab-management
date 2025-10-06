<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
       Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('lab_id')->nullable(); // Each user belongs to a lab
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'user'])->default('user');
    $table->rememberToken();
    $table->timestamps();

    $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
});

    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
