@extends('layout.admin.index' )
@section('title')
    Sửa tin tức
@stop
@section('extra_css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

@stop

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Sửa tin tức</h3>
        </div>

        <div class="box-body">
            <form method="post" action="{{ route('news.store') }}" id="news_form" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-xs-12 col-md-12 col-lg-12">
                        <div class="form-group col-md-8 col-lg-8 col-xs-8">
                            <div class="form-group col-md-12 col-lg-12 col-xs-12">
                                <label class="control-label no-padding-right" for="customer_name"> Tiêu đề </label>
                                <div class="clearfix">
                                    <input placeholder="Tiêu đề" name="title" value="{{ old('title', $news->title) }}"
                                        id="title" class="form-control" type="text">
                                    <input name="id" value="{{ old('title', $news->id) }}" type="hidden">
                                </div>

                            </div>
                            <div class="form-group col-md-12 col-lg-12 col-xs-12">
                                <label class="control-label no-padding-right" for="email"> Nội dung ngắn </label>
                                <div class="clearfix">

                                    <textarea id="shortContent" rows="6" class="form-control"
                                        name="shortContent">{{ old('shortContent', $news->short) }}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 form-group col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right" for="fullContent">Nội dung đầy đủ</label>
                                <div class="clearfix">
                                    <textarea id="fullContent" rows="6" class="form-control"
                                        name="fullContent">{{ old('fullContent', $news->full) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4 col-lg-4 col-xs-4">
                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right" for="tel"> Ảnh miêu tả </label>
                                <div class="clearfix">
                                    <input id="urlImage" class="btn btn-primary col-xs-12 col-md-12 col-lg-12"
                                        placeholder="url" name="urlImage" class="form-control" type="file">

                                    <img id="showImg" src="{{ $news->picture }}" alt="Hình Ảnh" width="100%">
                                    <input name="urlImageNews" type="hidden" value="{{ $news->picture }}">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right" for="tel"> Danh mục </label>
                                <div class="clearfix">
                                    <select name="typeNews" class="form-control" id="groupId">
                                        <option value="">-- Lựa chọn --</option>

                                        @foreach ($categoryNews as $item)
                                            <option value="{{ $item->id }}" @if ($news->category_id == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach



                                    </select>
                                </div>

                            </div>
                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right" for="tel"> Chuyên mục </label>
                                <div class="clearfix">
                                    <input type="checkbox" name="is_hot" id="is_hot"
                                        {{ $news->is_hot == 1 ? 'checked' : '' }}>Tin mới
                                   
                                    <input type="checkbox" name="is_feature" id="is_feature"
                                        {{ $news->is_feature == 1 ? 'checked' : '' }}>Tin nổi bật

                                    <input type="checkbox" name="is_home" id="is_home"
                                        {{ $news->is_home == 1 ? 'checked' : '' }}>Tin trang chủ
                                </div>

                            </div>
                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <hr>
                                <div class="clearfix">
                                    <input type="checkbox" name="isPushNotification" id="">Gửi thông báo
                                </div>
                            </div>
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
            <input type="hidden" value="{{ route('news.index') }}" id="redirect-route">
        </div>
    </div>
@endsection()
@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            //summernote
            $('#fullContent').summernote({
                height: 200,

                callbacks: {
                    onImageUpload: function(image) {
                        // upload image to server and create imgNode...
                        // console.log(image[0]);

                        uploadImage(image[0]);
                    }
                }

            });

            function uploadImage(image) {
                dataF = new FormData();
                dataF.append("image", image, image.name);
                console.log(dataF);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('news.uploadImage') }}",
                    method: "post",
                    enctype: 'multipart/form-data',
                    data: dataF,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(url) {
                        console.log(url);
                        var image = $('<img>').attr('src', url.result);
                        $('#fullContent').summernote("insertNode", image[0]);
                    },
                    error: function(data) {
                        console.log(data.responseText);
                    }
                });
            }

            //update ajax

            var isTrue = false;

            $("#news_form").validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                ignore: "",
                rules: {
                    title: "required",
                    fullContent: "required",
                    shortContent: "required",

                },
                messages: {
                    title: "Không được để trống",
                    shortContent: "Không được để trống",
                    fullContent: "Không được để trống",

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
                    url: "{{ route('news.update') }}",
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

                        $("#overlay").fadeOut(300);
                        if (data.success === true) {
                            //show loading image ,reset forms ,clear gallery
                            $(".preview").hide();
                            $("#news_form")[0].reset();
                            $(".gallery").empty();
                            Swal.fire(
                                'Sửa tin tức mới thành công!!!',
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
