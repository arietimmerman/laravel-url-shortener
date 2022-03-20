<?php

namespace ArieTimmerman\Laravel\OAuth2\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use ArieTimmerman\Laravel\URLShortener\URL;

class BasicTest extends TestCase
{
    private static $lengthMin = 4;
    private static $characterset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';

    protected function getPackageProviders($app)
    {
        return [\ArieTimmerman\Laravel\URLShortener\ServiceProvider::class];
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('urlshortener.characterset', self::$characterset);
        $app['config']->set('urlshortener.length_min', self::$lengthMin);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Returns random string of a given length.
     * 
     * @return string
     */
    public static function generateRandomString($length = 10)
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789!@#$%^&*()');
        $rand = '';
        foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

        return $rand;
    }

    /**
     * Tests whether the code length corresponds to the minimum set length.
     */
    public function testCodeMinLength()
    {
        for ($i = 0; $i < 1000; $i++) {
            $lengthMin = random_int(1, 200);
            Config::set("urlshortener.length_min", $lengthMin);

            $code = URL::generateUniqueCode();

            //Check if the length is correct
            $this->assertTrue(strlen($code) >= $lengthMin);
        }
    }

    /**
     * Tests whether the code actually consists of the permitted characters.
     */
    public function testCodeAllowedChars()
    {

        for ($i = 0; $i < 1000; $i++) {
            $characterset =  self::generateRandomString();
            Config::set("urlshortener.characterset", $characterset);

            $code = URL::generateUniqueCode();

            //Check if all characters are allowed
            $this->assertTrue(strlen(str_replace(str_split($characterset), "", $code)) == 0);
        }
    }

    /**
     * Tests that codes are successfully checked for duplicates.
     * Creates code of length 1 with the character A. If this already exists, length is set to 2 etc.
     */
    public function testDuplicateCodeNotPossible()
    {
        Config::set("urlshortener.characterset", 'A');
        Config::set("urlshortener.length_min", 1);

        for ($i = 1; $i < 100; $i++) {
            $shortUrl = URL::create(['url' => 'https://example.com']);
            $expectedCode = str_repeat('A', $i);

            $this->assertEquals($expectedCode, $shortUrl->code);
            $this->assertDatabaseHas('art_urls', [
                'code' => $expectedCode
            ]);
        }
    }
}
