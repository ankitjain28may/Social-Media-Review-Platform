<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\TwitterHandle;
use App\Models\UserTwitterAction;
use Session;
use Auth;
use Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;


class HashtagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hashtags = Hashtag::getHashtags();
        // return dd($hashtags);
        return view('twitter.hashtag.show', compact('hashtags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $handles = TwitterHandle::getHandles();
        return view('twitter.hashtag.create', compact('handles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
            'hashtag' => 'required',
            'handle' => 'required|not_in:Select|not_in:select',
            ]
        );

        $input = Input::all();

        if (is_null(TwitterHandle::findById($input['handle']))) {
            Session::flash('message', 'Ooops!! Handle is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }
        $hashtag = new Hashtag;

        $hashtag->name = $input['hashtag'];
        $hashtag->twitter_handle_id = $input['handle'];

        $hashtag->save();
        Session::flash('message', 'Successfullt Added');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/hashtags');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $input = Input::all();
        $filter = [];

        $hashtag = Hashtag::getHashtags($id);

        if (is_null($hashtag)) {
            Session::flash('message', 'Ooops!! Hashtag is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
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

        $users = UserTwitterAction::getHastagActivity($id, $filter);
        $hashtag->count = $users->total();

        return view('twitter.hashtag.activity', compact('hashtag', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hashtag = Hashtag::where('id', $id)->where('flag', 1)->first();

        if (is_null($hashtag)) {
            Session::flash('message', 'Ooops!! Hashtag is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }
        $handles = TwitterHandle::getHandles();

        return view('twitter.hashtag.edit', compact('hashtag', 'handles'));
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
        $this->validate(
            $request, [
            'name' => 'required',
            'handle' => 'required|not_in:Select|not_in:select',
            ]
        );

        $hashtag = Hashtag::where('id', $id)->where('flag', 1)->first();

        $input = Input::all();

        if (is_null(TwitterHandle::findById($input['handle']))  || is_null($hashtag)) {
            Session::flash('message', 'Ooops!! Invalid Handle or Hashtag');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $hashtag->name = $input['name'];
        $hashtag->twitter_handle_id = $input['handle'];

        $hashtag->save();
        Session::flash('message', 'Successfullt Updated');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/hashtags');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $hashtag = Hashtag::where('id', $id)->where('flag', 1)->first();

        if (is_null($hashtag)) {
            Session::flash('message', 'Ooops!! Hashtag is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $hashtag->flag = 0;
        $hashtag->save();
        Session::flash('message', 'Hashtag is deleted successfully');
        Session::flash('alert-class', 'alert-success');

        return Redirect::to('/hashtags');
    }
}
