<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('report_results', function (Blueprint $table) {
            $table->text('interpretation')->nullable()->after('reference_range');
        });
    }

    public function down()
    {
        Schema::table('report_results', function (Blueprint $table) {
            $table->dropColumn('interpretation');
        });
    }
};
