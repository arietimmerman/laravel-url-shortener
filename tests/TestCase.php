<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->runDatabaseMigrations();
    }
    
    protected function getEnvironmentSetUp($app)
    {
        $app ['config']->set('app.url', 'http://localhost');

        $app['config']->set('app.key', 'base64:1234mRasdLA123F0JiF02Og3bLXbk5qPE8H3+vX2O5M=');

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
    
    protected function getPackageProviders($app)
    {
        return [
            \ArieTimmerman\Laravel\URLShortener\ServiceProvider::class,
        ];
    }
}
