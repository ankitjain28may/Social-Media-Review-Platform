<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\FbUser;
use App\Models\Page;
use Session;
use Auth;
use Redirect;

class PageController extends Controller
{

    protected $base_uri = 'https://graph.facebook.com/v2.9/';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::where('user_id', Auth::id())->where('flag', 1)->paginate(25);
        return view('facebook.pages.show', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $access_token = FbUser::getAccessToken(Auth::id());

        $client = new Client();

        $res = $client->request('GET', $this->base_uri.'me/accounts?limit=100&access_token='.$access_token);
        $page_s = json_decode($res->getBody(), True);

        
        $pages = [];
        if (count($page_s['data'])) {
            foreach ($page_s['data'] as $index => $page) {
                if (!Page::getPage($page['id'])) {
                    $pages[] = $page;
                }
            }
        } else {
            Session::flash('message', 'Ooops!! Pages are not found');
            Session::flash('alert-class', 'alert-danger');
        }
        return view('facebook.pages.add', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pages = $request->get('pages');
        if (!count($pages)) {
            Session::flash('message', 'Please select at least 1 page from the list');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::back();
        }
        $flag = -1;
        foreach ($pages as $index => $page_data) {
            $page_data = json_decode($page_data, True);
            if (!$page = Page::getPage($page_data['id'], $flag)) {
                $page = new Page;
                $page->page_name = $page_data['name']; 
                $page->fb_page_id = $page_data['id']; 
                $page->page_access_token = $page_data['access_token']; 
                $page->user_id = Auth::id();
            } else {
                $page->flag = 1;
            }
            $page->save();
        }
        Session::flash('message', 'Woww!! Pages are added successfully');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/pages');
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
        $page = Page::where('id', $id)->where('flag', 1)->first();
        if ($page) {
            $page->flag = 0;
            $page->save();
            Session::flash('message', 'Page is removed successfully');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('/pages');
        }
        Session::flash('message', 'Oops!! Pages is not found');
        Session::flash('alert-class', 'alert-info');
        return Redirect::back();
    }
}
