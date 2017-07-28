<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use Excel;
use Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use App\Models\TwitterHandle;
use App\Models\TwitterUsersHandle;
use Validator;

class HandleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($limit = 25, $offset = 0)
    {
        $type = Input::get('type');

        if (isset($type) && $type == 'user') {
            $handles = TwitterUsersHandle::where('flag', 1)->paginate(25);
        } elseif (isset($type) && $type == 'main') {
            $handles = TwitterHandle::where('flag', 1)->paginate(25);
        } else {
            Session::flash('message', 'Invalid Call');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::back();
        }
        return view('twitter.handles.show', compact('handles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('twitter.handles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return dd(Input::all());

        $this->validate(
            $request, [
            'user_type' => 'required|not_in:Select',
            ]
        );

        $user_type = Input::get('user_type');

        Session::flash('message', 'Inserted successfully');
        Session::flash('alert-class', 'alert-success');

        if (Input::has('handle_name')) {

            if ($user_type) {
                TwitterHandle::firstOrCreate([
                    'handle' => Input::get('handle_name')
                ]);
            } else {
                TwitterUsersHandle::firstOrCreate([
                    'handle' => Input::get('handle_name')
                ]);
            }

        } elseif(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get(['handles']);

            $handles = [];

            if ($user_type) {
                foreach ($data as $key => $value) {
                    if (!TwitterHandle::findByTwitterHandle($value['handles'])) {
                        $handles[] = ['handle' => $value['handles']];
                    }
                }

                TwitterHandle::insert($handles);
            } else {
                foreach ($data as $key => $value) {
                    if (!TwitterUsersHandle::findByTwitterHandle($value['handles'])) {
                        $handles[] = ['handle' => $value['handles']];
                    }
                }

                TwitterUsersHandle::insert($handles);
            }

        } else {
            Session::flash('message', 'Ooops !! Error has occured, Talk to App Developer');
            Session::flash('alert-class', 'alert-danger');

        }
        return back();
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
        
        $type = Input::get('type');

        if (isset($type) && $type == 'user') {
            $handle = TwitterUsersHandle::where('id', $id)->where('flag', 1)->first();

        } elseif (isset($type) && $type == 'main') {
            $handle = TwitterHandle::where('id', $id)->where('flag', 1)->first();
        } else {
            Session::flash('message', 'Invalid Call');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::back();
        }

        if (is_null($handle)) {
            Session::flash('message', 'Ooops!! Handle is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        return view('twitter.handles.edit', compact('handle'));

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
            'handle' => 'required',
            ]
        );

        $type = Input::get('type');

        if (isset($type) && $type == 'user') {
            $handle = TwitterUsersHandle::where('id', $id)->where('flag', 1)->first();

        } elseif (isset($type) && $type == 'main') {
            $handle = TwitterHandle::where('id', $id)->where('flag', 1)->first();
        } else {
            Session::flash('message', 'Invalid Call');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::back();
        }

        if (is_null($handle)) {
            Session::flash('message', 'Ooops!! Handle is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('/handles?type='.$type);
        }

        $handle->handle = Input::get('handle');
        $handle->save();

        Session::flash('message', 'Handle is updated successfully');
        Session::flash('alert-class', 'alert-success');

        return Redirect::to('/handles?type='.$type);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $type = Input::get('type');

        if (isset($type) && $type == 'user') {
            $handle = TwitterUsersHandle::where('id', $id)->where('flag', 1)->first();

        } elseif (isset($type) && $type == 'main') {
            $handle = TwitterHandle::where('id', $id)->where('flag', 1)->first();
        } else {
            Session::flash('message', 'Invalid Call');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::back();
        }

        if (is_null($handle)) {
            Session::flash('message', 'Ooops!! Handle is not found');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::back();
        }

        $handle->flag = 0;
        $handle->save();
        Session::flash('message', 'Handle is deleted successfully');
        Session::flash('alert-class', 'alert-success');

        return Redirect::to('/handles?type='.$type);


    }
}
