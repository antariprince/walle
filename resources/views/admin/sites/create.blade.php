@extends('adminlte::page')

@section('htmlheader_title')
	Change Title here!
@endsection


@section('main-content')
<div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">General Elements</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" action="{{ route('admin.sites.store') }}" method="post" id="scrapeSiteForm">
                <!-- text input -->
                {{ csrf_field() }}
                <div class="form-group">
                  <label>URL</label>
                  <input type="text" name="url" class="form-control" placeholder="Enter URL" value="{{ old('url') }}">
                </div>

                  <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" class="singlepageselector" name="singlepage" id="singlepage1" value="single">
                      Single Page
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" class="singlepageselector" name="singlepage" id="singlepage12" value="multi" checked="checked">
                      Multi Page
                    </label>
                  </div>
                </div>

                  <div class="form-group" id="page_string">
                  <label>Page String</label>
                  <input type="text" name="page_string" class="form-control" value="{{ old('page_string') }}">
                  </div>

                  <div class="box box-danger">

                    <div class="box-body dynamic_scrape_json">
                      <div class="row">
                        <div class="col-xs-2">
                          <input type="text" class="form-control" placeholder="Title" name="title[]">
                        </div>
                        <div class="col-xs-2">
                          <input type="text" class="form-control" placeholder="Target Element" name="element[]">
                        </div>
                        <div class="col-xs-3">
                          <input type="text" class="form-control" placeholder="Attribute" name="attribute['title'][]">
                        </div>
                        <div class="col-xs-4">
                          <input type="text" class="form-control" placeholder="Filters" name="filters[]">
                        </div>

                      </div>
                    </div>

                    
                    <!-- /.box-body -->
                  </div>

                  <div class="field_wrapper">
                  
                    <div>
                        <a href="javascript:void(0);" class="btn bg-purple margin add_button_purple" title="Add field">Add Elements</a>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <div class="text-center"><button id="saveScrapeButton" class="btn btn-success" type="submit">
                        Save Site
                    </button>
                  </div>
                </div>

                


              </form>
            </div>
            <!-- /.box-body -->
          </div>
	
@endsection
@section('scripts')
<script src="{{ asset('scrape/js/jquery3.0.min.js') }}"></script>
<script src="{{ asset('scrape/js/app.js') }}"></script>
@endsection

