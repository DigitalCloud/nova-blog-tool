<?php

namespace DigitalCloud\NovaBlogTool\Bootstrap;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Schema;
use DigitalCloud\NovaBlogTool\Resources\Tag;
use DigitalCloud\NovaBlogTool\Resources\Post;
use DigitalCloud\NovaBlogTool\Resources\Comment;
use DigitalCloud\NovaBlogTool\Resources\Category;

class Blog
{
    public static function isInstalled()
    {
        if (!env('APP_KEY'))
            return true;

        return
            Schema::hasTable('posts') &&
            Schema::hasTable('categories') &&
            Schema::hasTable('comments') &&
            Schema::hasTable('tags') &&
            Schema::hasTable('post_category');
    }

    public static function injectToolResources()
    {
        if (!self::isInstalled()) {
            return;
        }

        Nova::resources([
            Category::class,
            Post::class,
            Comment::class,
            Tag::class,
        ]);
    }
}
