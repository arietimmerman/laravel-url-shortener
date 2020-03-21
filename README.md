
A minimal Laravel package for shortening URLs. Apart for creating short URLs - like bitly - it also supported updating URL redirects and tracking URL clicks.

# Laravel URL Shortener 

Install the package. It supports Laravel 5.5 and up (including 7.0 and up).

~~~
composer require arietimmerman/laravel-url-shortener
~~~

And start shortening URLs

~~~.php
(string)URLShortener::shorten("http://www.example.com");
~~~

# Usage for Laravel < 5.5

Add the _service provider_ in your `config/app.php`.

~~~.php
'providers' => [
     // [..]
    \ArieTimmerman\Laravel\URLShortener\ServiceProvider::class
     // [..]
];
~~~

## Optional

Publish the configuration and the view.

~~~.php
php artisan vendor:publish --provider="ArieTimmerman\Laravel\URLShortener\ServiceProvider"
~~~

Optionally, register for URLVisit events in your `EventServiceProvider`.

~~~.php
protected $listen = [
	'ArieTimmerman\Laravel\URLShortener\Events\URLVisit' => [
		'App\Listener\YourListener',
	]
];
~~~    

## Configuration

See `config/urlshortener.php`

## Docker

~~~
docker-compose build
docker-compose up
~~~
