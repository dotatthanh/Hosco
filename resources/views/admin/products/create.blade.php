@extends('layout.admin.index' )
@section('title')
    Thêm sản phẩm
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Thêm sản phẩm</h3>
        </div>
        <div class="box-body">
            <form method="post" action="{{ route('product.store') }}" id="product_form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-lg-8">
                        <div class="form-group col-md-3 col-lg-3 col-xs-12">
                            <label class="control-label no-padding-right" for="product_code"> Mã sản phẩm </label>
                            <div class="clearfix">
                                <input placeholder="Mã hàng" name="product_code" value="{{ $newCode }}"
                                    id="product_code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-lg-3 col-xs-12">
                            <label class="control-label no-padding-right" for="product_name"> Tên sản phẩm </label>
                            <div class="clearfix">
                                <input placeholder="Tên sản phẩm" name="product_name" value="{{ old('product_name') }}"
                                    id="product_name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-lg-3 col-xs-12">
                            <label class=" control-label no-padding-right" for="groupId">Nhóm hàng</label>
                            <div class="clearfix">
                                <select name="groupId" id="groupId" class="form-control">
                                    <option value="" disabled selected>Chọn nhóm hàng</option>
                                    @foreach ($productGroups as $brand)
                                        <option {{ old('groupId') == $brand->Id ? 'selected' : '' }}
                                            value="{{ $brand->Id }}">{{ $brand->GroupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-md-3 col-lg-3">
                            <label class=" control-label no-padding-right" for="unit">Đơn vị tính</label>
                            <div class="clearfix">
                                <input placeholder="Đơn vị tính" type="text" value="{{ old('unit') }}" name="unit"
                                    class="form-control" id="unit">
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
                        <div class="col-xs-12 form-group col-md-12 col-lg-12">
                            <label class=" control-label no-padding-right" for="description">Mô tả</label>
                            <div class="clearfix">
                                <textarea id="description" rows="6" class="form-control ckeditor"
                                    name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-4 col-lg-4">
                        <div class="form-group col-xs-12 col-md-6 col-lg-6">
                            <label class=" control-label no-padding-right" for="purchasePrice"> Giá nhập </label>
                            <div class="clearfix">
                                <input placeholder="Giá nhập" name="purchasePrice" value="{{ old('purchasePrice') }}"
                                    id="purchasePrice" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-md-6 col-lg-6">
                            <label class=" control-label no-padding-right" for="unitPrice"> Giá bán </label>
                            <div class="clearfix">
                                <input placeholder="Giá bán" name="unitPrice" value="{{ old('unitPrice') }}"
                                    id="unitPrice" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group col-xs-12 col-md-6 col-lg-6">
                            <label class=" control-label no-padding-right" for="minInStock">Tối thiểu trong kho</label>
                            <div class="clearfix">
                                <input placeholder="Tối thiểu trong kho" type="number" value="{{ old('minInStock') }}"
                                    min="0" name="minInStock" class="form-control" id="minInStock">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-md-6 col-lg-6">
                            <label class=" control-label no-padding-right" for="maxInStock">Tối đa trong kho</label>
                            <div class="clearfix">
                                <input placeholder="Tối đa trong kho" type="number" value="{{ old('maxInStock') }}"
                                    min="0" name="maxInStock" class="form-control" id="maxInStock">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-md-12 col-lg-12">

                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="tel"> Chuyên mục sản phẩm </label>
                                <div class="clearfix">
                                    <input type="checkbox" name="IsNew" id="IsNew">Sản phẩm mới<br>

                                    <input type="checkbox" name="IsFeature" id="IsFeature">Sản phẩm nổi bật


                                </div>


                            </div>
                            <div class="col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="tel"> % Giảm giá </label>
                                <div class="clearfix">
                                    <input placeholder="Phần trăm giảm giá" type="number" value="{{ old('Discount') }}"
                                    min="0" max="100" name="Discount" class="form-control" id="Discount">


                                </div>


                            </div>

                        </div>
                        <div class=" col-xs-12 col-sm-12 col-lg-12 col-md-12">
                            <div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
                                <label class="bolder bigger-110 " for="picture">Ảnh</label>
                                <input type="file" name="picture" class="form-control" id="picture">
                                <span class="text-danger">{{ $errors->first('picture') }}</span>
                            </div>
                            <img id="show_image" src="" alt="" width="200" height="100"
                                class="img-responsive img-thumbnail">
                        </div>

                    </div>
                    <div class="col-xs-12 form-group col-md-12 col-lg-12">
                        <div class="btn-group">
                            <input type="submit" class="btn btn-info " value="Lưu">
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-danger" href="{{ route('product.index') }}">Quay lại</a>
                        </div>
                    </div>


                    <hr />

                </div>
            </form>
            <input type="hidden" value="{{ route('product.index') }}" id="redirect-route">
        </div>
    </div>
@endsection()
@section('extra_js')
    <script src="{{ asset('admin-assets/js/jquery.mask.min.js') }}"></script>
    <script type="text/javascript">
        // show items
        function showMe() {
            jQuery(".available0").toggle();
        }

        function showDiscount() {
            jQuery(".div-discount").toggle();
        }

        // <!-- add site map of the page -->
        jQuery(document).one('load', function(e) {
            jQuery("#site_map").append(
                "<i class='ace-icon fa '></i><a href='{{ route('product.create') }}' class='click_me'>Create Product</a>"
                );
            // e.isImmediatePropagationStopped()
        });
    </script>

    <!-- load cover image -->
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#show_image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#cover").change(function() {
            readURL(this);
        });
    </script>

    @if (env('APP_AJAX'))
        <script type="text/javascript">
            $(document).ready(function() {

                $('#unitPrice, #purchasePrice').mask("#.##0", {
                    reverse: true
                });

                var isTrue = false;

                $("#product_form").validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        product_code: "required",
                        product_name: "required",
                        unit: "required",
                        unitPrice: {
                            required: true
                        },
                        purchasePrice: {
                            required: true
                        },
                        //minInStock: "required",
                        //maxInStock: "required",
                        groupId: "required",
                        //categories: "required",
                    },
                    messages: {
                        product_name: "Không được để trống",
                        unit: "Không được để trống",
                        unitPrice: "Không được để trống",
                        purchasePrice: "Không được để trống",
                        minInStock: "Không được để trống",
                        maxInStock: "Không được để trống",
                        groupId: "Không được để trống",
                    },


                    highlight: function(e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },

                    success: function(e) {
                        $(e).closest('.form-group').removeClass('has-error'); //.addClass('has-info');
                        $(e).remove();
                    },

                    errorPlacement: function(error, element) {
                        if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                            var controls = element.closest('div[class*="col-"]');
                            if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                            else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        } else if (element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        } else if (element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        } else error.insertAfter(element.parent());
                    },

                    submitHandler: function(form) {
                        isTrue = true;
                    },
                    invalidHandler: function(form) {}
                });

                $('#modal-wizard-container').ace_wizard();
                $('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');

                $(document).one('ajaxloadstart.page', function(e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });

                $("#product_form").submit(function(e) {
                    e.preventDefault();
                    if (!isTrue) return false;
                    let form = $(this);
                    let form_data = new FormData(this);
                    // check if the input is valid
                    //if (!form.valid()) return false;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('product.store') }}",
                        method: "post",
                        enctype: 'multipart/form-data',
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function() {
                            $(".preview").toggle();
                            $("#overlay").fadeIn(500);
                        },
                        success: function(data) {
                            $("#overlay").fadeOut(300);
                            if (data.success === true) {
                                //show loading image ,reset forms ,clear gallery
                                $(".preview").hide();
                                $("#product_form")[0].reset();
                                $(".gallery").empty();
                                Swal.fire(
                                    'Thêm sản phẩm mới thành công!!!',
                                    '',
                                    'success'
                                )

                                window.location.replace($('#redirect-route').val());
                            }
                        },
                        error: function(request, status, error) {
                            $("#overlay").fadeOut(300);
                            json = $.parseJSON(request.responseText);
                            if (json.success === false) {
                                alert(json.message);
                                $(".preview").hide();
                                return;
                            }
                            $(".preview").hide();
                            $("#error_result").empty();
                            $.each(json.errors, function(key, value) {
                                $('.alert-danger').show().append('<p>' + value + '</p>');
                            });
                        }
                    });
                });
            });
        </script>
    @endif
    <!-- show selected images -->



@stop
