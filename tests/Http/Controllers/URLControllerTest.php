<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests\Http\Controllers;

use ArieTimmerman\Laravel\URLShortener\Http\Controllers\URLController;
use ArieTimmerman\Laravel\URLShortener\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Orchestra\Testbench\TestCase;

class URLControllerTest extends TestCase
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
        $app['config']->set('urlshortener.route_resource_enabled', true);
    }

    public function testListURLs() {

        for ($i = 0; $i < 200; $i++) {
            URL::create([
                "url" => "http://example.com"
            ]);
        }

        $response = $this->get('/urls');

        $response->assertStatus(200);
        $response->assertHeader("Content-Type", "application/json");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data", 100)
                ->has('data.0', fn (AssertableJson $json) =>
                    $json->where('url', 'http://example.com')
                        ->where('code', fn (string $code) => Str::length($code) == 4)
                        ->etc()
                )
                ->etc()
        );
    }

    public function testCreateURL() {
        $response = $this->post('/urls', [
            "url" => "https://example.com"
        ]);
        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('url', "https://example.com")->etc()
        );
    }

    public function testCreateWithInvalidUrl() {
        $response = $this->post('/urls', [
           "url" => "invalid-url"
        ]);
        $response->assertStatus(422);
    }

    public function testShowURL() {
        $url = URL::create(["url" => "http://example.com"]);
        $response = $this->get("/urls/".$url->id);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('url', $url->url)->where('code', fn (string $code) =>
                strlen($code) == self::$lengthMin && strlen(str_replace(str_split(self::$characterset), "", $url->code)) == 0
            )->etc()
        );
    }

    public function testShowURLNotFound() {
        $response = $this->get('/urls/79dbb39b-1dd0-465c-83b6-f9f391144138');
        $response->assertStatus(404);
    }

    public function testUpdateURL() {
        /**
         * @var URL $url
         */
        $url = URL::create(["url" => "http://example.com"]);
        $response = $this->put('/urls/'.$url->id, [
            "url" => "http://example.fr"
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('url', "http://example.fr")->where('code', $url->code)->etc()
        );
        $url->refresh();
        $this->assertEquals("http://example.fr", $url->url);
    }

    public function testDestroyURL() {
        $url = URL::create(["url" => "http://example.com"]);
        $response = $this->delete('/urls/'.$url->id);
        $response->assertStatus(204);

        $this->assertDatabaseMissing($url, ['id' => $url->id]);
    }

}
