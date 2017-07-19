<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Group;
use DB;

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


    public static function getUsers($slug = 'user') {

        $query = DB::table('users')
            ->join('groups', 'users.group_id', 'groups.id')
            ->where('users.flag', 1)
            ->where('groups.slug', $slug);

        $user_s = $query->get(['fb_id']);
                    
        $users = [];

        foreach ($user_s as $index => $user) {
            $users[] = $user->fb_id;
        }

        return $users;
    }

    public static function findByFbId($fb_id)
    {
        $user = Self::where('fb_id', $fb_id)->where('flag', 1)->first();
        return $user;
    }

    public static function getSlug($user_id) {
        $group = DB::table('users')
            ->where('users.id', $user_id)
            ->where('users.flag', 1)
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->select('groups.slug')
            ->get();

        return $group; 
    }
}
