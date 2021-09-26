@extends('layout.admin.index' )
@section('title')
    Thêm khách hàng
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Thêm khách hàng</h3>
        </div>

        <div class="box-body">
            <form method="post" action="{{ route('customer.store') }}" id="customer_form" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-xs-12 col-md-12 col-lg-12">
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label class="control-label no-padding-right" for="customer_name"> Tên khách hàng </label>
                            <div class="clearfix">
                                <input placeholder="Tên khách hàng" name="customer_name"
                                    value="{{ old('customer_name') }}" id="customer_name" class="form-control"
                                    type="text">
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-3 col-xs-12">
                            <label class="control-label no-padding-right" for="email"> Email </label>
                            <div class="clearfix">
                                <input placeholder="Email" name="email" value="{{ old('email') }}" id="email"
                                    class="form-control" type="email">
                            </div>
                        </div>

                        <div class="form-group col-xs-6 col-md-6 col-lg-3">
                            <label class=" control-label no-padding-right" for="tel"> Số điện thoại </label>
                            <div class="clearfix">
                                <input placeholder="Số điện thoại" name="tel" value="{{ old('tel') }}" id="tel"
                                    class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group col-xs-6 col-md-6 col-lg-3">
                            <label class=" control-label no-padding-right" for="address"> Địa chỉ </label>
                            <div class="clearfix">
                                <input placeholder="Địa chỉ" name="address" value="{{ old('address') }}" id="address"
                                    class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group col-xs-6 col-md-6 col-lg-3">
                            <label class=" control-label no-padding-right" for="birthday">Ngày sinh</label>
                            <div class="clearfix">
                                <input placeholder="Ngày sinh" type="date" value="{{ old('birthday') }}" name="birthday"
                                    class="form-control" id="birthday">
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



                    <hr />
                    <div class="form-group col-sm-12">
                        <div class="btn-group">
                            <input type="submit" class="btn btn-primary " value="Lưu">
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-danger" onclick="history.back()">Quay lại</a>
                        </div>
                    </div>

                </div>
            </form>
            <input type="hidden" value="{{ route('customer.index') }}" id="redirect-route">
        </div>
    </div>
@endsection()
@section('extra_js')
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
                "<i class='ace-icon fa '></i><a href='{{ route('customer.create') }}' class='click_me'>Create Customer</a>"
            );
            // e.isImmediatePropagationStopped()
        });
    </script>

    <!-- load avatar image -->


    @if (env('APP_AJAX'))
        <script type="text/javascript">
            $(document).ready(function() {
                var isTrue = false;
                $("#customer_form").validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        customer_name: "required",

                    },
                    messages: {
                        customer_name: "Không được để trống",
                        tel: "Không được để trống",
                        email: "Không được để trống",
                        description: "Không được để trống",
                        birthday: "Không được để trống",
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
                            let controls = element.closest('div[class*="col-"]');
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

                $("#customer_form").submit(function(e) {
                    e.preventDefault();
                    if (!isTrue) return false;
                    var form = $(this);
                    var form_data = new FormData(this);
                    console.log(form_data.get("_token"));
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('customer.store') }}",
                        method: "POST",
                        // enctype: 'multipart/form-data',
                        data: form_data,
                        contentType: false,
                        // cache: false,
                        processData: false,
                        beforeSend: function() {
                            $(".preview").toggle();
                            $("#overlay").fadeIn(500);
                        },
                        success: function(data) {
                            console.log(data);
                            $("#overlay").fadeOut(300);
                            if (data.success === true) {
                                Swal.fire(
                                    'Thêm khách hàng thành công!!!',
                                    '',
                                    'success'
                                )                                             
                                window.location.replace($('#redirect-route').val());
                            } else {
                                Swal.fire(
                                    'Đã có lỗi xảy ra!!!',
                                    '<h3>' + data.message + '</h3>',
                                    'error'
                                );
                            }

                        },
                        error: function(xhr) {
                            $("#overlay").fadeOut(300);
                            console.log(xhr.responseText)
                        }
                    });
                });
            });
        </script>
    @endif

@stop
