<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserFbAction extends Model
{
    protected $table = 'user_fb_actions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'page_id', 'post_id', 'action', 'details', 'action_perform', 'action_id', 'action_parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    public static function getActivity($post_id)
    {
        $query = DB::table('user_fb_actions');
        $query->where('post_id', $post_id);
        $query->joins('users', 'users.id', 'user_fb_actions.user_id');
        $query->select('user_fb_actions.id, user_fb_actions.action');
        $query->get();

        return $query;

    }
}
