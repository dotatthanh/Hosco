@extends('layout.admin.index' )
@section('title')
    Thêm đơn hàng
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Thêm đơn hàng</h3>
        </div>
        <div class="box-body">
    @include('admin.orders._form')
        </div>
    </div>
    @include('admin.customer._modal')
    @include('admin.products._modal')
    @include('admin.products._newmodal')
@endsection

@include('admin.orders._js')
