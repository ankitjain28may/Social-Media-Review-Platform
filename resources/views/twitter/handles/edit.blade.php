@extends('layouts.app') @section('content')
<div id="page-wrapper">
  @if(Session::has('message'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
  @endif
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit the Handle name</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/handles/'.$handle['id'].'?type='.app('request')->input('type')) }}">
                    <input type="hidden" name="_method" value="put" />
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name: </label>

                            <div class="col-md-6">
                                <input id="name" class="form-control" type="text" name="name" value="{{ $handle['name'] }}" autofocus disabled>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('handle') ? ' has-error' : '' }}">
                            <label for="handle" class="col-md-4 control-label">Handle Name: </label>

                            <div class="col-md-6">
                                <input id="handle" class="form-control" type="text" name="handle" value="{{ $handle['handle'] }}" autofocus>
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
                                    Submit
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

