<form method="get" action="{{ route('report.index', ['type' => $type]) }}" id="report-search" >
    <div class="row">
    	@if(in_array($type, ['income', 'outcome']))
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="location"><b>Chi nhánh</b></label>
            <div class="clearfix">
                <select name="locationId" class="form-control" id="location">
                    <option value="">Chọn chi nhánh</option>
                    @foreach($location as $loc)
                        <option value="{{ $loc->Id }}" @if($locationId == $loc->Id) selected @endif>{{ $loc->LocationName}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="orderSearch"><b>Mã phiếu</b></label>
            <div class="clearfix">
                <input type="text" name="orderSearch" value="{{ $orderSearch }}" placeholder="Mã phiếu" class="form-control" id="orderSearch"/>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="customerInfoSearch"><b>Tên, mã, số điện thoại khách hàng</b></label>
            <div class="clearfix">
                <input type="text" name="customerInfoSearch" value="{{ $customerInfoSearch }}" placeholder="Tên, mã, số điện thoại khách hàng" class="form-control" id="customerInfoSearch" />
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="descriptionSearch"><b>Nội dung ghi chú</b></label>
            <div class="clearfix">
                <input type="text" name="descriptionSearch" value="{{ $descriptionSearch }}" placeholder="Nội dung ghi chú" class="form-control" id="descriptionSearch" />
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="orderId"><b>Thời gian</b></label>
            <div class="clearfix">
                <input type="text" name="dates" value="{{ $dates }}" class="form-control" readonly/>
            </div>
        </div>
        
        @elseif($type == 'month')
        	<div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            	<label class="control-label no-padding-right"><b>Tháng/Month</b></label>
                <div class="clearfix">
                	<div class="input-group date" id="dpMonths" data-date="{{$date}}" data-date-format="mm/yyyy" data-date-viewmode="years" data-date-minviewmode="months">
                        <input class="form-control" size="16" type="text" name="date" value="{{$date}}" readonly>
                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        @elseif($type == 'monthyear')
        	<div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            	<label class="control-label no-padding-right"><b>Năm/Year</b></label>
                <div class="clearfix">
                	<div class="input-group date" id="dpMonths" data-date-format="yyyy" data-date-viewmode="years" data-date-minviewmode="years">
                        <input class="form-control" size="16" type="text" name="date" value="{{$date}}" readonly>
                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        @elseif($type == 'location')
        	<div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
                <label class="control-label no-padding-right" for="orderId"><b>Thời gian/Time</b></label>
                <div class="clearfix">
                    <input type="text" name="dates" value="{{ $dates }}" class="form-control" readonly/>
                </div>
            </div>
        @else
        	<div class="col-md-6 col-lg-3 col-sm-12 col-xs-12 form-group">
            	<label class="control-label no-padding-right"><b>Năm/Year</b></label>
                <div class="clearfix">
                    <input type="text" name="date" value="{{ $date }}" class="form-control" readonly/>
                </div>
            </div>
        @endif
        
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
