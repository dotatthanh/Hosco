@extends('layout.admin.index' )
@section('title')
   Danh sách đơn hàng bán
@stop
@section('extra_css')
@stop
@section('content')

    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-list orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách đơn hàng bán</h3>
            <hr/>
            <a href="{{ route('order.create', ['type' => 'sel']) }}" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Thêm đơn hàng</a>
        </div>
        <div class="box-body">

    @include('admin.orders._formSearch', ['type' => 'sel'])

   <div class="table-responsive">
      <table id="simple-table" class="table table-bordered table-hover table-responsive">
         <thead>
         <tr class="">
            <th class="center">
               ID
            </th>
            <th class="center">Số hóa đơn</th>
            <th class="center">Ngày hóa đơn</th>
            <th class="center">Tên khách hàng</th>
            <th class="center">Số liên hệ</th>
             <th class="center">Người tạo</th>
            <th class="center">Chi nhánh</th>
            <th class="center">Tình trạng thanh toán</th>
            <th class="center">Tổng tiền</th>
            <th class="center" width="10%"></th>
         </tr>
         </thead>
         <tbody class="table_data">
            @include('admin.orders.selOrders._data')
            @if(count($data) > 0)
            <tr class="bg-info text-right">
                <td colspan="8" class="bolder text-uppercase">Tổng tiền</td>
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
    @include('admin.orders.selOrders._modalIncome')
@endsection