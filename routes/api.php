<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DigitalCloud\NovaBlogTool\Bootstrap\Blog;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/


Route::get('/check-migrations', 'DigitalCloud\NovaBlogTool\Http\Controllers\DashboardController@checkMigrations');

Route::get('/migrate-tables', 'DigitalCloud\NovaBlogTool\Http\Controllers\MigrationController@execute');
Route::get('/reset-content', 'DigitalCloud\NovaBlogTool\Http\Controllers\ResetController@execute');
Route::get('/uninstall', 'DigitalCloud\NovaBlogTool\Http\Controllers\UninstallController@execute');

