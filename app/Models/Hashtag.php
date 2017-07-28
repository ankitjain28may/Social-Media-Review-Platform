<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $table = 'hashtags';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'flag', 'twitter_handle_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    public static function getHashtag($name, $twitter_handle_id = NULL)
    {
        $query = Self::where('name', $name);
        if (is_null($twitter_handle_id)) {
            $query->where('twitter_handle_id', $twitter_handle_id);
        }
        $query->where('flag', 1);

        $hashtag = $query->first();

        return $hashtag;
    }
}
