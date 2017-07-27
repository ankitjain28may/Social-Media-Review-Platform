@extends('layouts.app') @section('content')
<div id="page-wrapper">
  @if(Session::has('message'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
  @endif
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Import Leads from Excel file</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/handles') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('handle_name') ? ' has-error' : '' }}">
                            <label for="handle_name" class="col-md-4 control-label">Handle: </label>

                            <div class="col-md-6">
                                <input id="handle_name" class="form-control" type="text" name="handle_name" value="{{ old('handle_name') }}" autofocus>
                                @if ($errors->has('handle_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('handle_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('import_file') ? ' has-error' : '' }}">
                            <label for="import_file" class="col-md-4 control-label">Excel File: </label>

                            <div class="col-md-6">
                                <input id="import_file" class="form-control" type="file" name="import_file" value="{{ old('import_file') }}" autofocus>

                                @if ($errors->has('import_file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('import_file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('user_type') ? ' has-error' : '' }}">
                            <label for="user_type" class="col-md-4 control-label">Handle Type: </label>
                            <div class="col-md-6">
                                <select id="user_type" class="form-control" name="user_type">
                                    <option>Select</option>
                                    <option value="1" >Main Handles</option>
                                    <option value="0" >User Handles</option>
                                </select>
                                @if ($errors->has('user_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_type') }}</strong>
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

