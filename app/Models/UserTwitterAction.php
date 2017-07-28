<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
