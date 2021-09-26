@php $productGroups = getProductGroupList();
    $newCode = getNewProductCode();
@endphp
<div id="newProductModal" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalLabel">Thêm sản phẩm mới</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ route('product.store') }}" id="product_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="autoProductCode" value="0" class="autoProductCode" />
                    <div class="row">
                        <div class="col-xs-12 col-md-8 col-lg-8">
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                    <label class="control-label no-padding-right" for="product_code">Mã sản phẩm </label>
                                    <div class="clearfix">
                                        <input placeholder="Mã hàng" name="product_code" value="{{ $newCode }}"
                                               id="product_code" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class="control-label no-padding-right" for="product_name">Tên sản phẩm</label>
                                <div class="clearfix">
                                    <input placeholder="Tên sản phẩm" name="product_name" value="{{ old('product_name') }}"
                                           id="product_name" class="form-control" type="text">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-md-6 col-lg-6 col-xs-12">
                                <label class=" control-label no-padding-right" for="groupId2">Nhóm hàng</label>
                                <div class="clearfix">
                                    <select name="groupId" id="groupId2" class="form-control">
                                        <option value="" disabled selected>Chọn nhóm hàng</option>
                                        @foreach($productGroups as $brand)
                                            <option {{ old('groupId') == $brand->Id ? 'selected' : '' }} value="{{ $brand->Id }}">{{ $brand->GroupName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                                    <label class=" control-label no-padding-right" for="unit">Đơn vị tính</label>
                                    <div class="clearfix">
                                        <input placeholder="Đơn vị tính" type="text" value="{{ old("unit") }}" name="unit"
                                               class="form-control" id="unit">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group col-md-12 col-lg-6">
                                    <label class=" control-label no-padding-right" for="noteImport">Ghi chú nhập</label>
                                    <div class="clearfix">
                          <textarea id="noteImport" rows="6" class="form-control ckeditor"
                                    name="noteImport">{{ old('noteImport') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 form-group col-md-12 col-lg-6">
                                    <label class=" control-label no-padding-right" for="noteOrder">Lưu ý đặt hàng</label>
                                    <div class="clearfix">
                          <textarea id="noteOrder" rows="6" class="form-control ckeditor"
                                    name="noteOrder">{{ old('noteOrder') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 col-lg-4">
                            <div class="form-group col-xs-12 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="purchasePrice"> Giá nhập </label>
                                <div class="clearfix">
                                    <input placeholder="Giá nhập" name="purchasePrice" value="{{ old('purchasePrice') }}" id="purchasePrice"
                                           class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="unitPrice"> Giá bán </label>
                                <div class="clearfix">
                                    <input placeholder="Giá bán" name="unitPrice" value="{{ old('unitPrice') }}" id="unitPrice"
                                           class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="minInStock">Tối thiểu trong kho</label>
                                <div class="clearfix">
                                    <input placeholder="Tối thiểu trong kho" type="number" value="{{ old("minInStock") }}" min="0" name="minInStock"
                                           class="form-control" id="minInStock">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="maxInStock">Tối đa trong kho</label>
                                <div class="clearfix">
                                    <input placeholder="Tối đa trong kho" type="number" value="{{ old("maxInStock") }}" min="0" name="maxInStock"
                                           class="form-control" id="maxInStock">
                                </div>
                            </div>
                            <div class="center col-xs-12 col-sm-12 col-lg-12 col-md-12">
                                <div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
                                    <label class="bolder bigger-110 " for="picture">Ảnh</label>
                                    <input type="file" name="picture" class="form-control" id="picture">
                                    <span class="text-danger">{{ $errors->first('picture') }}</span>
                                </div>
                                <img id="show_image" src="" alt="" width="200" height="100" class="img-responsive img-thumbnail">
                            </div>
                        </div>

                        <div class="col-xs-12 form-group col-md-12 col-lg-12">
                            <label class=" control-label no-padding-right" for="description">Mô tả</label>
                            <div class="clearfix">
                          <textarea id="desc" rows="6" class="form-control ckeditor"
                                    name="description">{{ old('description') }}</textarea>
                            </div>
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
