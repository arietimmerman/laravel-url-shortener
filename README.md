
URL Shortener 


Install the package on your resource server

~~~
composer require arietimmerman/arietimmerman/laravel-url-shortener
~~~

and add the Service Provider in your config/app.php

~~~.php
'providers' => [
     // [..]
    \ArieTimmerman\Laravel\URLShortener\ServiceProvider::class
     // [..]
];
~~~

publish

~~~.php
php artisan vendor:publish --provider="ArieTimmerman\Laravel\URLShortener\ServiceProvider"
~~~

