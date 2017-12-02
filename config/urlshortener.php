<?php

return [

		'characterset' => env('AUTHORIZATION_SERVER_AUTHORIZATION_URL', "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"),
		'length_min' => env('AUTHORIZATION_SERVER_CLIENT_SECRET', 4)
		
];

