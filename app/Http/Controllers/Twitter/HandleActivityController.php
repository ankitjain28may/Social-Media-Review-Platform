<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use Excel;
use Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use App\Models\TwitterHandle;
use App\Models\UserTwitterAction;
use App\Models\TwitterPost;
use App\Models\TwitterUsersHandle;
use Validator;


class HandleActivityController extends Controller
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

        $post = TwitterPost::getPostById($post_id);
        if (is_null($post)) {
            Session::flash('message', 'Ooops!! Invalid Post');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $users = UserTwitterAction::getActivity($post_id, $filter);

        // return dd($users);
        return view('twitter.activity.show', compact('users'));
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
        $post = TwitterPost::getPostById($post_id);
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

        $users = UserTwitterAction::getActivity($post_id, $filter);
        return view('twitter.activity.show', compact('users'));
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

    public function showUserActivity($id)
    {
        
        $handle = TwitterUsersHandle::where('id', $id)->where('flag', 1)->first();

        if (is_null($handle)) {
            Session::flash('message', 'Ooops!! Handle is not found');
            Session::flash('alert-class', 'alert-danger');
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

        $users = UserTwitterAction::getActivity(null, $filter);
        return view('twitter.activity.show', compact('users'));


    }
}
