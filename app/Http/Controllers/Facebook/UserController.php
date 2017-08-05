<?php

namespace App\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FbUser;
use App\Models\Page;
use App\Models\UserFbAction;
use App\Models\Group;
use App\User;
use Session;
use Illuminate\Support\Facades\Input;
use Auth;
use Redirect;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group_id)
    {
        $group = Group::where('id', $group_id)->where('flag', 1)->first();

        if (is_null($group)) {
            Session::flash('message', 'Ooops!! invalid Group Id');
            Session::flash('alert-class', 'alert-info');
            return Redirect::back();
        }

        $users = User::where('group_id', $group_id)->where('flag', 1)->paginate(100);

        return view('facebook.users.show', compact('users', 'group'));
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

        $user = User::where('id', $id)->where('flag', 1)->first();

        if (is_null($user)) {
            Session::flash('message', 'Ooops!! User is not found');
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


        $users = UserFbAction::getActivity(null, $filter);

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
        $user = User::where('id', $id)->where('flag', 1)->first();

        if (is_null($user)) {
            Session::flash('message', 'Ooops!! User is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $groups = Group::getGroups();
        return view('facebook.users.edit', compact('user', 'groups'));
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
            'group' => 'required|not_in:Select',
            ]
        );
        
        $input = Input::all();

        $user = User::where('id', $id)->where('flag', 1)->first();
        if (is_null($user)) {
            Session::flash('message', 'Ooops!! User is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $user->group_id = (int) $input['group'];

        if (isset($input['password']) && isset($input['password_confirmation']) && !is_null($input['password'])) {

            $this->validate(
                $request, [
                    'password' => 'required|string|min:6|confirmed',
                ]
            );

            $user->password = bcrypt($input['password']);
        }

        $user->save();

        return Redirect::to('/users/'.$user->group_id);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->where('flag', 1)->first();

        if (is_null($user)) {
            Session::flash('message', 'Ooops!! User is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $user->flag = 0;
        $user->save();
        Session::flash('message', 'User is deleted successfully');
        Session::flash('alert-class', 'alert-success');

        return Redirect::to('/users/'.$user->group_id);

    }
}
