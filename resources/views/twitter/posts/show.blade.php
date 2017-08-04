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
                Posts
              </div>
              <!-- <div class="col-sm-6">
                <a href= "{{ url('/pages/create') }}"><button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">Add More Pages</button></a>
              </div> -->
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
                        <th class="sorting_asc center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Post Name</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Post Message</th>
                        <th width="20%" class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Mentions</th>
                        <th width="20%" class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Hashtags</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Likes</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Comments</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" >Shares</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Created Time</th>
                        <th class="sorting center" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Explore</th>
                      </tr>
                    </thead>
                    <tbody>
                          {{ csrf_field() }}
                      @if(count($posts)) 
                        @foreach($posts as $index => $post)
                          <tr class="gradeA odd" role="row">
                            <td class="sorting_1">
                              <a href="{{ url('posts/'.$post['id'].'/activity') }}">
                                {{ $post['twitter_id'] }}
                              </a>
                            </td>
                            <td >
                              {{ (strlen($post['post_message']) > 10) ? substr($post['post_message'],0, 10).'...' : $post['post_message'] }}
                            </td>
                            <td >
                              {{ $post['mentions'] }}
                            </td>
                            <td>
                              {{ $post['hashtags'] }}
                            </td>
                            <td class="center">
                              <a href="{{ url('posts/'.$post['id'].'/activity?action=like') }}">
                                {{ $post['internal_favourites']."/".$post['favourites'] }}
                              </a>
                            </td>
                            <td class="center"><a href="{{ url('posts/'.$post['id'].'/activity?action=comment') }}">{{ $post['internal_comments']."/".$post['comments'] }}</a></td>
                            <td class="center"><a href="{{ url('posts/'.$post['id'].'/activity?action=share') }}">{{ $post['internal_retweets']."/".$post['retweets'] }}</a></td>
                            <td class="center">{{ date("d M Y h:i:s A" , strtotime($post['created_time'])) }}</td>
                            <td class="center"><a href="{{ $post['link'] }}" target="_blank"><i class="fa fa-globe fa-fw"></i></a></td>
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
                  <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} entries </div>
                </div>
                <div class="col-sm-8">
                  <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                    {{ $posts->links() }}
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