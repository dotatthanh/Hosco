@extends('layout.admin.index' )
@section('title')
    @lang('models/products.plural') @lang('ext.list')
@stop
@section('extra_css')
@stop
@section('content')

    <a class="" onclick="history.back()">BACK</a><div class="page-header"><h2>Lịch sử nhập hàng của sản phẩm "{{ $product->Name }}"</h2></div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xs-12">
            <table id="simple-table" class="table table-bordered table-hover table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Mã phiếu</th>
                    <th>Tên phiếu</th>
                    <th>Ngày đặt hàng</th>
                    <th>Nhân viên phụ trách</th>
                    <th>Số lượng</th>
                    <th>Giá bán</th>
                    <th>ĐVT</th>
                    <th>Khuyến mãi</th>
                    <th>Trạng thái thanh toán</th>
                </tr>
                </thead>
                <tbody id="table_body" class="table_data">
                @include('admin.products.history._data')
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 text-center">
            {{ $data->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection()
@section('extra_js')

@stop
