<form action="{{ route("admin.orders.store") }}" method="POST" id="create-order-form">
    @csrf
    <input type="hidden" name="type" value="{{$type}}" />
    <input name="orderCode" value="{{ old('orderCode') }}" id="orderCode" class="form-control" type="hidden" readonly>
    <input type="hidden" id="is_overselling_allowed" name="is_overselling_allowed" value="{{ isset($orderConfig->enable_stock)?:false }}" />

    <div class="row">
        <div class="form-group col-md-6 col-lg-2 col-xs-12">
            <label class="control-label no-padding-right" for="locationId">Chi nhánh</label>
            <div class="clearfix">
                <select name="locationId" class="form-control" id="locationId" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    @foreach($locations as $loc)
                        <option value="{{$loc->Id}}">{{ $loc->LocationName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group col-md-6 col-lg-3 col-xs-12">
            <label class="control-label no-padding-right" for="storeId">Kho hàng </label>
            <div class="clearfix">
                <select name="storeId" class="form-control" id="storeId">
                    <option value="">-- Chọn kho hàng --</option>
                </select>
            </div>
        </div>

        <div class="form-group col-md-6 col-lg-3 col-xs-12">
            <label class="control-label no-padding-right" for="customerId">Khách hàng
            </label>
            <span class="pull-right">
                    <button class="btn-info add-customer-btn" type="button" data-toggle="modal" data-target="#myModal">Thêm mới KH</button>
                </span>
            <div class="clearfix">
                <select name="customerId" class="form-control" id="customerId">
                    <option value="">-- Chọn khách hàng --</option>
                </select>
            </div>
        </div>

        <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
            <label class="control-label no-padding-right" for="employeeId">Nhân viên</label>
            <div class="clearfix">
                <select name="employeeId" class="form-control" id="employeeId">
                    <option value="">-- Tất cả --</option>
                    @foreach($employees as $loc)
                        <option value="{{ $loc->Id }}" @if($loc->Id == old('employeeId')) selected @endif>{{ $loc->EmployeeName}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group col-md-6 col-lg-2 col-xs-12">
            <label class="control-label no-padding-right" for="orderDate"> Ngày </label>
            <div class="clearfix">
                <input placeholder="Ngày" name="orderDate" value="{{ old('orderDate', date('Y-m-d', time())) }}"
                       id="orderDate" class="form-control" type="date">
            </div>
        </div>
        {{--
        <div class="col-md-6 col-lg-2 col-sm-12 form-group">
            <label class="control-label no-padding-right" for="orderStatus">Trạng thái</label>
            <div class="clearfix">
                <select name="orderStatus" class="form-control" id="orderStatus">
                    @if($type == 'sel')
                        <option value="0">Chưa thu</option>
                        <option value="1">Đã thu</option>
                        <option value="2">Thu 1 phần</option>
                    @else
                        <option value="0">Đang xử lý</option>
                        <option value="1">Hoàn thành</option>
                        <option value="2">Đã xử lý</option>
                    @endif
                </select>
            </div>
        </div>
        --}}
    </div>

    <div class="row">
        <div class="col-sm-6 form-horizontall">
            <div class="form-group">
                <label class="control-label no-padding-right">Sản phẩm</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                    <input class="form-control select-product" id="select-product" name="autosuggest-product" disabled>
                    <span class="input-group-addon add-product" data-toggle="modal" data-target="#newProductModal"><i class="fa fa-plus-circle text-primary"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                @include('admin.orders._productTable')
            </div>
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-12">--}}
            {{--                        <button id="add_row" class="btn btn-sm pull-left">+ Add Row</button>--}}
            {{--                        <button id='delete_row' class="btn-sm pull-right btn btn-danger">- Delete Row</button>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            <hr/>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label no-padding-right" for="discount">Ghi chú</label>
                        <textarea class="form-control" name="desc" rows="4"></textarea>
                    </div>
                </div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right" for="discount">Giảm giá</label>
                        <div class="col-sm-4">
                            <select name="discountType" class="form-control discountType">
                                <option value="">-- Lựa chọn --</option>
                                <option value="fDiscount">Tỷ lệ %</option>
                                <option value="mDiscount">Tiền mặt</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="number" class="form-control discount" value="" min="0" name="discount" disabled style="display: none">
                                <input type="number" class="form-control discountAmount" value="" min="0" name="discountAmount" disabled style="display: none">
                                <span class="input-group-addon" style="display: none">%</span>
                            </div>
                        </div>
                        {{--
                        <label class="control-label col-sm-4 no-padding-right" for="discount">Giảm giá (giá trị)</label>
                        <div class="col-sm-8">
                            <div class="input-group form-group">
                                <input type="number" class="form-control discountAmount" value="" min="0" name="discountAmount">
                                <span class="input-group-addon">VND</span>
                            </div>
                        </div>
                        --}}
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right" for="discount">VAT</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <select name="vat" class="form-control vat">
                                    <option value="">-- Chọn --</option>
                                    <option value="0">0</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                </select>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right text-bold" for="totalPrice">Total</label>
                        <div class="col-sm-8">
                            <input name="totalPriceText" value="@convert(0)"
                                   class="form-control priceText" type="text" disabled/>
                            <input name="totalPrice" value="@convert(0)"
                                   class="form-control" type="hidden" readonly id="totalPrice" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right" for="paid">Tiền khách đưa</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control paid" value="" min="0" name="mTotalMoney">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right" for="paymentLeft">Thừa / Thiếu</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control paymentLeft" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-4 no-padding-right" for="paymentMethod">Hình thức thanh toán</label>
                        <div class="col-sm-8">
                            <select name="paymentMethod" class="form-control" id="paymentMethod">
                                <option value="cash">Tiền mặt</option>
                                <option value="card">Chuyển khoản</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row cardId sr-only">
                        <label class="control-label col-sm-4 no-padding-right" for="bankList">Danh sách ngân hàng</label>
                        <div class="col-sm-8">
                            <select name="cardId" class="form-control" id="bankList"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="Lưu">
        <a class="btn btn-danger" onclick="history.back()">Quay lại</a>
    </div>
</form>

<input type="hidden" value="{{ route('order.index', $type) }}" id="redirect-route">
