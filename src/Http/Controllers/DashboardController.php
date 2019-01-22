<?php

namespace DigitalCloud\NovaBlogTool\Http\Controllers;

use Illuminate\Routing\Controller;
use DigitalCloud\NovaBlogTool\Bootstrap\Blog;

class DashboardController extends Controller
{
    public function checkMigrations() {
        return response()->json([
            'installed' => Blog::isInstalled(),
        ], 200);
    }

}
