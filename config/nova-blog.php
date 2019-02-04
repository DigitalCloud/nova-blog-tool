<?php

return [
    'resources' => [
        'users' => [
            'model' => env('BLOG_USER_MODEL', App\User::class),
        ],

        'posts' => [
            'search' => ['id', 'title', 'content'],
        ],

        'categories' => [
            'search' => ['id', 'name', 'description'],
        ],

        'comments' => [
            'search' => ['id', 'name'],
        ],

        'tags' => [
            'search' => ['id', 'name'],
        ],
    ],

    'user_model' => env('BLOG_USER_MODEL', App\User::class),
];
