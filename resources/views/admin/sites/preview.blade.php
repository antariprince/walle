@extends('adminlte::page')

@section('htmlheader_title')
	Change Title here!
@endsection


@section('main-content')
	<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Scrape List</h3> 
              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th><a href="{{ route('admin.sites.create') }}" type="button" class="btn bg-purple btn-sm">Add Website</a></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
                
                 <tr>
                @foreach($data[0] as $key => $item)
                  <th>{{ $key }}</th>
                @endforeach
                </tr>

                @foreach($data as $item)
                <tr>
                  @foreach($item as $skey => $sdata)
                  <td>{{ $sdata }}</td>
                  
                  @endforeach

                </tr>
                @endforeach
                
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
@endsection
