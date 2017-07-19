<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Page;
use Redirect;
use GuzzleHttp\Client;
use App\Models\FbUser;
use App\Models\Post;
use Session;


class ReportingController extends Controller
{

    protected $base_uri = 'https://graph.facebook.com/v2.9/';

    // Timestamp of 4 weeks
    protected $fourWeek = 2592000;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $access_token = FbUser::getAccessToken($id);
        $pages = Page::where('user_id', $id)->where('flag', 1)->get();

        $time = date(time());
        $since = $time - $this->fourWeek;

        $client = new Client();
        $after = '';

        foreach ($pages as $key => $page) {

            do {

                $res = $client->request('GET', $this->base_uri.$page['fb_page_id'].'/posts?since='.$since.'&after='.$after.'&fields=created_time,message,description,id,name,attachments{media},permalink_url&limit=25&access_token='.$access_token);
                $post_s = json_decode($res->getBody(), True);
                $after = isset($post_s['paging']['cursors']['after']) ? $post_s['paging']['cursors']['after'] : '';

                $data = $post_s['data'];
                // return dd($data);
                $users = User::getUsers();

                foreach ($data as $index => $post_data) {

                    if ($post_data['id'] == "138483559560270_1640865172655427") {
                        return dd($post_data);
                    }


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

                    $shares_data = Post::getShares($page['id'], $post, $access_token);
                    
                    $post->shares = $shares_data['shares'];
                    $post->internal_shares = $shares_data['internal_shares'];

                    $post->save();

                }

            } while (isset($post_s['paging']['next']));
        }

        return Redirect::to('/pages');

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
