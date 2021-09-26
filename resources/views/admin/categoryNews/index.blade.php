@extends('layout.admin.index' )
@section('title')
    @lang('ext.list') @lang('models/customers.plural')
@stop
@section('extra_css')
    <style>
        #formAddNhom * {
            margin: 0px;
        }

        #formAddNhom {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            overflow-y: auto;
            box-shadow: 0px 6px 30px rgba(0, 0, 0, 0.4);
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;
            z-index: 10;
            background-color: #ffffff;
            width: 400px;
            border-radius: 10px;
        }

        #closeBtnNhom {
            float: right;
            margin: 10px;
        }

        #closeBtnNhom:hover {
            cursor: pointer;
        }

        .TieuDeForm {
            background: none repeat scroll 0 0 #2d91c3;
            padding: 10px;
            color: #ffffff;
        }

        #formAddNhom.open {
            visibility: visible;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .inputForm {
            padding: 0px 10px;

        }
        
        .btnGroupForm {
            padding: 10px;
            background: #d3d1d1;

        }

    </style>
@stop
@section('content')
    <div id="formAddNhom">
        <div class="TieuDeForm">
            <span id="closeBtnNhom">&#9932;</span>
            <h1>Thêm danh mục</h1>
        </div>
        <form method="post" action="{{ route('CategoryNew.store') }}" id="formNhom">
            @csrf
            <div class="inputForm">
                <h3>Tên danh mục</h3>
                <input type="text" placeholder="Nhập tên danh mục" class="form-control" style="margin: 10px 0px;" name='nameCategory'>
            </div>
            <div class="inputForm">
                <h3>Miêu tả</h3>
                <input type="text" placeholder="Nhập miêu tả" class="form-control" name='descriptionCategory'>
                <div style="float: right;margin:10px 0px"><input type="checkbox" name='statusCategory' > Kích hoạt</div>
            </div>
            <div style="clear: both;"></div>

            <div class="btnGroupForm">
                <input type="submit" value="Thêm" class="btn btn-sm btn-primary">
            </div>
        </form>
    </div>

    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách danh mục</h3>
        </div>
        <div class="box-body">
            <div class="pull-rightt">
                <button class="btn btn-sm btn-primary" id="addNhom"><span>Thêm danh mục</span></button>

            </div>

            <hr />

            <form method="get" action="{{ route('CategoryNew.index') }}" id="report-search">
                <div class="row">
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right text-bold" for="orderId"><b>Tên danh mục</b></label>
                        <div class="clearfix">
                            <input type="text" placeholder="Tên danh mục" class="form-control nav-search-input"
                                autocomplete="off" name="search" value="{{ $search }}" />
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
            <hr />
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xs-12">
                    <div class="table-responsive">

                        <table id="simple-table" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="center">Tên danh mục</th>
                                    <th>Miêu tả</th>
                                    <th>Trạng thái</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_body" class="table_data">
                                @include('admin.categoryNews._data')
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->appends(request()->except('page'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection()
@section('extra_js')
    <script>
        $('#addNhom').click(function() {
            $('#formAddNhom').addClass('open');
            // console.log("ssasdad");
        });
        $('#closeBtnNhom').click(function() {
            $('#formAddNhom').removeClass('open');
            $('#formAddNhom input[type=submit]').val('Thêm');
            $('#formAddNhom input[name=nameCategory]')[0].value ="";
            $('#formAddNhom input[name=descriptionCategory]')[0].value ="";
            $('#formAddNhom input[name=statusCategory]')[0].checked  =true;

            $('#formAddNhom input[name=_idCategory]')[0].remove();


        });
    </script>
    <script>
        $(document).ready(function() {
            deleteAjax("{{ route('CategoryNew.destroy','') }}", "delete_me", "CategoryNew");
            // $(".restore_me").click(function(e) {
            //     e.preventDefault();
            //     var obj = $(this); // first store $(this) in obj
            //     var id = $(this).data("id");
            //     console.log("ss");
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //         }
            //     });
            //     $.ajax({
            //         url: id,
            //         method: "GET",
            //         dataType: "Json",
            //         // data: {"id": id},
            //         success: function($results) {
            //             if ($results.success === true) {
            //                 alert($results.message);
            //                 $(obj).closest("tr").remove(); //delete row
            //             }
            //         },
            //         error: function(xhr) {
            //             alert(xhr.responseText.message);
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });
        });
    </script>

    <script>
        $('#simple-table a[title=Edit]').click(function(e) {
            e.preventDefault();

            if (!$('#formAddNhom').hasClass('open')) {
                $('#formAddNhom').addClass('open');

                $('#formAddNhom input[type=submit]').val('Lưu');
                // $('#formAddNhom input[name=_idNhom]')[0].remove();
                $("#formAddNhom form").append("<input type='hidden' name='_idCategory' value=" + $(this).data('id') +
                    ">");
                let id = $(this).data('id');
                // console.log(id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('CategoryNew.show') }}",
                    data:{
                        id:id
                    },
                    method: "get",
                    beforeSend: function() {
                        $(".preview").toggle();
                        $("#overlay").fadeIn(500);
                    },
                    success: function(data) {
                        console.log(JSON.parse(data).data.name);
                        $('#formAddNhom input[name=nameCategory]').val(JSON.parse(data).data.name);
                        $('#formAddNhom input[name=descriptionCategory]').val(JSON.parse(data).data.description);
                        $('#formAddNhom input[name=statusCategory]')[0].checked  =JSON.parse(data).data.enable;

                        $("#overlay").fadeOut(300);


                    },
                    error: function(xhr) {
                        $("#overlay").fadeOut(300);
                        console.log(xhr.responseText)
                    }
                });


            }
        });
        $('#formAddNhom input[type=submit]').click(function(e) {
            e.preventDefault();
            if ($('input[name=_idCategory]').length != 0) {
                var form_data = new FormData($("#formAddNhom form")[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('CategoryNew.update') }}",
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
                                'Sửa danh mục thành công!!!',
                                '',
                                'success'
                            )
                            window.location.replace('{{route('CategoryNew.index')}}');
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
            }else{
                var form_data = new FormData($("#formAddNhom form")[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('CategoryNew.store') }}",
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
                                'Thêm danh mục thành công!!!',
                                '',
                                'success'
                            )
                            window.location.replace('{{route('CategoryNew.index')}}');
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
            }

        });
    </script>
@stop
