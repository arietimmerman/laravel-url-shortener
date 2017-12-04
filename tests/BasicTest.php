<?php

namespace ArieTimmerman\Laravel\OAuth2\Tests;

use Orchestra\Testbench\TestCase;
use ArieTimmerman\Laravel\URLShortener\URL;

class BasicTest extends TestCase {
	
	private static $lengthMin = 4;
	private static $characterset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
	
	protected function getEnvironmentSetUp($app) {
		$app ['config']->set ( 'urlshortener.characterset', self::$characterset );
		$app ['config']->set ( 'urlshortener.length_min', self::$lengthMin );
	}
	
	public function testUrlCode() {
		
		for($i = 0;$i<10000;$i++){
			
			$code = URL::generateCode("http://www.example.com");
			
			//Check if the length is correct
			$this->assertTrue( strlen($code) == 4 );
			
			//Check if all characters are allowed
			$this->assertTrue( strlen(str_replace(str_split(self::$characterset),"",$code)) == 0 );
			
		}
		
	}
	
	
	
}
            
            
            