@php $customerGroups = getCustomerGroupList();
    $customer_code = getNewCustomerCode();
@endphp
<div id="myModal" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalLabel">Thêm mới khách hàng</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ route('customer.store') }}" id="customer_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="autoCustomerCode" value="0" class="autoCustomerCode" />
                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class="control-label no-padding-right" for="customer_name"> Mã khách hàng </label>
                                <div class="clearfix">
                                    <input placeholder="Mã khách hàng" name="customer_code" value="{{ old('customer_code', $customer_code) }}"
                                           id="customer_code" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class="control-label no-padding-right" for="customer_name"> Tên khách hàng </label>
                                <div class="clearfix">
                                    <input placeholder="Tên khách hàng" name="customer_name" value="{{ old('customer_name') }}"
                                           id="customer_name" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-12 form-group">
                                <label class="control-label no-padding-right" for="objectType">Đối tượng</label>
                                <div class="clearfix">
                                    <select name="objectType" class="form-control" id="objectType">
                                        <option value="0" @if(old('objectType') == 0) selected @endif>Khách hàng</option>
                                        <option value="1" @if(old('objectType') == 1) selected @endif>NCC</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class=" control-label no-padding-right" for="groupId">Nhóm khách hàng</label>
                                <div class="clearfix">
                                    <select name="groupId" id="groupId" class="form-control">
                                        <option value="" disabled selected>Chọn nhóm hàng</option>
                                        @foreach($customerGroups as $group)
                                            <option {{ old('groupId') == $group->Id ? 'selected' : '' }} value="{{ $group->Id }}">{{ $group->GroupName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12 col-md-12 col-lg-12">
                            <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class="control-label no-padding-right" for="email"> Email </label>
                                <div class="clearfix">
                                    <input placeholder="Email" name="email" value="{{ old('email') }}"
                                           id="email" class="form-control" type="email">
                                </div>
                            </div>

                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="tel"> Số điện thoại </label>
                                <div class="clearfix">
                                    <input placeholder="Số điện thoại" name="tel" value="{{ old('tel') }}" id="tel"
                                           class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="address"> Địa chỉ </label>
                                <div class="clearfix">
                                    <input placeholder="Địa chỉ" name="address" value="{{ old('address') }}" id="address"
                                           class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="birthday">Ngày sinh</label>
                                <div class="clearfix">
                                    <input placeholder="Ngày sinh" type="date" value="{{ old("birthday") }}" name="birthday"
                                           class="form-control" id="birthday">
                                </div>
                            </div>

                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="taxCode">Mã số thuế</label>
                                <div class="clearfix">
                                    <input placeholder="Mã số thuế" type="text" value="{{ old('taxCode') }}" name="taxCode"
                                           class="form-control" id="taxCode">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 form-group col-md-12 col-lg-12">
                            <label class=" control-label no-padding-right" for="description">Ghi chú</label>
                            <div class="clearfix">
                  <textarea id="description" rows="6" class="form-control"
                            name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="center col-xs-6 col-sm-6 col-lg-6 col-md-6">
                            <div class="form-group {{ $errors->has('cover') ? 'has-error' : '' }}">
                                <label class="bolder bigger-110 " for="avatar">Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control" id="avatar">
                                <span class="text-danger">{{ $errors->first('avatar') }}</span>
                            </div>
                            <img id="show_image" src="" alt="" width="200" height="100" class="img-responsive img-thumbnail">
                        </div>
                    </div>

                    <div class="form-group clearfix"></div>
                    <div class="modal-footer" style="background: none;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
