@extends('layouts.app')

@section('content')
<div id="page-wrapper">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">{{ Session::get('message') }}</p>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Import Leads from Excel file</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('import_verify') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('import_file') ? ' has-error' : '' }}">
                            <label for="import_file" class="col-md-4 control-label">Excel File: </label>

                            <div class="col-md-6">
                                <input id="import_file" class="form-control" type="file" name="import_file" value="{{ old('import_file') }}" required autofocus>

                                @if ($errors->has('import_file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('import_file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
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
                        @if($data['type']):
                        <input type="hidden" name="file" id="file" value="{{ $data['file'] }}">
                        <input type="hidden" name="source_data" id="source_data" value="{{ $data['source_data'] }}">
                        @endif
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

                            <!-- Modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myModalLabel">Are you sure to upload these data ?</h4>
                                        </div>
                                        <div class="modal-body">
                                        @if($data['type']):
                                            <p>There are total of {{ $data['total'] }} records of lead, out of which {{ $data['error'] }} records are inappropriate or repeated.
                                        @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button id="confirm" type="button" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
@endsection
@section('script')
    <script type="text/javascript">

    @if($data['type'])
        $("#myModal").modal();
    @endif
    $("#confirm").click(function() {
        console.log("thr");
        $("form").attr('action', "/import_leads").submit();
    });
</script>
@endsection

