<?php

namespace ArieTimmerman\Laravel\URLShortener;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class URL extends Model
{
	
	protected $table = 'art_urls';
	
	//use makeVisible to make it visble
	protected $hidden = ['id'];
	
	protected $guarded = ['id','code'];
	
	public $incrementing = false;
	
	protected static function boot()
	{
		parent::boot();
	
		static::creating(function ($model) {
			$model->{$model->getKeyName()} = Uuid::generate()->string;
		});
	}
	
	public static function generateCode($url){
	
		$code = "";
	
		$characters = \str_split(config("urlshortener.characterset"));
		$length = config("urlshortener.length_min");
	
		for($i = 0 ; $i < $length;$i++){
			$code .= $characters[\random_int(0, \count($characters)-1)];
		}
	
		return $code;
	
	}
	
	
}
