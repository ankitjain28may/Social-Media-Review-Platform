<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbUser extends Model
{

	protected $table = 'fb_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'fb_id', 'access_token', 'expires_in', 'flag', 'code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getAccessToken($user_id)
    {
        $access_token = FbUser::where('user_id', $user_id)->where('flag', 1)->first()->access_token;
        return $access_token;
    }
}
