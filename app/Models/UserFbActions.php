<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFbActions extends Model
{
    protected $table = 'user_fb_actions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'page_id', 'post_id', 'action', 'details', 'action_perform',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
