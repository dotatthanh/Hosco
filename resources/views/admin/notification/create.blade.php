@extends('layout.admin.index' )
@section('title')
    Tạo thông báo
@stop
@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <style>
        #groupSend {
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;

        }

        #groupSend.open {
            visibility: visible;
            opacity: 1;
        }

    </style>
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Tạo thông báo</h3>
        </div>

        <div class="box-body">
            <form method="post" action="{{ route('notification.store') }}" id="news_form" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-xs-12 col-md-12 col-lg-12">
                        <div class="form-group col-md-6 col-lg-6 col-xs-6">
                            <div class="form-group col-md-12 col-lg-12 col-xs-12">
                                <label class="control-label no-padding-right" for="customer_name"> Tiêu đề </label>
                                <div class="clearfix">
                                    <input placeholder="Tiêu đề" name="title" value="{{ old('title') }}" id="title"
                                        class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-lg-12 col-xs-12">
                                <label class="control-label no-padding-right" for="message"> Nội dung tin </label>
                                <div class="clearfix">

                                    <textarea id="message" rows="6" class="form-control"
                                        name="message">{{ old('shortContent') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-xs-6">
                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right">Hình thức gửi</label>
                                <div class="clearfix">
                                    <div class="form-group col-xs-4 col-md-4 col-lg-4">

                                        <input type="radio" id="allUser" name="typePushNotification" value="0" checked>
                                        <label for="allUser">Gửi cho tất cả</label>
                                    </div>
                                    <div class="form-group col-xs-4 col-md-4 col-lg-4">

                                        <input type="radio" id="GroupUser" name="typePushNotification" value="1">
                                        <label for="GroupUser">Gửi theo nhóm</label><br>
                                    </div>
                                </div>
                            </div>
                            <div id="groupSend">
                                <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                    <label class=" control-label no-padding-right">Danh sách nhóm</label>
                                    <div class="clearfix">
                                        <select name="groupId" class="form-control" id="groupId">
                                            <option value="">-- Lựa chọn --</option>
                                            @foreach ($customerGroups as $loc)
                                                <option value="{{ $loc->Id }}">{{ $loc->GroupName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                    <label class=" control-label no-padding-right">Danh sách nhóm gửi</label>
                                    <div class="clearfix">
                                        <div id="chooseGroup"></div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>





                    <hr />
                    <div class="form-group col-sm-12">
                        <div class="btn-group">
                            <input type="submit" class="btn btn-primary " value="Gửi">
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-danger" onclick="history.back()">Quay lại</a>
                        </div>
                    </div>

                </div>
            </form>
            <input type="hidden" value="{{ route('notification.index') }}" id="redirect-route">
        </div>
    </div>
@endsection()
@section('extra_js')
    <script>
        function validateSelectBox(obj) {
            console.log(obj);

            var options = obj.childNodes;

            var html = '';

            var kiemtra = -1;
            for (var i = 0; i < options.length; i++) {
                if (options[i].selected) {
                    kiemtra = options[i].value;
                    html += "<div style='display:inline-block;'>";
                    html += "<p style='display:inline-block;box-shadow: 0px 0px 10px 2px inset gray ;'>" + options[i]
                        .innerHTML + "</p>";
                    html +=
                        "<button type='button' style='width: 16.2px;padding: 0;border: 1px solid #c56565;' onclick='remove(this)'><img src='../../images/remove-image.png' alt='' width='100%' height = '100%'></button>"
                    html += '<input type="hidden" name="groupSend-' + options[i].value + '" value="' + options[i].value +
                        '" >';
                    html += "</div>";
                }
            }
            if (kiemtra != "") {
                if (!search(kiemtra))
                    document.getElementById('chooseGroup').innerHTML += html;
                else
                    alert("Lựa chọn đã được chọn lựa");
            }

        }

        function remove(obj) {

            obj.parentNode.remove();
        }

        function search(a) {
            var div = document.getElementById('chooseGroup').children;

            for (var i = 0; i < div.length; i++) {
                if (a == div[i].getElementsByTagName('input')[0].value)
                    return true;
            }
            return false;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type=radio][name=typePushNotification]').change(function() {
                if (this.value == '0') {
                    $('#groupSend').removeClass('open');
                    $('#chooseGroup').empty();
                } else if (this.value == '1') {
                    $('#groupSend').addClass('open');
                }
            });


            $("#groupId").change(function() {
                validateSelectBox($(this)[0]);
            });


            //pushnotication ajax


            var isTrue = false;

            $("#news_form").validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                ignore: "",
                rules: {
                    title: "required",
                    message: "required",

                },
                messages: {
                    title: "Không được để trống",
                    message: "Không được để trống",

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

            $("#news_form").submit(function(e) {
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
                    url: "{{ route('notification.store') }}",
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
                        console.log(data);
                        data = JSON.parse(data);
                        $("#overlay").fadeOut(300);
                        if (data.status === true) {
                            //show loading image ,reset forms ,clear gallery
                            $(".preview").hide();
                            $("#news_form")[0].reset();
                            $(".gallery").empty();
                            Swal.fire(
                                'Gửi thông báo thành công!!!',
                                '',
                                'success'
                            )

                            window.location.replace($('#redirect-route').val());
                        }
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
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


@stop
