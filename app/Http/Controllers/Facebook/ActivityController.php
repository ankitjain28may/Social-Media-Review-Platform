<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FbUser;
use App\Models\UserFbAction;
use App\Models\Page;
use App\Models\Post;
use Session;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
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
        $input = Input::all();
        $filter = [];

        if (isset($input['action']) && $input['action'] != "None") {
            $filter['activity'] = $input['action'];
        }

        if (isset($input['end_date'])) {
            $input['end_date'] = implode("-", explode("/", $input['end_date']));
            $filter['end_date'] = date("Y-m-d", strtotime($input['end_date']));
        }

        if (isset($input['start_date'])) {
            $input['start_date'] = implode("-", explode("/", $input['start_date']));
            $filter['start_date'] = date("Y-m-d", strtotime($input['start_date']));
        }

        if (isset($input['start_date']) && isset($input['end_date']) && strtotime($input['end_date']) < strtotime($input['start_date'])) {
            Session::flash('message', 'Ooops!! Invalid Date-Time Range selected');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $post = Post::getPostById($post_id);
        if (is_null($post)) {
            Session::flash('message', 'Ooops!! Invalid Post');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $users = UserFbAction::getActivity($post_id, $filter);
        return view('facebook.activity.show', compact('users'));
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

        $input = Input::all();
        $filter = [];

        if (isset($input['action']) && $input['action'] != "None") {
            $filter['activity'] = $input['action'];
        }

        if (isset($input['end_date'])) {
            $input['end_date'] = implode("-", explode("/", $input['end_date']));
            $filter['end_date'] = date("Y-m-d", strtotime($input['end_date']));
        }

        if (isset($input['start_date'])) {
            $input['start_date'] = implode("-", explode("/", $input['start_date']));
            $filter['start_date'] = date("Y-m-d", strtotime($input['start_date']));
        }

        if (isset($input['start_date']) && isset($input['end_date']) && strtotime($input['end_date']) < strtotime($input['start_date'])) {
            Session::flash('message', 'Ooops!! Invalid Date-Time Range selected');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $filter['user_id'] = $id;

        $users = UserFbAction::getActivity($post_id, $filter);
        return view('facebook.activity.show', compact('users'));
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
