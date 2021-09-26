@extends('layout.admin.index' )
@section('title')
   Cập nhật thông tin khách hàng
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-user orange" aria-hidden="true"></i>
            <h2 class="box-title">Cập nhật thông tin khách hàng</h2>
        </div>
        <div class="box-body">

           <form method="post" action="{{ route('customer.update', $customer->Id) }}" id="customer_form"
                 enctype="multipart/form-data">
                 @csrf
               @if( ! env("APP_AJAX") )
                   @method("PUT")
               @endif
              <div class="row">
                 <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group col-md-6 col-lg-3 col-xs-6">
                       <label class="control-label no-padding-right" for="customer_code"> Mã khách hàng </label>
                       <div class="clearfix">
                          <input placeholder="Mã khách hàng" name="customer_code" value="{{ old('customer_code', $customer->CustomerCode) }}"
                                 id="customer_code" class="form-control" type="text">
                       </div>
                    </div>
                    <div class="form-group col-md-6 col-lg-3 col-xs-12">
                       <label class="control-label no-padding-right" for="customer_name"> Tên khách hàng </label>
                       <div class="clearfix">
                          <input placeholder="Tên khách hàng" name="customer_name" value="{{ old('customer_name', $customer->Name) }}"
                                 id="customer_name" class="form-control" type="text">
                       </div>
                    </div>

                    <div class="form-group col-md-6 col-lg-3 col-xs-12">
                       <label class=" control-label no-padding-right" for="groupId">Nhóm khách hàng</label>
                       <div class="clearfix">
                          <select name="groupId" id="groupId" class="form-control">
                             <option value="" disabled selected>Chọn nhóm hàng</option>
                             @foreach($customerGroups as $group)
                                <option {{ old('groupId', $customer->GroupId) == $group->Id ? 'selected' : '' }} value="{{ $group->Id }}">{{ $group->GroupName }}</option>
                             @endforeach
                          </select>
                       </div>
                    </div>

                    <div class="form-group col-md-6 col-lg-3 col-xs-12">
                       <label class="control-label no-padding-right" for="email"> Email </label>
                       <div class="clearfix">
                          <input placeholder="Email" name="email" value="{{ old('email', $customer->Email) }}"
                                 id="email" class="form-control" type="email">
                       </div>
                    </div>

                 </div>

                 <div class="form-group col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group col-xs-6 col-md-6 col-lg-3">
                       <label class=" control-label no-padding-right" for="tel"> Số điện thoại </label>
                       <div class="clearfix">
                          <input placeholder="Số điện thoại" name="tel" value="{{ old('tel', $customer->Tel) }}" id="tel"
                                 class="form-control" type="text">
                       </div>
                    </div>
                    <div class="form-group col-xs-6 col-md-6 col-lg-3">
                       <label class=" control-label no-padding-right" for="address"> Địa chỉ </label>
                       <div class="clearfix">
                          <input placeholder="Địa chỉ" name="address" value="{{ old('address', $customer->Address) }}" id="address"
                                 class="form-control" type="text">
                       </div>
                    </div>

                    <div class="form-group col-xs-6 col-md-6 col-lg-3">
                       <label class=" control-label no-padding-right" for="birthday">Ngày sinh</label>
                       <div class="clearfix">
                          <input placeholder="Ngày sinh" type="date" value="{{ old("birthday", date_format(date_create($customer->BirthDay),"Y-m-d")) }}" name="birthday"
                                 class="form-control" id="birthday" >
                       </div>
                    </div>

                    <div class="form-group col-xs-6 col-md-6 col-lg-3">
                       <label class=" control-label no-padding-right" for="taxCode">Mã số thuế</label>
                       <div class="clearfix">
                          <input placeholder="Mã số thuế" type="text" value="{{ old('taxCode', $customer->TaxCode) }}" name="taxCode"
                                 class="form-control" id="taxCode">
                       </div>
                    </div>
                 </div>
                 <div class="col-xs-12 form-group col-md-12 col-lg-12">
                    <label class=" control-label no-padding-right" for="description">Ghi chú</label>
                    <div class="clearfix">
                          <textarea id="description" rows="6" class="form-control"
                                    name="description">{{ old('description', $customer->Description) }}</textarea>
                    </div>
                 </div>

                 <div class="form-group col-xs-6 col-sm-6 col-lg-6 col-md-6">
                    <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                       <label class="bolder bigger-110 " for="avatar">Ảnh đại diện</label>

                       <input type="file" name="avatar" class="form-control" id="avatar">

                       <span class="text-danger">{{ $errors->first('avatar') }}</span>
                    </div>
                    <img id="show_image" src="{{ $customer->Avatar }}" alt="" width="200" height="100" class="img-responsive img-thumbnail">
                 </div>
                 <hr/>
                 <div class="form-group col-sm-12">
                   <div class="btn-group">
                      <input type="submit" class="btn btn-info " value="Lưu">
                   </div>
                   <div class="btn-group">
                      <a class="btn btn-danger" href="{{ route('customer.index') }}">Quay lại</a>
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
       jQuery(document).one('load', function (e) {
           jQuery("#site_map").append("<i class='ace-icon fa '></i><a href='{{ route('customer.create') }}' class='click_me'>Create Customer</a>");
           // e.isImmediatePropagationStopped()
       });
   </script>

   <!-- load avatar image -->
   <script type="text/javascript">
       function readURL(input) {
           if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                   $('#show_image').attr('src', e.target.result);
               }

               reader.readAsDataURL(input.files[0]);
           }
       }

       $("#avatar").change(function () {
           readURL(this);
       });
   </script>

   <!--FRONT VALIDATION -->
   <script type="text/javascript">
       jQuery(document).ready(function () {
           jQuery(function ($) {
               $("#customer_form").validate({
                   errorElement: 'div',
                   errorClass: 'help-block',
                   focusInvalid: false,
                   ignore: "",
                   rules: {
                       customer_code: "required",
                       customer_name: "required",
                       //taxCode: "required",
                       groupId: {required: true},
                       //tel: {required: true},
                       //email: {required: true},
                       //birthday: "required",
                       //colors: "required",
                       //brand_id: "required",
                       //categories: "required",
                   },
                   messages: {
                       customer_code: "Không được để trống",
                       customer_name: "Không được để trống",
                       taxCode: "Không được để trống",
                       tel: "Không được để trống",
                       email: "Không được để trống",
                       birthday: "Không được để trống",
                   },
                   highlight: function (e) {
                       $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                   },

                   success: function (e) {
                       $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                       $(e).remove();
                   },

                   errorPlacement: function (error, element) {
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

                   submitHandler: function (form) {

                   },
                   invalidHandler: function (form) {
                   }
               });

               $('#modal-wizard-container').ace_wizard();
               $('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');

               $(document).one('ajaxloadstart.page', function (e) {
                   //in ajax mode, remove remaining elements before leaving page
                   $('[class*=select2]').remove();
               });
           })
       })
   </script>

   @if(env('APP_AJAX'))
      <script type="text/javascript">
          $(document).ready(function () {
              $("#customer_form").submit(function (e) {
                  e.preventDefault();
                  var form = $(this);
                  var form_data = new FormData(this);
                  // check if the input is valid
                  // if (!form.valid()) return false;
                  $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      }
                  });
                  $.ajax({
                      url: "{{ route('customer.updateCus', $customer->Id) }}",
                      method: "POST",
                      enctype: 'multipart/form-data',
                      data: form_data,
                      contentType: false,
                      cache: false,
                      processData: false,
                      beforeSend: function () {
                          $(".preview").toggle();
                          // show loading
                          $("#overlay").fadeIn(500);
                      },
                      success: function (data) {
                          $("#overlay").fadeOut(300);
                          if (data.success === true) {
                              //show loading image ,reset forms ,clear gallery
                              $(".preview").hide();
                              //$("#customer_form")[0].reset();
                              $(".gallery").empty();
                              Swal.fire(
                                  'Cập nhật khách hàng thành công!!!',
                                  '',
                                  'success'
                              )
                              window.location.replace($('#redirect-route').val());
                          } else {
                              Swal.fire(
                                  'Đã có lỗi xảy ra!!!',
                                  data.message,
                                  'error'
                              )
                          }
                      },
                      error: function (request, status, error) {
                          $("#overlay").fadeOut(300);
                          json = $.parseJSON(request.responseText);
                          if (json.success === false) {
                              Swal.fire(
                                  'Đã có lỗi xảy ra!!!',
                                  json.message,
                                  'error'
                              )
                              $(".preview").hide();
                              return;
                          }
                          $(".preview").hide();
                          $("#error_result").empty();
                          $.each(json.errors, function (key, value) {
                              $('.alert-danger').show().append('<p>' + value + '</p>');
                          });
                          // $('html, body').animate(
                          //     {
                          //         scrollTop: $("#error_result").offset().top,
                          //     },
                          //     500,
                          // )
                          // $("#result").html('');
                      }
                  });
              });
          });
      </script>
   @endif
   <!-- show selected images -->



@stop
