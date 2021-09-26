
<form method="get" action="{{ route('order.index', ['type' => $type]) }}" id="report-search" >
    <div class="row">
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="searchByOrderInfo"><b>Tên, mã hóa đơn</b></label>
            <div class="clearfix">
                <input type="text" placeholder="Tên, mã hóa đơn" class="form-control nav-search-input"
                       autocomplete="off" name="searchByOrderInfo" value="{{ $searchByOrderInfo }}"/>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="searchByCustomerInfo"><b>Tên, mã, số điện thoại khách hàng</b></label>
            <div class="clearfix">
                <input type="text" placeholder="Tên, mã, số điện thoại khách hàng" class="form-control nav-search-input"
                       autocomplete="off" name="searchByCustomerInfo" value="{{ $searchByCustomerInfo }}"/>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="searchByProductInfo"><b>Tên, mã sản phẩm</b></label>
            <div class="clearfix">
                <input type="text" placeholder="Tên, mã sản phẩm" class="form-control nav-search-input"
                       autocomplete="off" name="searchByProductInfo" value="{{ $searchByProductInfo }}"/>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="locationId"><b>Chi nhánh</b></label>
            <div class="clearfix">
                <select name="locationId" class="form-control" id="locationId">
                    <option value="">-- Tất cả --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->Id }}" @if($loc->Id == $locationId) selected @endif>{{ $loc->LocationName}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="employeeId"><b>Nhân viên phụ trách</b></label>
            <div class="clearfix">
                <select name="employeeId" class="form-control" id="employeeId">
                    <option value="">-- Tất cả --</option>
                    @foreach($employees as $loc)
                        <option value="{{ $loc->Id }}" @if($loc->Id == $employeeId) selected @endif>{{ $loc->EmployeeName}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="orderStatus"><b>Trạng thái</b></label>
            <div class="clearfix">
                <select name="orderStatus" class="form-control" id="orderStatus">
                    <option value="-1">-- Tất cả --</option>
                    @if($type == 'sel')
                        <option value="0" @if($orderStatus == 0) selected @endif>Đã thu</option>
                        <option value="2" @if($orderStatus == 2) selected @endif>Chưa thu</option>
                        <option value="1" @if($orderStatus == 1) selected @endif>Thu 1 phần</option>
                    @else
                        <option value="0" @if($orderStatus == 0) selected @endif>Hoàn thành</option>
                        <option value="2" @if($orderStatus == 2) selected @endif>Đang đặt hàng</option>
                        <option value="1" @if($orderStatus == 1) selected @endif>Đã xử lý</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right text-bold" for="orderId"><b>Thời gian</b></label>
            <div class="clearfix">
                <input type="text" name="dates" value="{{ $dates }}" class="form-control" readonly/>
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
@section('extra_js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(document).ready(function () {
        	@if(isDelete(env('ORDER_ROLE_CODE')) || isDelete(env('SALES_ORDER_ROLE_CODE')))
               	deleteAjax("/admin/orders/{{$type}}/", "delete_me", "Order");
            @endif
            
            $('input[name="dates"]').daterangepicker({
                locale: {
                    format: 'D/M/YYYY',
                    "customRangeLabel": "Tùy chọn",
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(6, 'days'), moment()],
                    '30 ngày trước': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
        });
    </script>
@stop
