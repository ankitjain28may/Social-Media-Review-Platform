<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivilegeGroup extends Model
{
    protected $table = 'privilege_groups';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'privilege_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
