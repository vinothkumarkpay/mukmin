<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToWidgetsTable extends Migration
{
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->json('settings')->nullable()->after('content');
        });
    }

    public function down()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('settings');
        });
    }
}
