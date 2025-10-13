<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('lab_code')->nullable()->after('role');
        $table->string('logo')->nullable()->after('lab_code');
        $table->string('address')->nullable()->after('logo');
        $table->string('website')->nullable()->after('address');
        $table->string('mobile')->nullable()->after('website');
        $table->string('reference_lab')->nullable()->after('mobile');
        $table->text('note')->nullable()->after('reference_lab');
        $table->string('digital_signature')->nullable()->after('note');
        $table->string('qualification')->nullable()->after('digital_signature');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'lab_code',
            'logo',
            'address',
            'website',
            'mobile',
            'reference_lab',
            'note',
            'digital_signature',
            'qualification',
        ]);
    });
}
};
