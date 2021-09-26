@extends('layout.admin.index' )
@section('title')
    @lang('ext.list') @lang('models/customers.plural')
@stop
@section('extra_css')

@stop

@section('content')


    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách thông báo</h3>
        </div>
        <div class="box-body">
            <div class="pull-rightt">
                <a href="{{ route('notification.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i>
                    Tạo thông báo</a>
            </div>

            <hr />

            <form method="get" action="{{ route('notification.index') }}" id="report-search">
                <div class="row">
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right text-bold" for="orderId"><b>Tiêu Đề</b></label>
                        <div class="clearfix">
                            <input type="text" placeholder="Tiêu Đề Bài Viết" class="form-control nav-search-input"
                                autocomplete="off" name="search" value="{{ $search}}" />
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="groupId"><b>Từ</b></label>
                        <div class="clearfix">
                            <input type="date"  class="form-control nav-search-input"
                                autocomplete="off" name="dateFrom" value="{{ $dateFrom}}" />
                        </div>
                        
                    </div>
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="groupId"><b>Đến</b></label>
                        <div class="clearfix">
                            <input type="date"  class="form-control nav-search-input"
                                autocomplete="off" name="dateTo" value="{{ $dateTo }}" />
                        </div>
                        
                    </div>

                    <div class="col-md-6 col-lg-1 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right">&nbsp;</label>
                        <div class="clearfix">
                            <button type="submit" class="btn btn-primary btn-block">
                                <span class="fa fa-search"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xs-12">
                    <div class="table-responsive">

                        <table id="simple-table" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="center">Tiêu đề</th>
                                    <th>Nội dung gửi</th>

                                    <th>Ngày tạo</th>
                                    {{-- <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th> --}}

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_body" class="table_data">
                                {{-- @php
                                    var_dump($data);
                                @endphp --}}
                                @include('admin.notification._data')
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->appends(request()->except('page'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection()
@section('extra_js')

@stop
