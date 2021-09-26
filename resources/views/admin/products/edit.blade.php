@extends('layout.admin.index' )
@section('title')
   Cập nhật sản phẩm
@stop
@php

$unitPrice=33;
if(isRead(env('VIEW_UNIT_PRICE_CODE1')))
{$disabledPurchasePrice='';
    $unitPrice=$product->UnitPrice;
}
else
{
 $disabledPurchasePrice='disabled';
    $unitPrice="**********";
}
@endphp
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-edit orange" aria-hidden="true"></i>
            <h3 class="box-title">Cập nhật sản phẩm</h3>
        </div>
        <div class="box-body">
            <form method="post" action="{{ route('product.update',$product->Id) }}" enctype="multipart/form-data"
            id="Uproduct_form">
         @csrf
         @if( ! env("APP_AJAX") )
            @method("PUT")
         @endif

          <div class="row">
              <div class="col-xs-12 col-md-9 col-lg-9">
                  <div class="form-group col-md-3 col-lg-3 col-xs-12">
                      <label class="control-label no-padding-right" for="product_code"> Mã sản phẩm </label>
                      <div class="clearfix">
                          <input placeholder="Mã sản phẩm" name="product_code" value="{{ old('product_code', $product->ProductCode) }}"
                                 id="product_code" class="form-control" type="text">
                      </div>
                  </div>
                  <div class="form-group col-md-3 col-lg-3 col-xs-12">
                      <label class="control-label no-padding-right" for="product_name"> Tên sản phẩm </label>
                      <div class="clearfix">
                          <input placeholder="Tên sản phẩm" name="product_name" value="{{ old('product_name', $product->Name) }}"
                                 id="product_name" class="form-control" type="text">
                      </div>
                  </div>
                  <div class="form-group col-md-3 col-lg-3 col-xs-12">
                      <label class=" control-label no-padding-right" for="groupId">Nhóm hàng</label>
                      <div class="clearfix">
                          <select name="groupId" id="groupId" class="form-control">
                              <option value="" disabled selected>Chọn nhóm hàng</option>
                              @foreach($productGroups as $brand)
                                  <option {{ old('groupId', $product->GroupId) == $brand->Id ? 'selected' : '' }} value="{{ $brand->Id }}">{{ $brand->GroupName }}</option>
                              @endforeach
                          </select>
                          <span class="lbl"></span>
                          <span class="text-danger">{{ $errors->first('groupId') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-3 col-lg-3">
                      <label class=" control-label no-padding-right" for="unit">Đơn vị tính</label>
                      <div class="clearfix">
                          <input placeholder="Đơn vị tính" type="text" value="{{ old("unit", $product->Unit) }}" name="unit"
                                 class="form-control" id="unit">
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-xs-12 form-group col-md-12 col-lg-6">
                          <label class=" control-label no-padding-right" for="noteImport">Ghi chú nhập</label>
                          <div class="clearfix">
                  <textarea id="noteImport" rows="6" class="form-control ckeditor"
                            name="noteImport">{{ old('noteImport', $product->s_NoteImport) }}</textarea>
                          </div>
                      </div>
                      <div class="col-xs-12 form-group col-md-12 col-lg-6">
                          <label class=" control-label no-padding-right" for="noteOrder">Lưu ý đặt hàng</label>
                          <div class="clearfix">
                  <textarea id="noteOrder" rows="6" class="form-control ckeditor"
                            name="noteOrder">{{ old('noteOrder', $product->s_NoteOrder) }}</textarea>
                          </div>
                      </div>
                  </div>
                  <div class="col-xs-12 form-group col-md-12 col-lg-12">
                    <label class=" control-label no-padding-right" for="description">Mô tả</label>
                    <div class="clearfix">
                    <textarea id="description" rows="6" class="form-control ckeditor"
                              name="description">{{ old('description', $product->Description) }}</textarea>
                    </div>
                </div>
              </div>
              <div class="col-xs-12 col-md-3 col-lg-3">
                  <div class="form-group col-xs-12 col-md-6 col-lg-6">
                      <label class=" control-label no-padding-right" for="purchasePrice"> Giá nhập </label>
                      <div class="clearfix">
                          <input type="hidden" name="purchasePrice" value="{{$product->UnitPrice}}">
                          <input placeholder="Giá nhập" name="purchasePrice" {{$disabledPurchasePrice}} value="{{ old('purchasePrice', $unitPrice) }}" id="purchasePrice"
                                 class="form-control" type="text">
                      </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-6 col-lg-6">
                      <label class=" control-label no-padding-right" for="unitPrice">Giá bán</label>
                      <div class="clearfix">
                          <input placeholder="Giá bán" name="unitPrice" value="{{ old('unitPrice', $product->Price) }}" id="unitPrice"
                                 class="form-control" type="text">
                      </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-6 col-lg-6">
                      <label class=" control-label no-padding-right" for="minInStock">Tối thiểu trong kho</label>
                      <div class="clearfix">
                          <input placeholder="Tối thiểu trong kho" type="number" value="{{ old("minInStock", $product->MinInStock) }}" min="0" name="minInStock"
                                 class="form-control" id="minInStock">
                      </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-6 col-lg-6">
                      <label class=" control-label no-padding-right" for="maxInStock">Tối đa trong kho</label>
                      <div class="clearfix">
                          <input placeholder="Tối đa trong kho" type="number" value="{{ old("maxInStock", $product->MaxInStock) }}" min="0" name="maxInStock"
                                 class="form-control" id="maxInStock">
                      </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-12 col-lg-12">

                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <label class=" control-label no-padding-right" for="tel"> Chuyên mục sản phẩm </label>
                        <div class="clearfix">
                            <input type="checkbox" name="IsNew" id="IsNew"  {{ $product->IsNew == 1 ? 'checked' : '' }}>Sản phẩm mới<br>

                            <input type="checkbox" name="IsFeature" id="IsFeature" {{ $product->IsFeature == 1 ? 'checked' : '' }}>Sản phẩm nổi bật


                        </div>


                    </div>
                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <label class=" control-label no-padding-right" for="tel"> % Giảm giá </label>
                        <div class="clearfix">
                            <input placeholder="Phần trăm giảm giá" type="number" value="{{ old('Discount',$product->Discount) }}"
                            min="0" max="100" name="Discount" class="form-control" id="Discount">


                        </div>


                    </div>

                </div>
                  <div class="center col-xs-12 col-sm-12 col-lg-12 col-md-12">
                      <div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
                          <label class="bolder bigger-110 " for="picture">Ảnh</label>
                          <input type="file" name="picture" class="form-control" id="picture">
                          <span class="text-danger">{{ $errors->first('picture') }}</span>
                      </div>
                      <img id="show_image" src="{{ $product->Picture }}" alt="" width="200" height="100" class="img-responsive img-thumbnail">
                  </div>
              </div>

              

              <hr/>
              <div class="form-group col-sm-12">
                  <div class="btn-group">
                      <input type="submit" class="btn btn-info " value="Lưu">
                  </div>
                  <div class="btn-group">
                      <a class="btn btn-danger" href="{{ route('product.index') }}" >Quay lại</a>
                  </div>
              </div>
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
       jQuery(document).one('load', function (e) {
           jQuery("#site_map").append("<i class='ace-icon fa '></i><a href='{{ route('product.create') }}' class='click_me'>Create Product</a>");
           // e.isImmediatePropagationStopped()
       });
   </script>
   <!-- inline scripts related to this page -->
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
           $('#unitPrice, #purchasePrice').mask("#.##0", {reverse: true});
           var demo1 = jQuery('select[name="categories[]"]').bootstrapDualListbox({infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>'});
           var container1 = demo1.bootstrapDualListbox('getContainer');
           container1.find('.btn').addClass('btn-white btn-info btn-bold');
           //typeahead.js
           //example taken from plugin's page at: https://twitter.github.io/typeahead.js/examples/
           var substringMatcher = function (strs) {
               return function findMatches(q, cb) {
                   var matches, substringRegex;
                   // an array that will be populated with substring matches
                   matches = [];
                   // regex used to determine if a string contains the substring `q`
                   substrRegex = new RegExp(q, 'i');
                   // iterate through the pool of strings and for any string that
                   // contains the substring `q`, add it to the `matches` array
                   jQuery.each(strs, function (i, str) {
                       if (substrRegex.test(str)) {
                           // the typeahead jQuery plugin expects suggestions to a
                           // JavaScript object, refer to typeahead docs for more info
                           matches.push({value: str});
                       }
                   });
                   cb(matches);
               }
           }
           jQuery('input.typeahead').typeahead({
               hint: true,
               highlight: true,
               minLength: 1
           }, {
               name: 'states',
               displayKey: 'value',
               source: substringMatcher(ace.vars['US_STATES']),
               limit: 10
           });
           //in ajax mode, remove remaining elements before leaving page
           jQuery(document).one('ajaxloadstart.page', function (e) {
               jQuery('[class*=select2]').remove();
               jQuery('select[name="categories[]"]').bootstrapDualListbox('destroy');
               jQuery('.rating').raty('destroy');
               jQuery('.multiselect').multiselect('destroy');
           });

           var demo1 = jQuery('select[name="colors[]"]').bootstrapDualListbox({infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>'});
           var container1 = demo1.bootstrapDualListbox('getContainer');
           container1.find('.btn').addClass('btn-white btn-info btn-bold');
           //typeahead.js
           //example taken from plugin's page at: https://twitter.github.io/typeahead.js/examples/
           var substringMatcher = function (strs) {
               return function findMatches(q, cb) {
                   var matches, substringRegex;
                   // an array that will be populated with substring matches
                   matches = [];
                   // regex used to determine if a string contains the substring `q`
                   substrRegex = new RegExp(q, 'i');
                   // iterate through the pool of strings and for any string that
                   // contains the substring `q`, add it to the `matches` array
                   jQuery.each(strs, function (i, str) {
                       if (substrRegex.test(str)) {
                           // the typeahead jQuery plugin expects suggestions to a
                           // JavaScript object, refer to typeahead docs for more info
                           matches.push({value: str});
                       }
                   });
                   cb(matches);
               }
           }
           jQuery('input.typeahead').typeahead({
               hint: true,
               highlight: true,
               minLength: 1
           }, {
               name: 'states',
               displayKey: 'value',
               source: substringMatcher(ace.vars['US_STATES']),
               limit: 10
           });
           //in ajax mode, remove remaining elements before leaving page
           jQuery(document).one('ajaxloadstart.page', function (e) {
               jQuery('[class*=select2]').remove();
               jQuery('select[name="categories[]"]').bootstrapDualListbox('destroy');
               jQuery('.rating').raty('destroy');
               jQuery('.multiselect').multiselect('destroy');
           });

           $("#Uproduct_form").validate({
               errorElement: 'div',
               errorClass: 'help-block',
               focusInvalid: false,
               ignore: "",
               rules: {
                   product_code: "required",
                   product_name: "required",
                   unit: "required",
                   unitPrice: {required: true},
                   purchasePrice: {required: true},
                   minInStock: "required",
                   maxInStock: "required",
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
       });
   </script>
   {{--send date with AJAX--}}
   @if(env('APP_AJAX') )
      <script type="text/javascript">
          $(document).ready(function () {
              //get value of radio buttons
              var radio_val = $("input[name='cover']").val();
              $(".radio").click(function () {
                  radio_val = $(this).val();
              });
              $("#Uproduct_form").submit(function (e) {
                  e.preventDefault();
                  var form = $(this);
                  var data_form = new FormData(this);
                  data_form.append('_method', 'PUT');
                  /*var data = {
                      product_name: $("#product_name").val(),
                      made_in: $("#made_in").val(),
                      brand_id: $("#brand_id").val(),
                      product_slug: $("#product_slug").val(),
                      sale_price: $("#sale_price").val(),
                      buy_price: $("#buy_price").val(),
                      quantity: $("#quantity").val(),
                      weight: $("#weight").val(),
                      description: $("#description").val(),
                      data_available: $("#data_available").val(),
                      off_price: $("#off_price").val(),
                      categories: $("#duallist0").val(),
                      colors: $("#duallist").val(),
                      photos: $("#gallery-photo-add").val(),
                      cover: radio_val,
                  };*/
                  // check if the input is valid
                  // if (!form.valid()) return false;
                  $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                      }
                  });
                  $(document).ajaxSend(function() {
                      $("#overlay").fadeIn(5000);
                  });
                  $.ajax({
                      url: "{{ route('product.update',$product->Id) }}",
                      method: "POST",
                      enctype: 'multipart/form-data',
                      dataType: 'json',
                      data: data_form,
                      contentType: false,
                      cache: false,
                      processData: false,
                      beforeSend: function () {
                          $(".preview").toggle();
                          $("#overlay").fadeIn(500);
                      },
                      success: function (data) {
                          //show loading image ,
                          $(".preview").hide();
                          $("#overlay").fadeOut(300);

                          if (data.success === true) {
                              Swal.fire(
                                  'Cập nhật sản phẩm thành công!!!',
                                  '',
                                  'success'
                              )
                              $("#error_result").empty();
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
                          let json = $.parseJSON(request.responseText);
                          if (json.success === false) {
                              Swal.fire(
                                  'Đã có lỗi xảy ra!!!',
                                  json.message,
                                  'error'
                              )
                              $(".preview").hide();
                              return
                          }
                          $("#error_result").empty();
                          $(".preview").hide();
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

   <script type="text/javascript">
       <!-- show selected images -->
       $(document).ready(function () {
           $(function () {
               // Multiple images preview in browser
               var imagesPreview = function (input, placeToInsertImagePreview) {
                   // $(".gallery").empty();
                   if (input.files) {
                       var filesAmount = input.files.length;
                       $(".ace-file-name").attr('data-title', 'now choose the cover');
                       var c = 0;
                       for (var i = 0; i < filesAmount; ++i) {
                           var reader = new FileReader();

                           reader.onload = function (event) {
                               var showImage = '<div class="fileuploader-items div-show">' +
                                   '<ul class="fileuploader-items-list">' +
                                   '<li class="fileuploader-item file-has-popup file-type-image file-ext-png">' +
                                   '<div class="columns"><div class="column-thumbnail"><div class="fileuploader-item-image fileuploader-no-thumbnail">' +
                                   '<div style="background-color: #298a22" class="fileuploader-item-icon">' +
                                   '<img src="' + event.target.result + '" alt="' + input.files[c].type + '"></div></div><span class="fileuploader-action-popup"></span></div>' +
                                   '<div class="column-title">' +
                                   '<div title="innostudio.de__setting-icnload.png">' + input.files[c].name + '</div>' +
                                   '<span>' + (input.files[c].size) + ' KB </span></div>' +
                                   '<div class="column-actions">' +
                                   '<input type="radio" name="cover" value="' + input.files[c].name + '" class="cover_cb fileuploader-action radio" id="cover" title="Cover Photo"><i class="icon ui-icon-asc"></i>' +
                                   '<a class="fileuploader-action fileuploader-action-remove" title="remove">' +
                                   '<i onclick="removeImage(this)"></i></a>' +
                                   '</div></div><div class="progress-bar2"><div class="fileuploader-progressbar"><div class="bar"></div></div><span></span>' +
                                   '</div></li></ul></div>\n';
                               $(".gallery").append(showImage);
                               c++;
                           }
                           reader.readAsDataURL(input.files[i]);
                       }
                   }
               };

               $('#gallery-photo-add').on('change', function () {
                   imagesPreview(this, 'div.gallery');
               });
           });
       });

       function removeImage(e) {
           $(e).parents(":eq(4)").remove()
       }

       $(".destroy_image").click(function () {
           var obj = $(this);
           var photo_id = $(this).data("id");
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
               }
           });
           $.ajax({
               url: " /admin/photo/" + photo_id,
               method: "DELETE",
               datatype: "json",
               data: {id: photo_id},
               success: function (data) {
                   if ( data.success === true) {

                       alert(data.message);
                       obj.parents(":eq(5)").remove();
                   }
               },
               error: function (request, status, error) {
                   console.log(request);
                   var json = $.parseJSON(request.responseText);
                   if (json.success === false) {
                       alert(json.message);
                   }
                   console.log(json,error);
               }
           });
       });
   </script>
   <script src="{{ asset('admin-assets/js/bootstrap-tag.min.js') }}"></script>
   <!-- FOR TAG INPUT -->
   <script type="text/javascript">
       var tag_input = $('#form-field-tags');
       try {
           tag_input.tag(
               {
                   source: function (query, process) {
                       $.ajax({
                           url: '/admin/product/tags/' + query,
                           type: 'get'
                       }).done(function (result_items) {
                           process(result_items);
                       });
                   }
               }
           )
       } catch (e) {
           tag_input.after('<textarea id="' + tag_input.attr('id') + '" name="' + tag_input.attr('name') + '" rows="3">' + tag_input.val() + '</textarea>').remove();
       }
   </script>
@stop
