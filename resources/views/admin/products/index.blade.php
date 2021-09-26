@extends('layout.admin.index' )
@section('title')
    @lang('ext.list') @lang('models/products.plural')
@stop
@section('extra_css')
@stop
@section('content')

    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Danh sách sản phẩm</h3>
        </div>
        <div class="box-body">
            <div class="pull-rightt">
                <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Thêm mới</a>
            </div>
            <hr/>
           <form method="get" action="{{ route('product.index') }}" id="report-search" >
               <div class="row">
                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right text-bold" for="orderId"><b>Mã sản phẩm</b></label>
                       <div class="clearfix">
                           <input type="text" placeholder="Mã sản phẩm" class="form-control nav-search-input"
                                  autocomplete="off" name="productCode" value="{{ $productCode }}"/>
                       </div>
                   </div>
                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right text-bold" for="orderId"><b>Tên sản phẩm</b></label>
                       <div class="clearfix">
                           <input type="text" placeholder="Tên sản phẩm" class="form-control nav-search-input"
                                  autocomplete="off" name="productName" value="{{ $productName }}"/>
                       </div>
                   </div>
                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right text-bold" for="orderId"><b>Mô tả - Ghi chú</b></label>
                       <div class="clearfix">
                           <input type="text" placeholder="Mô tả - Ghi chú" class="form-control nav-search-input"
                                  autocomplete="off" name="description" value="{{ $description }}"/>
                       </div>
                   </div>
                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right" for="groupId"><b>Nhóm hàng</b></label>
                       <div class="clearfix">
                           <select name="groupId" class="form-control" id="groupId">
                               <option value="">-- Tất cả --</option>
                               @foreach($productGroups as $g)
                                   <option value="{{ $g->Id }}" @if($g->Id == $groupId) selected @endif>{{ $g->GroupName}}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>

                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right" for="instock"><b>Tồn kho</b></label>
                       <div class="clearfix">
                           <select name="instock" class="form-control" id="instock">
                               <option value="-1">-- Tất cả --</option>
                               <option value="0" @if($instock == '0') selected @endif>Còn hàng trong kho</option>
                               <option value="1" @if($instock == '1') selected @endif>Hết hàng</option>
                           </select>
                       </div>
                   </div>

                   <div class="col-md-6 col-lg-2 col-sm-12 col-xs-12 form-group">
                       <label class="control-label no-padding-right" for="status"><b>Trạng thái</b></label>
                       <div class="clearfix">
                           <select name="status" class="form-control" id="status">
                               <option value="-1">-- Tất cả --</option>
                               <option value="1" @if($status == '1') selected @endif>Đang kinh doanh</option>
                               <option value="0" @if($status == '0') selected @endif>Ngừng kinh doanh</option>
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

           <div class="row">
        <div class="col-sm-12 col-lg-12 col-xs-12">
            <div class="table-responsive">
                <table id="simple-table" class="table table-bordered table-hover table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã hàng</th>
{{--                        <th class="center">Mã 2</th>--}}
                        <th>Tên hàng</th>
{{--                        <th>Tên viết tắt</th>--}}
                        <th>Ngành hàng</th>
                        <th>ĐVT</th>
                        <th>Giá nhập</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th>Ghi chú</th>
                        <th>Tùy chọn</th>
                        <th width="10%"></th>
                    </tr>
                    </thead>
                    <tbody id="table_body" class="table_data">
                    @include('admin.products._data')
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            {{ $data->appends(request()->except('page'))->links() }}
        </div>
    </div>
        </div>
    </div>
@endsection()
@section('extra_js')
   
      <script>
          $(document).ready(function () {
        	  @if(isDelete(env('PRODUCT_ROLE_CODE')))
              	deleteAjax("/admin/product/", "delete_me", "product");
              @endif
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
