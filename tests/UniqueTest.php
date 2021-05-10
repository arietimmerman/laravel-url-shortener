<?php

namespace ArieTimmerman\Laravel\URLShortener\Tests;

use ArieTimmerman\Laravel\URLShortener\URLShortener;

class UniqueTest extends TestCase
{
    private static $lengthMin = 2;
    private static $characterset = 'AB';

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app ['config']->set('urlshortener.characterset', self::$characterset);
        $app ['config']->set('urlshortener.length_min', self::$lengthMin);
    }

    public function testUrlCode()
    {
        $all = [];
        for ($i = 0; $i<12; $i++) {
            $code = (string)URLShortener::shorten("http://www.example.com/" . $i);
            $all[] = $code;
        }

        $this->assertEquals(12, count(array_unique($all)));
    }
}
