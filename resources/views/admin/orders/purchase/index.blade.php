@extends('layout.admin.index' )
@section('title')
    Danh sách đơn đặt hàng bán
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-list orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách đơn đặt hàng bán</h3>
            <hr/>
            <a href="{{ route('order.create', ['type' => 'purchase']) }}" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Thêm đơn hàng</a>
        </div>
        <div class="box-body">

            @include('admin.orders._formSearch', ['type' => 'purchase'])

            <div class="table-responsive">
                <table id="simple-table" class="table table-bordered table-hover table-responsive">
                    <thead>
                    <tr class="">
                        <th class="center">
                            ID
                        </th>
                        <th class="center">Mã hóa đơn</th>
                        <th class="center">Ngày hóa đơn</th>
                        <th class="center">Khách hàng</th>
                        <th class="center">Nhân viên</th>
                        <th class="center">Tình trạng</th>
                        <th class="center">Giảm giá</th>
                        <th class="center">Số tiền</th>
                        <th class="center" width="10%"></th>
                    </tr>
                    </thead>
                    <tbody class="table_data">
                    @include('admin.orders.purchase._data')
                    @if(count($data) > 0)
                    <tr class="bg-info text-right">
                        <td colspan="7" class="bolder text-uppercase">Tổng tiền</td>
                        <td class="bolder">{{ number_format($totalMoney)}}</td>
                        <td></td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="row text-center">
                @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $data->appends(request()->except('page'))->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection