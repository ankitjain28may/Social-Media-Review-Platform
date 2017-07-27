<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwitterUsersHandle extends Model
{
    protected $table = 'twitter_user_handles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'handle', 'flag',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getHandles()
    {
        $handles = Self::where('flag', 1)->get();

        return $handles;
    }
}
