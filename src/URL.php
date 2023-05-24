<?php

namespace ArieTimmerman\Laravel\URLShortener;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    protected static function boot(): void
    {
        parent::boot();
        static::creating(
            function (URL $url) {
                $url->{$url->getKeyName()} = Uuid::generate()->string;
                if (!$url->code) {
                    $url->code = $url->generateCode();
                }
            }
        );
    }


    /**
     *
     * Generates a unique code for the short url with uniqueness verification
     *
     * @return string
     * @throws Exception If amount of retries exceeded
     */

    public function generateCode(): string
    {
        $tries = 0;

        while ($tries < config("urlshortener.max_tries")) {
            $key = Str::random(config("urlshortener.length_min"));
            if (false === $this->newQuery()->where('code', $key)->exists()) {
                return $key;
            }
            $tries++;
        }
        throw new Exception("Unable to generate unique token");
    }

    public function __toString()
    {
        return (string) route('urlshortener.redirect', ['code' => $this->code]);
    }
}
