<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fb_page_id', 'page_name', 'page_access_token', 'user_id', 'flag',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    public static function getPage($page_id, $flag = 1)
    {
        $query = Self::where('fb_page_id', $page_id);

        if ($flag != -1) {
            $query->where('flag', $flag);
        }
        $page = $query->first();
        return $page;
    }
}
