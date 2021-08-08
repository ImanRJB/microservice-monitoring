<?php

namespace Milyoona\MicroserviceMonitor;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class MicroserviceMonitor extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('microservice-monitor', __DIR__ . '/../dist/js/tool.js');
        Nova::style('microservice-monitor', __DIR__ . '/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('microservice-monitor::navigation');
    }
}
