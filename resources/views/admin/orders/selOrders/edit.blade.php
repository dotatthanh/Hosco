@extends('layout.admin.index' )
@section('title')
    Sửa đơn hàng
@stop
@section('extra_css')
@stop
@section('content')
    @php
        $fmt = numfmt_create( 'vi_VN', \NumberFormatter::CURRENCY );
        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, isset($orderConfig->format_number_money)? $orderConfig->format_number_money:2);
    @endphp

    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Sửa đơn hàng</h3>
        </div>
        <div class="box-body">

        <form action="{{ route("admin.orders.update", ['type' => $type, 'id' => $order->Id]) }}" method="POST" id="create-order-form">
            @csrf
            <input type="hidden" id="newPrice" name="newPrice" readonly />
            <input type="hidden" id="is_overselling_allowed" name="is_overselling_allowed" value="{{ isset($orderConfig->enable_stock)?:false }}" />
            <div class="row">
                <div class="form-group col-md-6 col-lg-2 col-xs-12">
                    <label class="control-label no-padding-right" for="locationId">Chi nhánh</label>
                    <div class="clearfix">
                        <select name="locationId" class="form-control" id="locationId" required>
                            <option value="">-- Chọn chi nhánh --</option>
                            @foreach($locations as $loc)
                                <option value="{{$loc->Id}}" @if($order->Location == $loc->Id) selected @endif>{{ $loc->LocationName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6 col-lg-2 col-xs-12">
                    <label class="control-label no-padding-right" for="orderCode"> Số phiếu </label>
                    <div class="clearfix">
                        <input placeholder="Số phiếu" name="orderCode" value="{{ old('orderCode', $order->OrderCode) }}"
                               id="orderCode" class="form-control" type="text" readonly>
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
                            <option value="{{$order->CustomerId}}" selected="selected">{{$order->CustomerName}}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6 col-lg-3 col-xs-12">
                    <label class="control-label no-padding-right" for="storeId">Kho hàng</label>
                    <div class="clearfix">
                        <select name="storeId" class="form-control" id="storeId"></select>
                    </div>
                </div>

                <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                    <label class="control-label no-padding-right" for="employeeId">Nhân viên</label>
                    <div class="clearfix">
                        <select name="employeeId" class="form-control" id="employeeId">
                            <option value="">-- Tất cả --</option>
                            @foreach($employees as $loc)
                                <option value="{{ $loc->Id }}" @if($loc->Id == old('employeeId', $order->EmployeeId)) selected @endif>{{ $loc->EmployeeName}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6 col-lg-2 col-xs-12">
                    <label class="control-label no-padding-right" for="orderDate"> Ngày </label>
                    <div class="clearfix">
                        <input placeholder="Ngày" name="orderDate" value="{{ old('orderDate', date_format(date_create($order->OrderDate),"Y-m-d")) }}"
                               id="orderDate" class="form-control" type="date">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-6 form-horizontall">
                    <div class="form-group">
                        <label class="control-label no-padding-right">Sản phẩm</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                            <input class="form-control select-product" id="select-product" name="autosuggest-product">
                            <span class="input-group-addon add-product" data-toggle="modal" data-target="#newProductModal"><i class="fa fa-plus-circle text-primary"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="products_table">
                        <thead>
                        <tr>
                            <th width="25%">Sản phẩm</th>
                            <th width="5%">ĐVT</th>
                            <th width="7%">Số lượng</th>
                            <th width="6%">Đơn giá</th>
                            <th width="7%">% Chiết khấu</th>
                            <th width="7%">Tiền Chiết khấu</th>
                            <th width="7%">Thành tiền</th>
                            <th width="10%">Ghi chú</th>
                            <th width="2%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->OrderDetail as $k => $ord)
                            <tr class="{{$ord->Id}}">
                                <td><a href="#" data-toggle="modal" data-target="#productModal" data-product="{{json_encode($ord)}}">{{$ord->ProductCode}} / {{$ord->ProductName}}
                                        <i class="fa fa-info-circle"></i></a>
                                    <input type="hidden" name="products[]" value="{{$ord->ProductId}}" />
                                </td>
                                <td>{{$ord->Unit}}</td>
                                <td class="qty">
                                    <input type="number" name="quantities[]" class="form-control qty" value="{{number_format($ord->Qty,isset($orderConfig->format_number_qty)? $orderConfig->format_number_qty:2)}}" min="0"/>
                                </td>
                                <td class="price" width="10%">
                                    <input type="text" name="prices[]" value="{{$ord->Price}}" class="form-control price"/>
                                </td>
                                <td class=""><input type="number" name="f_discount[]" value="{{$ord->f_Discount}}" min="0" max="100" class="form-control f-discount" /></td>
                                <td class=""><input type="number" name="m_discount[]" value="{{$ord->m_Discount}}" min="0" class="form-control m-discount" /></td>
                                <td class="total">
                                    @php $total = $ord->Qty * $ord->Price @endphp
                                @if($ord->f_Discount > 0)
                                    @php $total = $ord->Qty * ($ord->Price - $ord->f_Discount * $ord->Price/100) - $ord->m_Discount; @endphp
                                @elseif($ord->m_Discount > 0)
                                    @php $total = $ord->Qty * ($ord->Price - $ord->m_Discount); @endphp
                                @endif
                                <span>{{ numfmt_format_currency($fmt, $total, 'VND') }}</span>
                                <input type="hidden" name="total[]" value="{{$total}}" />
                            </td>
                            <td class=""><textarea type="text" name="description[]" value="{{$ord->Description}}" class="form-control"></textarea></td>
                            <td><a href="#" class="btn btn-xs btn-danger remove-row"><i class="fa fa-minus-circle"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr/>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label no-padding-right" for="discount">Ghi chú</label>
                            <textarea class="form-control" name="desc" rows="4">{{$order->Discription}}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label class="control-label col-sm-4 no-padding-right" for="discount">Giảm giá</label>
                            <div class="col-sm-4">
                                <select name="discountType" class="form-control discountType">
                                    <option value="">-- Lựa chọn --</option>
                                    <option value="fDiscount" @if ($order->f_Discount > 0) selected @endif>Tỷ lệ %</option>
                                    <option value="mDiscount" @if ($order->m_Discount > 0) selected @endif>Tiền mặt</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="number" class="form-control discount" value="{{$order->f_Discount}}" min="0" name="discount" disabled style="display: none">
                                    <input type="number" class="form-control discountAmount" value="{{ $order->m_Discount }}" min="0" name="discountAmount" disabled style="display: none">
                                    <span class="input-group-addon" style="display: none">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4 no-padding-right" for="discount">VAT</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <select name="vat" class="form-control vat">
                                        <option value="">-- Chọn --</option>
                                        <option value="0" @if($order->f_Vat == 0) selected @endif>0</option>
                                        <option value="5" @if($order->f_Vat == 5) selected @endif>5</option>
                                        <option value="10" @if($order->f_Vat == 10) selected @endif>10</option>
                                    </select>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4 no-padding-right text-bold" for="totalPrice">Tổng giá trị đơn hàng</label>
                            <div class="col-sm-8">
                                <input name="totalPriceText" value="{{ numfmt_format_currency($fmt, $order->OrderTotalDiscount, 'VND') }}"
                                       class="form-control priceText" type="text" disabled/>
                                <input name="totalPrice" value="{{$order->OrderTotalDiscount}}"
                                       class="form-control" type="hidden" readonly id="totalPrice" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-4 no-padding-right" for="paid">Tiền khách đưa</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control paid" value="{{ isset($order->m_TotalMoney)?:'' }}" min="0" name="mTotalMoney">
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
            <a class="btn btn-danger" href="{{ route('order.index', $type) }}">Quay lại</a>
        </div>
    </form>
        </div>
    </div>
    @include('admin.customer._modal')
    @include('admin.products._modal')
    @include('admin.products._newmodal')
@endsection

@include('admin.orders._js')

