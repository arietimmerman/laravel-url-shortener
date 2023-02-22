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

    public static function encode($num, $multiplier, $alphabet, $length_min)
    {
        if ($num >= pow(strlen($alphabet), $length_min)) {
            $length_min = ceil(log($num, strlen($alphabet)));
        }

        $result = '';

        $num = $num * $multiplier % pow(strlen($alphabet), $length_min);
        do {
            $result = $alphabet[$num % strlen($alphabet)] . $result;
            $num = intval($num / strlen($alphabet));
        } while ($num);

        $result = str_pad($result, $length_min, $alphabet[0], STR_PAD_LEFT);

        return $result;
    }

    public static function generateCode($url)
    {
        $code = "";

        $characters = config("urlshortener.characterset");
        $length = config("urlshortener.length_min");
        $multiplier = config("urlshortener.multiplier");

        $number = URL::count() + 1;

        $code = self::encode($number, $multiplier, $characters, $length);

        return $code;
    }

    public function __toString()
    {
        return (string) route('urlshortener.redirect', ['code' => $this->code]);
    }
}
