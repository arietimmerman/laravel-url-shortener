<?php

namespace ArieTimmerman\Laravel\OAuth2\Tests;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use ArieTimmerman\Laravel\OAuth2\Exceptions\InvalidAccessTokenException;

class BasicTest extends TestCase {
	
	protected function getEnvironmentSetUp($app) {
		$app ['config']->set ( 'authorizationserver.authorization_server_token_url', 'https://token_endpoint' );
		$app ['config']->set ( 'authorizationserver.authorization_server_introspect_url', 'https://introspect_endpoint' );
	}
	
	protected function getClientCredentialsTokenEndPoint() {
		return new Response ( 200, [ ], Psr7\stream_for ( '{
            "access_token":"2YotnFZFEjr1zCsicMWpAA",
            "token_type":"example",
            "expires_in":3600,
            "example_parameter":"example_value"
        }' ) );
	}
	
	public function testMissingRequiredScopes() {
		$this->expectException ( InvalidAccessTokenException::class );
		
		$middleware = new \ArieTimmerman\Laravel\OAuth2\VerifyAccessToken ();
		
		$mock = new MockHandler ( [ 
				
				$this->getClientCredentialsTokenEndPoint (),
				
				new Response ( 200, [ ], Psr7\stream_for ( '{
                "active": true,
                "client_id": "l238j323ds-23ij4",
                "username": "jdoe",
                "scope": "read write dolphin",
                "sub": "Z5O3upPC88QrAjx00dis",
                "aud": "https://protected.example.net/resource",
                "iss": "https://server.example.com/",
                "exp": 1419356238,
                "iat": 1419350238,
                "extension_field": "twenty-seven"
            }' ) ) 
		]
		 );
		
		$middleware->setClient ( new Client ( [ 
				'handler' => HandlerStack::create ( $mock ) 
		] ) );
		
		$request = Request::create ( 'http://example.com/admin', 'GET' );
		$request->headers->set ( 'Authorization', 'Bearer test123' );
		
		$response = $middleware->handle ( $request, function () {
			return true;
		}, "missing_scope" );
	}
	
	public function testRequiredScopePresent() {
		$middleware = new \ArieTimmerman\Laravel\OAuth2\VerifyAccessToken ();
		
		$mock = new MockHandler ( [ 
				
				$this->getClientCredentialsTokenEndPoint (),
				
				new Response ( 200, [ ], Psr7\stream_for ( '{
                    "active": true,
                    "client_id": "l238j323ds-23ij4",
                    "username": "jdoe",
                    "scope": "read write dolphin",
                    "sub": "Z5O3upPC88QrAjx00dis",
                    "aud": "https://protected.example.net/resource",
                    "iss": "https://server.example.com/",
                    "exp": 1419356238,
                    "iat": 1419350238,
                    "extension_field": "twenty-seven"
                }' ) ) 
		]
		 );
		
		$middleware->setClient ( new Client ( [ 
				'handler' => HandlerStack::create ( $mock ) 
		] ) );
		
		$request = Request::create ( 'http://example.com/admin', 'GET' );
		$request->headers->set ( 'Authorization', 'Bearer test123' );
		
		$response = $middleware->handle ( $request, function () {
			return true;
		}, "dolphin" );
		
		$this->assertTrue ( $response );
	}
	
	public function testTokenIsActive() {
		$middleware = new \ArieTimmerman\Laravel\OAuth2\VerifyAccessToken ();
		
		$mock = new MockHandler ( [ 
				
				$this->getClientCredentialsTokenEndPoint (),
				
				new Response ( 200, [ ], Psr7\stream_for ( '{
                        "active": true,
                        "client_id": "l238j323ds-23ij4",
                        "username": "jdoe",
                        "sub": "Z5O3upPC88QrAjx00dis",
                        "aud": "https://protected.example.net/resource",
                        "iss": "https://server.example.com/",
                        "exp": 1419356238,
                        "iat": 1419350238,
                        "extension_field": "twenty-seven"
                    }' ) ) 
		]
		 );
		
		$middleware->setClient ( new Client ( [ 
				'handler' => HandlerStack::create ( $mock ) 
		] ) );
		
		$request = Request::create ( 'http://example.com/admin', 'GET' );
		$request->headers->set ( 'Authorization', 'Bearer test123' );
		
		$response = $middleware->handle ( $request, function () {
			return true;
		} );
		
		$this->assertTrue ( $response );
	}
	
}
            
            
            