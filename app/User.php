<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Group;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'avatar', 'group_id', 'flag', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function getUsers() {
        $group = Group::where('slug', 'user')->where('flag', 1)->first();
        $user_s = Self::where('group_id', $group->id)->where('flag', 1)->get(['fb_id']);
            
        $users = [];

        foreach ($user_s as $index => $user) {
            $users[] = $user['fb_id'];
        }

        return $users;
    }

    public static function findByFbId($fb_id)
    {
        $user = Self::where('fb_id', $fb_id)->where('flag', 1)->first();
        return $user;
    }
}
