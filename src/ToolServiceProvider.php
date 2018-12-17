<?php

namespace DigitalCloud\NovaBlogTool;

use DigitalCloud\NovaBlogTool\Bootstrap\Blog;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use DigitalCloud\NovaBlogTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * The registered additional fields.
     */
    public static $additionalFields = [];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-blog-tool');

        $this->app->booted(function () {
            $this->routes();

            Blog::injectToolResources();
        });

        $this->publishes([
            $this->configPath() => config_path('nova-blog.php'),
        ], 'nova-blog-config');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-blog-tool')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'nova-blog');
    }

    /**
     * @return string
     */
    protected function configPath()
    {
        return __DIR__.'/../config/nova-blog.php';
    }

    /**
     * Register the given additional fields.
     *
     * @param  array  $additionalFields
     * @return void
     */
    public static function additionalFields(array $additionalFields)
    {
        static::$additionalFields = array_merge(static::$additionalFields, $additionalFields);
    }

    /**
     * Get registered additional fields .
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function availableAdditionalFields(Request $request)
    {
        return collect(static::$additionalFields)
            ->filter
            ->authorize($request)
            ->all();
    }

}
