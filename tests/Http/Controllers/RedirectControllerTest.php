<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests\Http\Controllers;

use ArieTimmerman\Laravel\URLShortener\Http\Controllers\RedirectController;
use ArieTimmerman\Laravel\URLShortener\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class RedirectControllerTest extends TestCase
{

    use RefreshDatabase;

    private static $lengthMin = 4;
    private static $characterset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';

    protected function getPackageProviders($app)
    {
        return [
            "ArieTimmerman\\Laravel\\URLShortener\\ServiceProvider"
        ];
    }


    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('urlshortener.characterset', self::$characterset);
        $app['config']->set('urlshortener.length_min', self::$lengthMin);
        $app['config']->set('urlshortener.max_tries', 20);
    }

    public function testControllerRedirect() {
        $url = URL::create([
            "url" => "http://example.com"
        ]);

        $reponse = $this->get('__'.$url->code);

        $reponse->assertStatus(301);
        $reponse->assertHeader("Location", $url->url);
    }

    public function testNonExistingLink() {
        $response = $this->get('__d5s6');
        $response->assertStatus(404);
    }

}
