<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class UserTwitterAction extends Model
{
    protected $table = 'twitter_user_actions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'twitter_user_id', 'twitter_post_id', 'action_id', 'action_parent_id', 'action', 'details', 'action_perform', 'mention_handle_id', 'hashtag_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    public static function getActivity($post_id = null, $filter = [])
    {
        $query = DB::table('twitter_user_actions');
        if (!is_null($post_id)) {
            $query->where('twitter_post_id', $post_id);
        }
        if (isset($filter['activity'])) {
            $query->where('action', $filter['activity']);
        }

        if (isset($filter['user_id'])) {
            $query->where('twitter_user_id', $filter['user_id']);
        }

        if (isset($filter['start_date'])) {
            $query->whereDate('action_perform', '>=', $filter['start_date']);
        }

        if (isset($filter['end_date'])) {
            $query->whereDate('action_perform', '<=', $filter['end_date']);
        }


        $query->join('twitter_user_handles', 'twitter_user_handles.id', 'twitter_user_actions.twitter_user_id');
        $query->join('twitter_handles', 'twitter_handles.id', 'twitter_user_actions.mention_handle_id');
        $query->leftJoin('hashtags', 'hashtags.id', 'twitter_user_actions.hashtag_id');
        $query->select('twitter_user_actions.id as user_twitter_action_id', 'twitter_user_actions.action', 'twitter_user_actions.details', 'twitter_user_actions.action_perform', 'twitter_user_actions.twitter_post_id as post_id', 'twitter_user_handles.id', 'twitter_user_handles.name', 'twitter_user_handles.handle', 'twitter_handles.id as user_mention_id', 'twitter_handles.name as mention_name', 'twitter_handles.handle as mention_handle', 'hashtags.name as hashtag_name');
        $query->orderBy('action_perform', 'desc');
        $users = $query->paginate(25);

        return $users;

    }

    public static function getHastagActivity($id, $filter = [])
    {
        $query = DB::table('twitter_user_actions');

        if (isset($filter['start_date'])) {
            $query->whereDate('action_perform', '>=', $filter['start_date']);
        }

        if (isset($filter['end_date'])) {
            $query->whereDate('action_perform', '<=', $filter['end_date']);
        }

        $query->where('hashtag_id', $id);
        $query->join('twitter_user_handles', 'twitter_user_handles.id', 'twitter_user_actions.twitter_user_id');
        $query->select('twitter_user_actions.id as user_twitter_action_id', 'twitter_user_actions.action', 'twitter_user_actions.details', 'twitter_user_actions.action_perform', 'twitter_user_actions.twitter_post_id as post_id', 'twitter_user_handles.id', 'twitter_user_handles.name', 'twitter_user_handles.handle');
        $query->orderBy('action_perform', 'desc');
        $users = $query->paginate(25);

        return $users;
    }
}
