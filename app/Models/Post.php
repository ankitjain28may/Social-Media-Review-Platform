<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use App\User;
use App\Models\UserFbActions;

class Post extends Model
{
    protected $table = 'posts';

    protected static $base_uri = 'https://graph.facebook.com/v2.9/';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'fb_post_id', 'post_message', 'link', 'likes', 'comments', 'shares', 'internal_likes', 'internal_comments', 'internal_shares', 'created_time', 'flag',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getPost($post_id, $page_id, $flag = 1)
    {
        $query = Self::where('fb_post_id', $post_id);
        $query = $query->where('page_id', $page_id);

        if ($flag != -1) {
            $query = $query->where('flag', $flag);
        }
        $post = $query->first();
        return $post;
    }


    public static function getLikes($page_id, $post, $access_token) {
        
        $users = User::getUsers();

        $client = new Client();

        $after = '';
        $likes = 0;
        $internal_likes = 0;
        
        do {
            $res = $client->request('GET', Self::$base_uri.$post->fb_post_id.'/likes?limit=25&after='.$after.'&access_token='.$access_token);
            $likes_data = json_decode($res->getBody(), True);
            // return $likes_data;
            if (count($likes_data['data'])) {
                $likes += count($likes_data['data']);

                foreach ($likes_data['data'] as $index => $like) {
                    
                    if (in_array($like['id'], $users)) {

                        $user = User::findByFbId($like['id']);
                        $actions = UserFbActions::firstOrCreate(['user_id'=> $user->id, 'page_id' => $page_id, 'post_id' => $post->id, 'action' => 'like']);
                        $internal_likes += 1;
                    }
                }
            }
            $after = isset($likes_data['paging']['cursors']['after']) ? $likes_data['paging']['cursors']['after'] : '';
        } while(isset($likes_data['paging']['next']));
        $data = [
            "likes" => $likes,
            "internal_likes" => $internal_likes
        ];
        
        return $data;

    }



    public static function getComments($page_id, $post, $access_token, $parent_id = 0) {
        
        $users = User::getUsers();

        $client = new Client();

        $after = '';
        $comments = 0;
        $internal_comments = 0;
        
        $id = $post->fb_post_id;

        if ($parent_id != 0) {
            $id = $parent_id;
        }

        do {
            $res = $client->request('GET', Self::$base_uri.$id.'/comments?fields=from,message,comment_count,created_time&limit=25&after='.$after.'&access_token='.$access_token);

            $comments_data = json_decode($res->getBody(), True);
            // return $comments_data;
            if (count($comments_data['data'])) {
                $comments += count($comments_data['data']);

                foreach ($comments_data['data'] as $index => $comment) {
                    if (in_array($comment['from']['id'], $users)) {

                        $user = User::findByFbId($comment['from']['id']);
                        $actions = UserFbActions::firstOrCreate(['user_id'=> $user->id, 'page_id' => $page_id, 'post_id' => $post->id, 'action_id' => $comment['id'], 'action' => 'comment']);
                        if($actions->details == NULL) {
                            $actions->details = $comment['message'];
                        }
                        $actions->action_perform = date('Y-m-d h:i:s', strtotime($comment['created_time']));

                        if ($parent_id != 0) {
                            $actions->action_parent_id = $id;
                        }

                        $actions->save();

                        $internal_comments += 1;
                    }

                    if ($comment['comment_count'] != 0) {
                        $getReturn = Self::getComments($page_id, $post, $access_token, $comment['id']);
                        $comments += $getReturn['comments'];
                        $internal_comments += $getReturn['internal_comments'];
                    }

                }
            }
            $after = isset($comments_data['paging']['cursors']['after']) ? $comments_data['paging']['cursors']['after'] : '';
        } while(isset($comments_data['paging']['next']));
        $data = [
            "comments" => $comments,
            "internal_comments" => $internal_comments
        ];
        
        return $data;

    }


    public static function getShares($page_id, $post, $access_token) {
        
        $users = User::getUsers();

        $client = new Client();

        $after = '';
        $shares = 0;
        $internal_shares = 0;
        
        $id = $post->fb_post_id;

        do {
            $res = $client->request('GET', Self::$base_uri.$id.'/sharedposts?fields=from,message,created_time&limit=25&after='.$after.'&access_token='.$access_token);

            $shares_data = json_decode($res->getBody(), True);

            if ($id == "138483559560270_1640865172655427") {
                return dd($post_data);
            }

            // return $shares_data;
            if (count($shares_data['data'])) {
                $shares += count($shares_data['data']);

                foreach ($shares_data['data'] as $index => $share) {
                    if (in_array($share['from']['id'], $users)) {

                        $user = User::findByFbId($share['from']['id']);
                        $actions = UserFbActions::firstOrCreate(['user_id'=> $user->id, 'page_id' => $page_id, 'post_id' => $post->id, 'action_id' => $share['id'], 'action' => 'share']);
                        if($actions->details == NULL) {
                            $actions->details = isset($share['message']) ? $share['message'] : NULL;
                        }
                        $actions->action_perform = date('Y-m-d h:i:s', strtotime($share['created_time']));

                        $actions->save();

                        $internal_shares += 1;
                    }

                }
            }
            $after = isset($shares_data['paging']['cursors']['after']) ? $shares_data['paging']['cursors']['after'] : '';
        } while(isset($shares_data['paging']['next']));
        $data = [
            "shares" => $shares,
            "internal_shares" => $internal_shares
        ];
        
        return $data;

    }


}
