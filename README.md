
A Laravel package for shortening URLs. Apart for creating short URLs - like bitly - it also supported updating URL redirects and tracking URL clicks.

# Laravel URL Shortener 

Install the package 

~~~
composer require arietimmerman/laravel-url-shortener
~~~

and add the _service provider_ in your `config/app.php`.

~~~.php
'providers' => [
     // [..]
    \ArieTimmerman\Laravel\URLShortener\ServiceProvider::class
     // [..]
];
~~~

Publish the configuration and the view.

~~~.php
php artisan vendor:publish --provider="ArieTimmerman\Laravel\URLShortener\ServiceProvider"
~~~

Now register the Laravel _routes_. Open your `AppServiceProvider` and populate the `boot()` function with the following.

~~~.php
public function boot() {
    // [...]
	
	\ArieTimmerman\Laravel\URLShortener\URLShortener::routes();
	
	// [...]
}
~~~

## Optional

Optionally, register for URLVisit events in your `EventServiceProvider`.

~~~.php
protected $listen = [
	'ArieTimmerman\Laravel\URLShortener\Events\URLVisit' => [
		'App\Listener\YourListener',
	]
];
~~~    

# Configuration

See `config/urlshortener.php`
