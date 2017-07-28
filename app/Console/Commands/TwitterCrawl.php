<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twitter;
use App\Models\TwitterHandle;
use App\Models\TwitterPost;
use App\Models\TwitterUsersHandle;
use App\Models\UserTwitterAction;
use App\Models\Hashtag;

class TwitterCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:twitter';

    protected $lastWeek = 604800;
    protected $count = 0;
    protected $tweet = 0;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will crawl the data from the Twitter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Start Crawling....\n");

        $handles = TwitterHandle::getHandles();

        $handles_array = [];

        foreach ($handles as $key => $handle) {

            $handles_array[] = $handle->handle;

            if (time()-strtotime($handle->last_crawl) < $this->lastWeek) {
                continue;
            }


            $param = [
                'screen_name' => $handle->handle,
                'count' => 200,
                'format' => 'array'
            ];

            $handleTweets = Twitter::getUserTimeline($param);
            
            $handle->last_crawl = date('Y-m-d h:i:s', time());
            if (isset($handleTweets[0]['user']['name'])) {
                $handle->name = ucwords($handleTweets[0]['user']['name']);
            }
            $handle->save();
            // return dd($handleTweets);

            // Output
            $this->count += 1;
            $this->tweet += count($handleTweets);
            $this->output->write("\r\r\t\t".' Collecting '.count($handleTweets).'tweets of Handle : ', false);
            $this->info($handle->handle, false);

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
                    $hashtags = [];
                    if (isset($tweet['entities']['hashtags'])) {
                        foreach ($tweet['entities']['hashtags'] as $key => $hashtag) {
                            $hashtags[] = $hashtag['text']." ,";
                        }
                    }
                    $post->hashtags = implode(",", $hashtags);
                    $mentions = [];
                    if (isset($tweet['entities']['user_mentions'])) {
                        foreach ($tweet['entities']['user_mentions'] as $key => $mention) {
                            $mentions[] = $mention['screen_name']." ,";
                        }
                    }
                    $post->mentions = implode(",", $mentions);
                    $post->save();
                }

            }

        }

        $handles = TwitterUsersHandle::getHandles();

        foreach ($handles as $key => $handle) {

            if (time()-strtotime($handle->last_crawl) < $this->lastWeek) {
                continue;
            }

            $param = [
                'screen_name' => $handle->handle,
                'count' => 200,
                'format' => 'array'
            ];

            $handleTweets = Twitter::getUserTimeline($param);

            $handle->last_crawl = date('Y-m-d h:i:s', time());
            if (isset($handleTweets[0]['user']['name'])) {
                $handle->name = ucwords($handleTweets[0]['user']['name']);
            }
            $handle->save();

            // return dd($handleTweets);

            // Output
            $this->count += 1;
            $this->tweet += count($handleTweets);
            $this->output->write("\r\r\t\t".' Collecting activity of Handle : ', false);
            $this->info($handle->handle, false);

            foreach ($handleTweets as $index => $tweet) {

                // Comment
                if (!is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] == false && isset($tweet['in_reply_to_screen_name']) && in_array($tweet['in_reply_to_screen_name'], $handles_array)) {

                    $twitter_post = TwitterPost::getPost($tweet['in_reply_to_status_id']);

                    if (is_null($twitter_post)) {
                        continue;
                    }


                    $user_action = UserTwitterAction::firstOrCreate([

                        'twitter_user_id' => $handle->id,
                        'action_id' => $tweet['id'],
                        'twitter_post_id' => $twitter_post['id'],
                        'action' => 'comment',
                        'mention_handle_id' => TwitterHandle::findByTwitterHandle($tweet['in_reply_to_screen_name'])->id
                    ]);

                    if ($user_action->wasRecentlyCreated) {
                        TwitterPost::updateData($twitter_post['id'], 'comments');
                    }

                    $user_action->action_parent_id = $tweet['in_reply_to_status_id'];
                    $user_action->details = $tweet['text'];
                    $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $user_action->save();

                } 
                // Retweet with quotes
                elseif (is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] == true && isset($tweet['quoted_status']['user']['screen_name']) && isset($tweet['quoted_status']['user']['screen_name']) && isset($tweet['quoted_status_id']) && in_array($tweet['quoted_status']['user']['screen_name'], $handles_array)) {
                    
                    $twitter_post = TwitterPost::getPost($tweet['quoted_status_id']);

                    if (is_null($twitter_post)) {
                        continue;
                    }


                    $user_action = UserTwitterAction::firstOrCreate([

                        'twitter_user_id' => $handle->id,
                        'action_id' => $tweet['id'],
                        'twitter_post_id' => $twitter_post['id'],
                        'action' => 'retweet',
                        'mention_handle_id' => TwitterHandle::findByTwitterHandle($tweet['quoted_status']['user']['screen_name'])->id
                    ]);

                    if ($user_action->wasRecentlyCreated) {
                        TwitterPost::updateData($twitter_post['id'], 'retweets');
                    }
                    
                    $user_action->action_parent_id = $tweet['quoted_status_id'];
                    $user_action->details = $tweet['text'];
                    $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $user_action->save();
                }
                // Retweet
                elseif (is_null($tweet['in_reply_to_screen_name']) && $tweet['is_quote_status'] == false && isset($tweet['retweeted_status']) && isset($tweet['retweeted_status']['id'])  && isset($tweet['retweeted_status']['user']['screen_name']) && in_array($tweet['retweeted_status']['user']['screen_name'], $handles_array)) {
                    
                    $twitter_post = TwitterPost::getPost($tweet['retweeted_status']['id']);

                    if (is_null($twitter_post)) {
                        continue;
                    }


                    $user_action = UserTwitterAction::firstOrCreate([

                        'twitter_user_id' => $handle->id,
                        'action_id' => $tweet['id'],
                        'twitter_post_id' => $twitter_post['id'],
                        'action' => 'retweet',
                        'mention_handle_id' => TwitterHandle::findByTwitterHandle($tweet['retweeted_status']['user']['screen_name'])->id
                    ]);

                    if ($user_action->wasRecentlyCreated) {
                        TwitterPost::updateData($twitter_post['id'], 'retweets');
                    }
                    
                    $user_action->action_parent_id = $tweet['retweeted_status']['id'];
                    $user_action->details = $tweet['text'];
                    $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                    $user_action->save();
                }

                // Mentions
                if (isset($tweet['entities']['user_mentions'])) {
                    foreach ($tweet['entities']['user_mentions'] as $key => $mention) {

                        if ($mention['screen_name'] == $tweet['in_reply_to_screen_name']) {
                            continue;
                        } elseif (isset($tweet['quoted_status']['user']['screen_name'])  && $mention['screen_name'] == $tweet['quoted_status']['user']['screen_name']) {
                            continue;
                        } elseif(in_array($mention['screen_name'], $handles_array)) {

                            $twitter_post = [];

                            if (isset($tweet['quoted_status_id']) && !is_null($tweet['quoted_status_id'])) {
                                $twitter_post = TwitterPost::getPost($tweet['quoted_status_id']);
                            } elseif (isset($tweet['in_reply_to_status_id'])  && !is_null($tweet['in_reply_to_status_id'])) {
                                $twitter_post = TwitterPost::getPost($tweet['in_reply_to_status_id']);
                            } else {
                                $twitter_post['id'] = NULL;
                            }
                            
                            $user_action = UserTwitterAction::firstOrCreate([

                                'twitter_user_id' => $handle->id,
                                'action_id' => $tweet['id'],
                                'twitter_post_id' => $twitter_post['id'],
                                'action' => 'mention',
                                'mention_handle_id' => TwitterHandle::findByTwitterHandle($mention['screen_name'])->id
                            ]);

                            $user_action->details = $tweet['text'];
                            $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                            $user_action->save();
                        }
                    }
                }

                // Hashtags
                if (isset($tweet['entities']['hashtags'])) {
                    foreach ($tweet['entities']['hashtags'] as $key => $hashtag) {

                        $twitter_handle = [];

                        if (isset($tweet['in_reply_to_screen_name']) && !is_null($tweet['in_reply_to_screen_name'])) {

                            $twitter_handle = TwitterHandle::findByTwitterHandle($tweet['in_reply_to_screen_name']);

                        } elseif (isset($tweet['quoted_status']['user']['screen_name'])  && !is_null($tweet['quoted_status']['user']['screen_name'])) {

                            $twitter_handle = TwitterHandle::findByTwitterHandle($tweet['quoted_status']['user']['screen_name']);

                        } else {
                            $twitter_handle['id'] = NULL;
                        }

                        $hashtag = Hashtag::getHashtag($hashtag['text'], $twitter_handle['id']);
                        if (!is_null($hashtag)) {

                            $twitter_post = [];

                            if (isset($tweet['quoted_status_id']) && !is_null($tweet['quoted_status_id'])) {
                                $twitter_post = TwitterPost::getPost($tweet['quoted_status_id']);
                            } elseif (isset($tweet['in_reply_to_status_id'])  && !is_null($tweet['in_reply_to_status_id'])) {
                                $twitter_post = TwitterPost::getPost($tweet['in_reply_to_status_id']);
                            } else {
                                $twitter_post['id'] = NULL;
                            }
                                                        
                            $user_action = UserTwitterAction::firstOrCreate([

                                'twitter_user_id' => $handle->id,
                                'action_id' => $tweet['id'],
                                'twitter_post_id' => $twitter_post['id'],
                                'action' => 'hashtag',
                                'mention_handle_id' => $twitter_handle['id'],
                                'hashtag_id' => $hashtag['id']
                            ]);

                            $user_action->details = $tweet['text'];
                            $user_action->action_perform = date('Y-m-d h:i:s', strtotime($tweet['created_at']));
                            $user_action->save();
                        }
                    }
                }
            }

            /*$handlefavourites = Twitter::getFavorites($param);

            $handle->last_crawl = date('Y-m-d h:i:s', time());
            if (isset($handleTweets[0]['user']['name'])) {
                $handle->name = ucwords($handleTweets[0]['user']['name']);
            }
            $handle->save();

            // return dd($handleTweets);

            // Output
            $this->count += 1;
            $this->tweet += count($handleTweets);
            $this->output->write("\r\r\t\t".' Collecting activity of Handle : ', false);
            $this->info($handle->handle, false);

            foreach ($handleTweets as $index => $tweet) {
*/



        }

        if ($this->count == 0) {
            $this->output->write("\t\tNo new Handle to crawl!", True);
        }
        $this->info("\nSuccessfully Crawled ".$this->tweet." Tweets(s) of ".$this->count. " Handle(s)");


    }
}
