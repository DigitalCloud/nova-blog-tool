<?php

namespace DigitalCloud\NovaBlogTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaBlogTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-blog-tool', __DIR__.'/../dist/js/tool.js');
        Nova::style('nova-blog-tool', __DIR__.'/../dist/css/tool.css');
	
		Nova::script('post-form', __DIR__.'/../dist/js/post-form.js');
		Nova::style('post-form', __DIR__.'/../dist/css/post-form.css');
        
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-blog-tool::navigation');
    }
}
