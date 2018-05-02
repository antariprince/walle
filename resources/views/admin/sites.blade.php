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
                  <th>ID</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Reason</th>
                  <th>Action</th>
                </tr>
                @foreach($sitelist->user_sites as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->user->name }}</td>
                  <td>11-7-2014</td>
                  <td><a href="{{ $item->url }}">{{ $item->url }}</a></td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('admin.sites.preview',['id'=> $item->id]) }}" type="button" class="btn bg-maroon"><i class="fa fa-search"></i></a>
                      <a href="{{ route('admin.sites.downloadcsv',['id'=> $item->id]) }}" type="button" class="btn bg-olive"><i class="fa fa-table"></i></a>
                      <a href="{{ route('admin.sites.getjson',['id'=> $item->id]) }}" target="_blank" type="button" class="btn bg-purple"><i class="fa fa-rss"></i></a>
                      <a href="{{ route('admin.sites.edit',['id'=> $item->id]) }}" type="button" class="btn btn-info"><i class="fa fa-edit"></i></a>
                      <a href="{{ route('admin.sites.destroy',['id'=> $item->id]) }}" type="button" class="btn btn-danger"><i class="fa fa-close"></i></a>

                    </div>
                  </td>

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
