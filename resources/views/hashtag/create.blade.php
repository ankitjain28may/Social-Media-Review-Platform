@extends('layouts.app') @section('content')
<div id="page-wrapper">
  @if(Session::has('message'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
  @endif
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add Hashtags</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/hashtags') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('hashtag') ? ' has-error' : '' }}">
                            <label for="hashtag" class="col-md-4 control-label">Hashtag Name: </label>

                            <div class="col-md-6">
                                <input id="hashtag" class="form-control" type="text" name="hashtag" value="{{ old('hashtag') }}" autofocus>
                                @if ($errors->has('hashtag'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hashtag') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('handle') ? ' has-error' : '' }}">
                            <label for="handle" class="col-md-4 control-label">Handle Name: </label>
                            <div class="col-md-6">
                                <select id="handle" class="form-control" name="handle">
                                    <option>Select</option>
                                    @foreach($handles as $index => $handle)
                                        <option value="{{ $handle->id }}" >{{ $handle->handle }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('handle'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('handle') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection

