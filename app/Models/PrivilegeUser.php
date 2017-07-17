<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivilegeUser extends Model
{
    protected $table = 'privilege_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'privilege_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
