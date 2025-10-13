<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Step 1: Add the column as nullable first
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // Step 2: Assign a default user to existing reports
        DB::table('reports')->update(['user_id' => 1]); // Replace 1 with a valid user ID

        Schema::table('reports', function (Blueprint $table) {
            // Step 3: Make the column non-nullable and add foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

