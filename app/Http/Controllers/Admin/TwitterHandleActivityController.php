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
            // return dd($handleTweets);
            foreach ($handleTweets as $index => $tweet) {
                var_dump($tweet['is_quote_status']);
                // Comment
                try {
                    if (is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] && in_array($tweet['quoted_status']['user']['screen_name'], $handles_array)) {
                        echo $tweet['quoted_status']['user']['screen_name'];
                    }
                }
                catch(Exception $e) {
                    echo 'Message: ' .$e->getMessage();
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
