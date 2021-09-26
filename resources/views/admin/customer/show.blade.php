@extends('layout.admin.index')
@section('title')
   Thông tin khách hàng
@endsection
@section('content')
    @php
        $title = 'khách hàng';
        if($customer->IsSupplier) {
            $title = 'nhà cung cấp';
        }
    @endphp
   <div class="page-header"><a class="btn btn-danger" href="{{ route('customer.index') }}"><i class="fa fa-arrow-left"></i> Quay lại</a> <h2>Thông tin {{ $title }}</h2></div>
    <div id="user-profile-1" class="user-profile row">
        <div class="col-xs-12 col-sm-12">
        <div class="profile-user-info profile-user-info-striped">
            <div class="profile-info-row">
                <div class="profile-info-name">Tên khách hàng</div>
                <div class="profile-info-value">
                    <span class="editable editable-click" id="username">{{ $customer->Name }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Mã khách hàng</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->CustomerCode }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Email</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->Email }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Số điện thoại</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->Tel }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Ngày sinh</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ date("d/m/Y", strtotime($customer->BirthDay)) }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Nhóm</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->GroupName }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Người phụ trách</div>
                <div class="profile-info-value">
                    <span class="editable" id="country">{{ $customer->ManagerBy }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">Mô tả</div>
                <div class="profile-info-value">
                    <span class="editable" id="age">{{ $customer->Description }}</span>
                </div>
            </div>
        </div>
        </div>
    </div>
    {{--
    <hr/>
   <ul class="nav nav-tabs">
       <li class="@if($type == 'buy') active @endif">
           <a  href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'buy']) }}">Lịch sử mua hàng</a>
       </li>
       <li class="@if($type == 'order') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'order']) }}">Lịch sử đặt hàng</a>
       </li>
       <li class="@if($type == 'import') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'import']) }}">Lịch sử nhập hàng của nhà cung cấp</a>
       </li>
       <li class="@if($type == 'need-pay') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'need-pay']) }}">Danh sách công nợ phải thu từ khách</a>
       </li>
       <li class="@if($type == 'debit') active @endif"><a href="{{ route("customer.history", ['id' => $customer->Id, 'type' => 'debit']) }}">Danh sách công nợ phải trả nhà cung cấp</a>
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
                    @if($type == 'need-pay' || $type == 'debit')
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
            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $data->appends(request()->except('page'))->links() }}
            @endif
        </div>
    </div>
    --}}
@endsection
