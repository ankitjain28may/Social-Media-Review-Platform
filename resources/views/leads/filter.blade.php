@extends('layouts.app')
@section('content')
<div id="page-wrapper">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Filter Leads</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="post" action="{{ url('filter') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                            <label for="source" class="col-md-4 control-label">Source: </label>
                            <div class="col-md-6">
                                <select id="source" class="form-control" name="source">
                                    <option value="JEE" >JEE</option>
                                    <option value="CBSE" >CBSE</option>
                                </select>
                                @if ($errors->has('source'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('source') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label for="state" class="col-md-4 control-label">State: </label>
                            <div class="col-md-6">
                                <select id="state" class="form-control" name="state">
                                @foreach($states as $state)
                                    <option value="{{ $state->state }}" >{{ $state->state }}</option>
                                @endforeach
                                </select>
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

