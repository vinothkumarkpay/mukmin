<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCmsPageIdToWidgetsTable extends Migration
{
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->foreignId('cms_page_id')
                ->nullable()
                ->after('zone')
                ->constrained('cms_pages')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropForeign(['cms_page_id']);
        });
    }
}
