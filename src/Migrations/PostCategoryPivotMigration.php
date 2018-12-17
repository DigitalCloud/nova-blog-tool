<?php

namespace DigitalCloud\NovaBlogTool\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PostCategoryPivotMigration
{
    public function up()
    {
        Schema::create('post_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_category');
    }
}
