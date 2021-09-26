@extends('layout.admin.index')
@section('title')
    Chi tiết thống báo
@endsection
@section('content')
   
    <div class="page-header"><a class="btn btn-danger" href="{{ route('notification.index') }}"><i
                class="fa fa-arrow-left"></i> Quay lại</a>
        <h2>Chi tiết thông báo</h2>
    </div>
    <div id="user-profile-1" class="user-profile row">
        <div class="col-xs-12 col-sm-12">
            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name">Tiêu đề</div>
                    <div class="profile-info-value">
                        <span class="editable editable-click" id="username">{{ $notifi->title }}</span>
                    </div>
                </div>
                
                <div class="profile-info-row">
                    <div class="profile-info-name">Nội dung</div>
                    <div class="profile-info-value">
                        <span class="editable" id="country">{{ $notifi->body }}</span>
                    </div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-name">Chuyên mục</div>
                    <div class="profile-info-value">
                        <span class="" id="group">@foreach ($notifi->send_to as $item)
                            {{$item}},
                        @endforeach</span>
                    </div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-name">Ngày gửi</div>
                    <div class="profile-info-value">
                        <span class="editable" id="country">{{ $notifi->created_at }}</span>
                    </div>
                </div>




            </div>
        </div>
    </div>
    {{-- <hr/>
   <ul class="nav nav-tabs">
       <li class="@if ($type == 'buy') active @endif">
           <a  href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'buy']) }}">Lịch sử mua hàng</a>
       </li>
       <li class="@if ($type == 'order') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'order']) }}">Lịch sử đặt hàng</a>
       </li>
       <li class="@if ($type == 'import') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'import']) }}">Lịch sử nhập hàng của nhà cung cấp</a>
       </li>
       <li class="@if ($type == 'need-pay') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'need-pay']) }}">Danh sách công nợ phải thu từ khách</a>
       </li>
       <li class="@if ($type == 'debit') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'debit']) }}">Danh sách công nợ phải trả nhà cung cấp</a>
       </li>
   </ul>
    <br/>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xs-12">
            <table id="simple-table" class="table table-bordered table-hover table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Số hóa đơn</th>
                    <th class="center">Ngày hóa đơn</th>
                    <th>Tổng tiền</th>
                    @if ($type == 'need-pay' || $type == 'debit')
                        <th>Ghi chú</th>
                    @else
                        <th>Trạng thái</th>
                        <th>Nhân viên phụ trách</th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody id="table_body" class="table_data">
                @include('admin.customer.history._data')
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 text-center">
            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $data->appends(request()->except('page'))->links() }}
            @endif
        </div>
    </div> --}}
@endsection
