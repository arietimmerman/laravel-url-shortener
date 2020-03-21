<?php

namespace ArieTimmerman\Laravel\URLShortener\Http\Controllers;

use Illuminate\Http\Request;
use ArieTimmerman\Laravel\URLShortener\URL;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use ArieTimmerman\Laravel\URLShortener\Events\URLVisit;

/**
 * Controller for redirecting users to the URL behind the shortened URL
 */
class RedirectController extends Controller
{

    public function index(Request $request, $code)
    {

        $url = URL::where('code', $code)->firstOrFail();

        event(new URLVisit($url));

        return \response()->view('urlshortener::redirect', ["url" => $url], 301)->withHeaders(
            [
                'Content-Type' => "text/html; charset=utf-8",
                'Cache-Control' => "private, max-age=90",
                'Location' => $url->url
            ]
        );
    }
}
