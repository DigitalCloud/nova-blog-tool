<?php

namespace DigitalCloud\NovaBlogTool\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PostMigration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->json('title')->unique();
            $table->json('content');
            $table->json('category')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('menu_order')->default(0);

            $table->string('status')->default('draft');
            $table->boolean('published')->default(false);
            $table->boolean('featured')->default(false);

            $table->timestamp('post_date')->useCurrent();
            $table->timestamp('modified_date')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->timestamp('scheduled_for')->useCurrent();

            $table->string('featured_image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
