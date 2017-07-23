<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
use App\User;
use App\Models\Page;
use Redirect;
use GuzzleHttp\Client;
use App\Models\FbUser;
use App\Models\Post;
use Session;

class FbCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:fb';

    protected $base_uri = 'https://graph.facebook.com/v2.9/';

    // Timestamp of 4 weeks
    protected $fourWeek = 2592000;
    protected $lastWeek = 604800;

    protected $count = 0;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will crawl the data from facebook pages for last four weeks';

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
        $pages = [];

        $allUsers = FbUser::where('flag', 1)->get();
        foreach ($allUsers as $index => $allUser) {
            
            $id = $allUser->id;
            $access_token = $allUser->access_token;
        
            $pages = Page::where('user_id', $id)->where('flag', 1)->get();

            $time = date(time());
            $since = $time - $this->fourWeek;

            $client = new Client();
            $after = '';

            foreach ($pages as $key => $page) {

                if (time()-strtotime($page->last_crawl) < $this->lastWeek) {
                    continue;
                }

                $page->last_crawl = date('Y-m-d h:i:s', time());
                $page->save();

                do {

                    $res = $client->request('GET', $this->base_uri.$page['fb_page_id'].'/posts?since='.$since.'&after='.$after.'&fields=created_time,shares,message,description,id,name,attachments{media},permalink_url&limit=25&access_token='.$access_token);
                    $post_s = json_decode($res->getBody(), True);
                    $after = isset($post_s['paging']['cursors']['after']) ? $post_s['paging']['cursors']['after'] : '';

                    $data = $post_s['data'];
                    // return dd($data);
                    $users = User::getUsers();

                    foreach ($data as $index => $post_data) {

                        $this->count+=1;

                        // Output
                        $this->output->write("\r\r\t\t".' Collecting data from Page: ', false);
                        $this->info($page->page_name." => ".$post_data['id'], false);

                        if (!$post = Post::getPost($post_data['id'], $page['id'])) {
                            $post = new Post;
                            $post->fb_post_id = $post_data['id'];
                            $post->page_id = $page['id'];

                            $post->post_name = isset($post_data['name']) ? $post_data['name'] : null;
                            
                            if (isset($post_data['message'])) {
                                $post->post_message = $post_data['message'];
                            } elseif (isset($post_data['description'])) {
                                $post->post_message = $post_data['description'];
                            }
                            $post->created_time = date('Y-m-d h:i:s', strtotime($post_data['created_time']));
                            $post->link = $post_data['permalink_url'];
                            $post->media = isset($post_data['attachments']['data'][0]['media']) ? json_encode($post_data['attachments']['data'][0]['media']) : NULL;
                        }
                        $post->save();

                        $likes_data = Post::getLikes($page['id'], $post, $access_token);

                        $post->likes = $likes_data['likes'];
                        $post->internal_likes = $likes_data['internal_likes'];

                        $comments_data = Post::getComments($page['id'], $post, $access_token);
                        
                        $post->comments = $comments_data['comments'];
                        $post->internal_comments = $comments_data['internal_comments'];

                        if (isset($post_data['shares'])) {
                            $shares_data = Post::getShares($page['id'], $post, $access_token);
                            $post->shares = $post_data['shares']['count'];
                            $post->internal_shares = $shares_data['internal_shares'];
                        }

                        $post->save();

                    }

                } while (isset($post_s['paging']['next']));
            }
        }
        if ($this->count == 0) {
            $this->output->write("\t\tNo new post to crawl!", True);
        }
        $this->info("\nSuccessfully Crawled ".$this->count." Post(s) from ".count($pages). " Page(s)");

    }
}
