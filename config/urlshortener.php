<?php

return [
        
        // The characters used to generate an unique URL
        'characterset' => env('URLSHORTENER_CHARACTERSET', "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~"),
        
        // The minimum character length 
        'length_min' => env('URLSHORTENER_LENGTH_MIN', 4),
        
        // Optionally, an URL prefix. May be left empty. However, this would require registering the `routes` of this package after all your other routes
        'url_prefix' => env('URLSHORTENER_URL_PREFIX', ""),
        
        // Use a prefix for the generated URL code
        'url_prefix_code' => env('URLSHORTENER_URL_PREFIX_CODE', "__"),

        // Allows disabling the resource endpoint
        'route_resource_enabled' => env('URLSHORTENER_ROUTE_RESOURCE_ENABLED', true),

        'max_tries' => env('URLSHORTENER_MAX_TRIES', 20),

];

