<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twitter;
use App\Models\TwitterHandle;
use App\Models\TwitterPost;
use App\Models\TwitterUsersHandle;
use App\Models\UserTwitterAction;

class TwitterHandleActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $handles = TwitterHandle::getHandles();

        $handles_array = [];

        foreach ($handles as $key => $handle) {

            $handles_array[] = $handle->handle;

            $param = [
                'screen_name' => $handle->handle,
                'count' => 200,
                'format' => 'array'
            ];

            $handleTweets = Twitter::getUserTimeline($param);
            // return dd($handleTweets[0]['user']);
            foreach ($handleTweets as $index => $tweet) {

                if (is_null($tweet['in_reply_to_status_id'])) {

                    $post = TwitterPost::firstOrCreate([
                        'twitter_handle_id' => $handle['id'],
                        'twitter_id' => $tweet['id']
                    ]);

                    $post->link = "https://twitter.com/statuses/".$tweet['id'];
                    $post->post_message = $tweet['text'];
                    $post->created_at = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $post->favourites = $tweet['favorite_count'];
                    $post->retweets = $tweet['retweet_count'];

                    if (isset($tweet['entities']['media'])) {
                        $post->media = serialize($tweet['entities']['media']);
                    }
                    $hashtags = "";
                    if (isset($tweet['entities']['hashtags'])) {
                        foreach ($tweet['entities']['hashtags'] as $key => $hashtag) {
                            $hashtags .= $hashtag['text']." ,";
                        }
                    }
                    $post->hashtags = $hashtags;
                    $mentions = "";
                    if (isset($tweet['entities']['user_mentions'])) {
                        foreach ($tweet['entities']['user_mentions'] as $key => $mention) {
                            $mentions .= $mention['screen_name']." ,";
                        }
                    }
                    $post->mentions = $mentions;
                    $post->save();
                }

            }

        }

        $handles = TwitterUsersHandle::getHandles();

        foreach ($handles as $key => $handle) {

            $param = [
                'screen_name' => $handle->handle,
                'count' => 200,
                'format' => 'array'
            ];

            $handleTweets = Twitter::getUserTimeline($param);
            return dd($handleTweets);
            foreach ($handleTweets as $index => $tweet) {

                // Comment
                if (!is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] == false && in_array($tweet['in_reply_to_screen_name'], $handles_array)) {

                    $twitter_post = TwitterPost::getPost($tweet['in_reply_to_status_id']);


                    $user_action = UserTwitterAction::firstOrCreate([

                        'twitter_user_id' => $handle->id,
                        'action_id' => $tweet['id'],
                        'action' => 'comment',
                        'mention_handle_id' => TwitterHandle::findByTwitterHandle($tweet['in_reply_to_screen_name'])->id
                    ]);

                    if ($user_action->wasRecentlyCreated) {
                        TwitterPost::updateData($twitter_post['id'], 'comments');
                    }

                    $user_action->action_parent_id = $tweet['in_reply_to_status_id'];
                    $user_action->twitter_post_id = $twitter_post['id'];
                    $user_action->details = $tweet['text'];
                    $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $user_action->save();

                } 
                // Retweet
                elseif (is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] == true && in_array($tweet['quoted_status']['user']['screen_name'], $handles_array)) {
                    
                    $twitter_post = TwitterPost::getPost($tweet['quoted_status_id']);


                    $user_action = UserTwitterAction::firstOrCreate([

                        'twitter_user_id' => $handle->id,
                        'action_id' => $tweet['id'],
                        'action' => 'retweet',
                        'mention_handle_id' => TwitterHandle::findByTwitterHandle($tweet['quoted_status']['user']['screen_name'])->id
                    ]);

                    if ($user_action->wasRecentlyCreated) {
                        TwitterPost::updateData($twitter_post['id'], 'retweets');
                    }
                    
                    $user_action->action_parent_id = $tweet['quoted_status_id'];
                    $user_action->twitter_post_id = $twitter_post['id'];
                    $user_action->details = $tweet['text'];
                    $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $user_action->save();
                }

                if (isset($tweet['entities']['user_mentions'])) {
                    foreach ($tweet['entities']['user_mentions'] as $key => $mention) {
                        if ($mention['screen_name'] != $tweet['in_reply_to_screen_name'] && in_array($mention['screen_name'], $handles_array)) {

                            $twitter_post = TwitterPost::getPost($tweet['quoted_status_id'] || $tweet['in_reply_to_status_id']);
                            
                            $user_action = UserTwitterAction::firstOrCreate([

                                'twitter_user_id' => $handle->id,
                                'action_id' => $tweet['id'],
                                'action' => 'mention',
                                'mention_handle_id' => TwitterHandle::findByTwitterHandle($mention['screen_name'])->id
                            ]);

                            $user_action->action_parent_id =  $tweet['in_reply_to_status_id'] || $tweet['quoted_status_id'];
                            $user_action->twitter_post_id = $twitter_post['id'];
                            $user_action->details = $tweet['screen_name'];
                            $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                            $user_action->save();
                        }
                    }
                }
            }

        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
