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
              Hashtag- Report
            </div>
            @if(basename(request()->path()) != "users-favourites")
            <div class="col-sm-6">
              <button type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">Filter</button>
            </div>
            @endif
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel-group" id="accordion">
                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  <div class="panel-body">
                    <form method="GET">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Start Date</label>
                            <input class="form-control datepicker" type="text" name="start_date" @if(isset($_GET['start_date'])) value="{{ $_GET['start_date'] }}" @endif>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>End Date</label>
                            <input class="form-control datepicker" type="text" name="end_date" @if(isset($_GET['start_date'])) value="{{ $_GET['end_date'] }}" @endif>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
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
                        <th class="sorting_asc center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 102px;">Hashtag Name</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 126px;">Linked Handled Name</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 126px;">Linked Handled</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 126px;">Trend</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 86px;">Remove</th>
                      </tr>
                    </thead>
                    <tbody>
                          <tr class="gradeA odd" role="row">
                            <td class="sorting_1 center">
                              {{ $hashtag->name }}
                            </td>
                            <td class="center">{{ $hashtag->handle_name }}</td>
                            <td class="center">{{ $hashtag->handle }}</td>
                            <td class="center">{{ $hashtag->count }}</td>
                            <td class="center">
                              <a href="{{ url('hashtags/'.$hashtag->id.'/edit') }}"><span><i class="fa fa-pencil-square-o fa-fw"></i></span></a>
                              <a href="{{ url('hashtags/'.$hashtag->id.'/delete') }}""><span><i class="fa fa-trash-o fa-fw"></i></span></a>
                            </td>
                          </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>


          <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
              <div class="col-sm-12">
                <table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;">
                  <thead>
                    <tr role="row">
                      <th width="12%" class="sorting_asc center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Name</th>
                      <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Handle Name</th>
                      <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Action</th>
                      <th width="25%" class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Message</th>
                      <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Action Perform Date</th>
                      <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Hashtags</th>
                    </tr>
                  </thead>
                  <tbody>
                        {{ csrf_field() }}
                    @if(count($users)) 
                      @foreach($users as $index => $user)
                        <tr class="gradeA odd" role="row">
                          <td class="sorting_1 center"><a href="{{ url('twitter-posts/'.$user->post_id.'/activity/'.$user->id) }}">{{ $user->name }}</td>
                          <td class="center" >{{ $user->handle }}</td>
                          <td class="center">{{ $user->action }}</td>
                          <td>{{ $user->details }}</td>
                          @if(!is_null($user->action_perform))
                            <td class="center">{{ date("d M Y h:i:s A" , strtotime($user->action_perform)) }}</td>
                          @else
                            <td class="center">NULL</td>
                          @endif
                          <td class="center">{{ $hashtag->name }}</td>

                        </tr>
                      @endforeach 
                    @else
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
                <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries </div>
              </div>
              <div class="col-sm-8">
                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                  {{ $users->links() }}
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
@endsection