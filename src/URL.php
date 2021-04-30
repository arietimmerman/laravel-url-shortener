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
                    $model->code = self::generateUniqueCode();
                }
            }
        );
    }

    /**
     * Generates a unique code with the minimum length set in the configuration.
     * 
     * @return string
     */
    public static function generateUniqueCode()
    {
        $length = config("urlshortener.length_min");
        $characters = config("urlshortener.characterset");

        $attempts = 0;
        do {
            $code = '';
            $attempts++;

            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }

            if ($attempts > 2) {
                // Increases the code length after an existing code has been generated twice in a row.
                $length++;
                $attempts = 0;
            }
        } while (self::isCodeUsed($code)); // A new code is generated until it does not yet exist in the database.

        return $code;
    }

    /**
     * Checks if code is already in use. 
     * 
     * @param string $code
     * @return boolean
     */
    public static function isCodeUsed($code)
    {
        return URL::where('code', $code)->exists();
    }

    /**
     * @deprecated Does not test whether code already exists in the database before inserting.
     */
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
