<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    if (!Schema::hasColumn('labs', 'user_id')) {
        Schema::table('labs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }
    // Skip FK — orphaned rows in existing data prevent it from being added safely
}




    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('labs', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
