<?php

namespace ArieTimmerman\Laravel\URLShortener;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * Model for the shortened URL and related details
 */
class URL extends Model
{

    protected $table = 'art_urls';

    //use makeVisible to make it visble
    protected $hidden = ['id'];

    protected $guarded = ['id', 'code'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                $model->{$model->getKeyName()} = Uuid::generate()->string;

                // Generate a code if not set manually
                if (!$model->code) {
                    $model->code = self::generateCode($model->url);
                }
            }
        );
    }

    public static function generateCode($url)
    {

        $code = "";

        $characters = \str_split(config("urlshortener.characterset"));
        $length = config("urlshortener.length_min");

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[\random_int(0, \count($characters) - 1)];
        }

        return $code;
    }

    public function __toString()
    {

        return (string) route('urlshortener.redirect', ['code' => $this->code]);
    }
}
