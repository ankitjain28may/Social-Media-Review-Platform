<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;


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


    public static function getActivity($post_id, $filter = [])
    {
        $query = DB::table('user_fb_actions');
        $query->where('post_id', $post_id);
        if (isset($filter['activity'])) {
            $query->where('action', $filter['activity']);
        }

        if (isset($filter['user_id'])) {
            $query->where('user_id', $filter['user_id']);
        }
        $query->join('users', 'users.id', 'user_fb_actions.user_id');
        $query->select('user_fb_actions.id as user_fb_action_id', 'user_fb_actions.action', 'user_fb_actions.details', 'user_fb_actions.action_perform', 'user_fb_actions.post_id', 'users.id', 'users.name', 'users.email', 'users.avatar');
        $users = $query->paginate(25);

        return $users;

    }
}
