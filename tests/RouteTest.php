<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests;

use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app ['config']->set('urlshortener.route_resource_enabled', false);
        $app ['config']->set('urlshortener.url_prefix_code', '');
        
    }
    
    public function testRoutesRegistered()
    {

        URLShortener::routes();
        
        $paths = collect(Route::getRoutes()->getRoutes())->map(
            function ($value) {
                return $value->uri;
            }
        )->toArray();

        $this->assertContains('{code}', $paths);
        $this->assertCount(1, $paths);

    }
    
}
            
            
            