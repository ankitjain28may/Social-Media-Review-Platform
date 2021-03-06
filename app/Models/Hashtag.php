<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

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

    public static function getHashtags($id = null)
    {
        $hashtags = [];

        $query = DB::table('hashtags');
        $query->where('hashtags.flag', 1);
        $query->join('twitter_handles', 'hashtags.twitter_handle_id', 'twitter_handles.id');
        $query->select('hashtags.*', 'twitter_handles.handle', 'twitter_handles.name as handle_name');

        if (!is_null($id)) {
            $query->where('hashtags.id', $id);
            $hashtags = $query->first();
        } else {
            $hashtags = $query->paginate(100);
            
        }

        return $hashtags;
    }
}
