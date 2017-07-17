@extends('layouts.app') @section('content')
<div id="page-wrapper">
  @if(Session::has('message'))
  <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
  @endif
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-6">
              Students
            </div>
            <div class="col-sm-6">
              <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">Import</button>
            </div>
          </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
              <div class="col-sm-12">
                <table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;">
                  <thead>
                    <tr role="row">
                      <th class="sorting_asc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 102px;">Student Name</th>
                      <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 126px;">Email</th>
                      <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 114px;">Mobile No.</th>
                      <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 86px;">State</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($leads)) @foreach($leads as $lead)
                    <tr class="gradeA odd" role="row">
                      <td class="sorting_1">{{ $lead->name }}</td>
                      <td>{{ $lead->email }}</td>
                      <td>{{ $lead->mobile }}</td>
                      <td>{{ $lead->state }}</td>
                    </tr>
                    @endforeach @else
                    <tr class="gradeA odd" role="row">
                      <td colspan="4">No data available</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} entries </div>
              </div>
              <div class="col-sm-8">
                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                  {{ $leads->links() }}
                </div>
              </div>
            </div>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="{{ url('cluster') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('cluster_name') ? ' has-error' : '' }}">
                <label for="cluster_name" class="col-md-4 control-label">Cluster Name: </label>
                <div class="col-md-6">
                    <input id="cluster_name" type="text" class="form-control" name="cluster_name" value="{{ old('cluster_name') }}" required autofocus>
                    @if ($errors->has('cluster_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cluster_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                <label for="source" class="col-md-4 control-label">Source: </label>
                <div class="col-md-6">
                    <input id="source" type="text" class="form-control" name="source" value="{{ ($source) ? $source : old('source') }}" required autofocus readonly>
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
                    <input id="state" type="text" class="form-control" name="state" value="{{ ($state) ? $state : old('state') }}" required autofocus readonly>
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
                        Submit
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Modal end -->
@endsection