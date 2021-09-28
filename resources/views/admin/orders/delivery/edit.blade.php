@extends('layout.admin.index' )
@section('title')
    Cập nhật trạng thái giao hàng
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Cập nhật trạng thái giao hàng</h3>
        </div>
        <div class="box-body">

            <form action="{{ route("order.delivery.change_status", $id) }}" method="POST" id="create-order-form">
            @csrf
                <div class="row">
                    <div class="form-group col-md-6 col-lg-6 col-xs-12">
                        <label class="control-label no-padding-right" for="status">Trạng thái giao hàng</label>
                        <div class="clearfix">
                            <select name="status" class="form-control" id="status" required>
                                    <option value="-1" @if($status == '-1') selected @endif>Chờ xác nhận</option>
                                    <option value="0" @if($status == "0") selected @endif>Đã xác nhận</option>
                                    <option value="1" @if($status == "1") selected @endif>Đang giao</option>
                                    <option value="2" @if($status == "2") selected @endif>Đã giao</option>
                                    <option value="3" @if($status == "3") selected @endif>Thất bại</option>
                            </select>
                        </div>
                    </div>
                </div>
            <hr/>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Lưu">
                <a class="btn btn-danger" href="{{ route('order.index', $type) }}">Quay lại</a>
            </div>
            </form>
        </div>
    </div>
@endsection

@include('admin.orders._js')

