@extends('layout.admin.index' )
@section('title')
    @lang('ext.list') @lang('models/customers.plural')
@stop
@section('extra_css')

@stop

@section('content')


    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách tin tức</h3>
        </div>
        <div class="box-body">
            <div class="pull-rightt">
                <a href="{{ route('news.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i>
                    Thêm mới</a>


            </div>

            <hr />

            <form method="get" action="{{ route('news.index') }}" id="report-search">
                <div class="row">
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right text-bold" for="orderId"><b>Tiêu Đề Bài
                                Viết</b></label>
                        <div class="clearfix">
                            <input type="text" placeholder="Tiêu Đề Bài Viết" class="form-control nav-search-input"
                                autocomplete="off" name="search" value="{{ $search }}" />
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="groupId"><b>Danh Mục</b></label>
                        <div class="clearfix">
                            <select name="categoryId" class="form-control" id="groupId">
                                <option value="">-- Tất cả --</option>

                                @foreach ($categoryNews as $item)
                                    <option value="{{ $item->id }}" @if ($categoryId == $item->id) selected @endif>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="groupId"><b>Chuyên Mục</b></label>
                        <div class="clearfix">
                            <select name="typeNews" class="form-control" id="groupId">
                                <option value="">-- Tất cả --</option>
                                <option value="3" @if ($typeNews == '3') selected @endif>Tin nổi bật
                                </option>
                                <option value="2" @if ($typeNews == '2') selected @endif>Tin mới
                                </option>
                                <option value="1" @if ($typeNews == '1') selected @endif>Tin trang chủ
                                </option>

                            </select>
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
                                    <th class="center">Tiêu đề</th>
                                    <th>Nội dung ngắn</th>
                                    {{-- <th>Nội dung dài</th> --}}
                                    <th>Danh mục</th>
                                    <th>Ảnh</th>
                                    {{-- <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th> --}}

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_body" class="table_data">
                                {{-- @php
                                    var_dump($data);
                                @endphp --}}
                                @include('admin.news._data')
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
        $(document).ready(function() {
            deleteAjax("{{ route('news.destroy', '') }}", "delete_me", "TinTuc");

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".pushNewsNotification").click(function(e) {
                e.preventDefault();
                $makhachhang = $(this).attr("data-id");
                console.log($makhachhang);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('news.push') }}",
                    method: "post",
                    data: {
                        'id': $makhachhang
                    },

                    beforeSend: function() {
                        $(".preview").toggle();
                        $("#overlay").fadeIn(500);
                    },
                    success: function(data) {
                        console.log(data);
                        data = JSON.parse(data);
                        $("#overlay").fadeOut(300);
                        if (data.status === true) {
                            console.log("ssss");
                            Swal.fire(
                                data.messenge + '!!!',
                                '',
                                'success'
                            )
                            // window.location.replace($('#updateCustomerAPI').val());
                        } else {
                            Swal.fire(
                                'warning!!!',
                                '<h3>' + data.messenge + '</h3>',
                                'warning'
                            );
                        }

                    },
                    error: function(xhr) {
                        $("#overlay").fadeOut(300);
                        Swal.fire(
                            'loi ket noi!!!',
                            '<h3>error</h3>',
                            'error'
                        );
                        console.log(xhr.responseText)
                    }

                })

            });
        });
    </script>
@stop
