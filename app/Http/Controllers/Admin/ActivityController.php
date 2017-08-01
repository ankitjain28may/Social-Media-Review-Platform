<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FbUser;
use App\Models\UserFbAction;
use App\Models\Page;
use App\Models\Post;
use Session;
use Auth;
use Redirect;
use Illuminate\Pagination\Paginator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $post = Post::getPostById($post_id);
        if (is_null($post)) {
            Session::flash('message', 'Ooops!! Invalid Post');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $users = UserFbAction::getActivity($post_id);
        return view('userActions.fb.show', compact('users'));
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
    public function show($post_id, $id)
    {
        $post = Post::getPostById($post_id);
        if (is_null($post)) {
            Session::flash('message', 'Ooops!! Invalid Post');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $users = UserFbAction::getActivity($post_id, ['user_id' => $id]);
        return view('userActions.fb.show', compact('users'));
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
