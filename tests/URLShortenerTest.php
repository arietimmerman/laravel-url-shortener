<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests;

use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;

class URLShortenerTest extends TestCase
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

    public function testShortenURL() {
        $url = URLShortener::shorten("https://example.com");

        $this->assertEquals(4, Str::length($url->code));
    }

}
