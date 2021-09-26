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
            <h3 class="box-title">Danh sách khách hàng</h3>
        </div>
        <div class="box-body">
            <div class="pull-rightt">
                <a href="{{ route('customer.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Thêm mới</a>
            </div>
            <hr/>

            <form method="get" action="{{ route('customer.index') }}" id="report-search" >
                <div class="row">
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right text-bold" for="orderId"><b>Mã, tên, số điện thoại</b></label>
                        <div class="clearfix">
                             <input type="text" placeholder="Mã, tên, số điện thoại..." class="form-control nav-search-input"
                                    autocomplete="off" name="search" value="{{ $search }}"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right text-bold" for="email"><b>Email</b></label>
                        <div class="clearfix">
                            <input type="email" placeholder="Email" class="form-control nav-search-input"
                                   autocomplete="off" name="email" value="{{ $email }}"/>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="objectType"><b>Đối tượng</b></label>
                        <div class="clearfix">
                            <select name="objectType" class="form-control" id="objectType">
                                <option value="">-- Tất cả --</option>
                                <option value="0" @if($objectType == "0") selected @endif>Khách hàng</option>
                                <option value="1" @if($objectType == "1") selected @endif>NCC</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="groupId"><b>Nhóm</b></label>
                        <div class="clearfix">
                            <select name="groupId" class="form-control" id="groupId">
                                <option value="">-- Tất cả --</option>
                                @foreach($customerGroups as $loc)
                                    <option value="{{ $loc->Id }}" @if($loc->Id == $groupId) selected @endif>{{ $loc->GroupName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="employeeId">Nhân viên phụ trách</label>
                        <div class="clearfix">
                            <select name="employeeId" class="form-control" id="employeeId">
                                <option value="">-- Tất cả --</option>
                                @foreach($employees as $loc)
                                    <option value="{{ $loc->Id }}" @if($loc->Id == $employeeId) selected @endif>{{ $loc->EmployeeName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    --}}
                    <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                        <label class="control-label no-padding-right" for="status">Trạng thái</label>
                        <div class="clearfix">
                            <select name="status" class="form-control" id="status">
                                <option value="">-- Tất cả --</option>
                                <option value="0" @if($status == "0") selected @endif>Đang hoạt động</option>
                                <option value="1" @if($status == "1") selected @endif>Ngừng hoạt động</option>
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
            <hr/>
            <div class="row">
        <div class="col-sm-12 col-lg-12 col-xs-12">
            <div class="table-responsive">
                <table id="simple-table" class="table table-bordered table-hover table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã KH</th>
                        <th class="center">Tên KH</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                        <th>Mã số thuế</th>
                        <th>Nhân viên phụ trách</th>
                        <th>Đối tượng</th>
                        <th>Ghi chú</th>
                        <th>Nhóm</th>
                        <th>Đại lý</th>
                        <th>Tổng tiền</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="table_body" class="table_data">
                    @include('admin.customer._data')
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $data->appends(request()->except('page'))->links() }}
            @endif
        </div>
    </div>
        </div>
    </div>

@endsection()
@section('extra_js')
   
      <script>
          $(document).ready(function () {
              deleteAjax("/admin/customer/", "delete_me", "customer");
              $(".restore_me").click(function (e) {
                  e.preventDefault();
                  var obj = $(this); // first store $(this) in obj
                  var id = $(this).data("id");
                  $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      }
                  });
                  $.ajax({
                      url: id,
                      method: "GET",
                      dataType: "Json",
                      // data: {"id": id},
                      success: function ($results) {
                          if ($results.success === true){
                              alert($results.message);
                              $(obj).closest("tr").remove(); //delete row
                          }
                      },
                      error: function (xhr) {
                          alert(xhr.responseText.message);
                          console.log(xhr.responseText);
                      }
                  });
              });
          });
      </script>
   
   <!-- TO SORT PRODUCTS -->
   <script type="text/javascript">
       $(document).ready(function () {
           $("#sort_form").submit(function (e) {
               e.preventDefault();
               var form = $(this);
               var form_data = new FormData(this);
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                   }
               });
               $.ajax({
                   url: "{{ route('product.index.sort') }}",
                   method: "post",
                   data: form_data,
                   contentType: false,
                   cache: false,
                   processData: false,
                   beforeSend: function () {
                       $(".preview").show();
                   },

               })
                   .done(function (data) {
                       if (data.html == " ") {
                           // $('.ajax-load').attr('src', '');
                           $('#preview').hide();
                           $('.table_body').html("No more records found");
                           return;
                       }
                       $("#table_body").empty().append(data.html);
                       $('.preview').hide();
                   }).fail(function () {
                   alert('error');
               })
           });
       });
   </script>
@stop
