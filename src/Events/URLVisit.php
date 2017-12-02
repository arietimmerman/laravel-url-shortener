<?php

namespace ArieTimmerman\Laravel\URLShortener\Events;

use Illuminate\Queue\SerializesModels;
use ArieTimmerman\Laravel\URLShortener\URL;

class URLVisit{
	
	use SerializesModels;
	
	public $url;
	
	public function __construct(URL $url)
	{
		$this->url = $url;
	}
	
}