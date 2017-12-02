<?php

/**
 * Redirectiong endpoint for initiating the OAuth2 Implicit Grant flow.
 * The retrieved access token can be used to call the APIs as protected with the provided middleware.
 * 
 * Note: this module does not provide any logic for extracting the access tokens from the url.
 * 
 */


Route::resource('urls', ArieTimmerman\Laravel\URLShortener\Http\Controllers\URLController::class);

Route::get('/url/code123', function (Request $request) {
	
	return "real url";
	
});


Route::get('{code}', 'ArieTimmerman\Laravel\URLShortener\Http\Controllers\RedirectController@index')->where('code', '.*');


