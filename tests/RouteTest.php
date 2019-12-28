<?php

namespace ArieTimmerman\Laravel\OAuth2\Tests;

use Orchestra\Testbench\TestCase;
use ArieTimmerman\Laravel\URLShortener\URL;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    
    protected function getEnvironmentSetUp($app)
    {
        $app ['config']->set('urlshortener.route_resource_enabled', false);
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
            
            
            