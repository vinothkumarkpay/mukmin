<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWidgetsOnlyToCmsPagesTable extends Migration
{
    public function up()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->boolean('widgets_only')->default(false)->after('body');
        });
    }

    public function down()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn('widgets_only');
        });
    }
}
