@section('extra_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('extra_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="{{ asset('admin-assets/js/jquery.mask.min.js') }}"></script>
    <script>
        const formatter = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: {{ isset($orderConfig->format_number_money)? $orderConfig->format_number_money :2 }}
        })

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            deleteAjax("/admin/orders/orders-status/", "delete_me", "Order Details");

            $(document).on('change', '#locationId', function() {

                let locationId = $(this).val();
                if(locationId == "") return false;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('order.code', $type )}}",
                    method: 'POST',
                    data: {
                        locationId: locationId
                    },
                    success: function (data) {
                        $('#orderCode').val(data.orderCode);

                        let sel = $("#storeId");
                        sel.empty();
                        for (let i=0; i<data.storeList.length; i++) {
                            let $selected = "";
                            @if (isset($storeId))
                                if(data.storeList[i].Id == '{{$storeId}}')
                                    $selected = "selected";
                            @endif
                            sel.append('<option value="' + data.storeList[i].Id + '" '+$selected+'>' + data.storeList[i].StoreName + '</option>');
                        }
                    }
                })
            });

            $('#locationId').trigger('change');
        });

        $('#products_table').on('change keypress keyup', 'input.qty, input.price, ' +
            '.f-discount, .m-discount', function() {
            let $tr = $(this).closest('tr');
            let num = $tr.find('input.qty').val();
            let price = $tr.find('input.price').cleanVal();
            price = Number(price);

            let fDiscount = $tr.find('.f-discount').val();
            let mDiscount = $tr.find('.m-discount').val();

            $tr.find('.m-discount').attr('max', price);

            if(fDiscount > 100) {
                $tr.find('.f-discount').val(100);
            }

            if(Number(mDiscount) > Number(price)) {
                $tr.find('.m-discount').val(price);
            }

            let total = num * price;
            if(fDiscount > 0) {
                total = num * (price - fDiscount*price/100);
            } else if(mDiscount > 0) {
                total = num * (price - mDiscount);
            }

            $tr.find('td.total > input').val(total);
            $tr.find('td.total > span').text(formatter.format(total));
            calTotal();
            $('.paid, #totalPrice').trigger('change');
        });

        $(".vat, .discount, .discountAmount, .paid, #totalPrice").on('change keypress keyup', function () {
            calTotal();
            let paid = $('.paid').val();
            if(Number(paid) > 0) {
                let total = $("#totalPrice").val();
                let pay = Number(paid - total);
                $(".paymentLeft").val(formatter.format(pay));
            } else {
                $(".paymentLeft").val('');
            }
        });

        $('#customerId').select2({
            placeholder: 'Chọn khách hàng',
            delay: 250,
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                return data.html;
            },
            templateSelection: function(data) {
                return data.text;
            },
            ajax: {
                url: "{{ route('customer.index') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (res, params) {
                    let data = res.result.data;
                    let page = params.page || 1;
                    return {
                        results: $.map(data, function(item) {
                            return {
                                title: item.Name,
                                text: item.CustomerCode + "/" + item.Name + "/" + item.Tel,
                                id: item.Id,
                                html: item.CustomerCode + "/" + item.Name + "/" + item.Tel,
                                data: item
                            };
                        }),
                        pagination: {
                            more: page * 5 <= res.result.total
                        }
                    };
                },
                cache: true
            }
        }).on('select2:select', function (e) {
            $(".select-product").prop("disabled", false);
            $('#products_table').find('tbody tr').each(function() {
                let that = $(this);
                $.ajax({
                    url: "{{ route('products.price') }}",
                    method: "POST",
                    data: {
                        productId: that.val(),
                        customerId: e.params.id
                    },
                    success: function(response) {
                        that.find('input.price').val(response.result);
                        that.find('input.price').trigger('change');
                    }
                });
            });
        }).trigger('change');

        $('#select-product')
            .autocomplete({
                _resizeMenu: function() {
                    this.menu.element.outerWidth( 500 );
                },
                source: function(request, response) {
                    let price_group = '';
                    let search_fields = [];
                    $('.search_fields:checked').each(function(i){
                        search_fields[i] = $(this).val();
                    });

                    if ($('#price_group').length > 0) {
                        price_group = $('#price_group').val();
                    }
                    $.getJSON(
                        '{{ route("product.index") }}',
                        {
                            productCode: request.term,
                            perPage: 20,
                            page: 1,
                            price_group: price_group,
                            location_id: $('input#location_id').val(),
                            term: request.term,
                            not_for_selling: 0,
                            search_fields: search_fields
                        },
                        response
                    );
                },
                minLength: 0,
                autoFocus: true,
                response: function(event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        if (ui.item.InStock > 0) {
                            $(this)
                                .data('ui-autocomplete')
                                ._trigger('select', 'autocompleteselect', ui);
                            $(this).autocomplete('close');
                        }
                    } else if (ui.content.length == 0) {
                        $('input#search-product').select();
                    } else {
                        ui.item = ui.content[1];
                    }
                },
                focus: function(event, ui) {
                    if (ui.item.InStock <= 0) {
                        return false;
                    }
                },
                select: function(event, ui) {
                    let is_overselling_allowed = false;
                    if($('input#is_overselling_allowed').length) {
                        is_overselling_allowed = true;
                    }

                    if (/*ui.item.enable_stock != 1 || ui.item.InStock > 0 ||*/ is_overselling_allowed) {
                        $(this).val(null);
                        let currQty = 0;
                        let qtyTemp = $('#products_table').find('tbody tr.' + ui.item.Id).find('input.qty').val();
                        if(qtyTemp) {
                            currQty = Number(qtyTemp);
                        }
                        //if(ui.item.InStock > currQty) {
                            addRow(ui.item);
                        //}
                    } else {
                        //alert(LANG.out_of_stock);
                    }
                },
            }).focus(function(){
                window.pageIndex = 0;
                $(this).autocomplete("search");
            }).on('keydown', function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        })
            .autocomplete('instance')._renderItem = function(ul, item) {
                $(ul).unbind("scroll");
                let is_overselling_allowed = false;
                if($('input#is_overselling_allowed').length) {
                    is_overselling_allowed = true;
                }

                if (item.InStock <= 0 && !is_overselling_allowed) {
                    let string = '<li class="ui-state-disabled">' + item.Name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }
                    let selling_price = item.Price;
                    if (item.variation_group_price) {
                        selling_price = item.variation_group_price;
                    }
                    string +=
                        ' (' +
                        item.ProductCode +
                        ')' +
                        '<br> Price: ' +
                        selling_price +
                        ' (Out of stock) </li>';
                    return $(string).appendTo(ul);
                } else {
                    let string = '<div>' + item.Name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }

                    let selling_price = item.Price;
                    if (item.variation_group_price) {
                        selling_price = item.variation_group_price;
                    }

                    string += ' (' + item.ProductCode + ')' + '<br> Price: ' + selling_price;
                    if (item.InStock > 0) {
                        string += ' - ' + item.InStock + ' ' + item.Unit;
                    }
                    string += '</div>';

                    return $('<li>')
                        .append(string)
                        .appendTo(ul);
                }
            };

        $('form').on('click', '.add-product', function() {
            let customerId = $('#customerId').val();
            if( customerId == null || customerId == "") {
                Swal.fire(
                    'Bạn chưa chọn khách hàng!!!',
                    '',
                    'error'
                );

                return false;
            }
        });

        $('#products_table').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calTotal();
        });

        function addRow(data) {
            if(typeof data == "undefined" || data == null) return false;

            $.ajax({
                url: "{{ route('products.price') }}",
                method: "POST",
                data: {
                    productId: data.Id,
                    customerId: $('#customerId').val()
                },
                success: function(response) {
                    let price = response.result;
                    let qty = 1;
                    let total = price * qty;
                    let totalText = formatter.format(total);

                    let isExit = false;
                    $('#products_table').find('tbody tr').each(function() {
                        if($(this).hasClass(data.Id)) {
                            isExit = true;
                            let currQty = $(this).find('input.qty').val();
                            $(this).find('input.qty').val(Number(currQty)+1);
                            $('input.qty').trigger('change');
                            return false;
                        }
                    });

                    showProductDetailPopup(data);

                    if(isExit) return false;

                    let $tr = '<tr class="'+data.Id+'">\n' +
                        '          <td><a href="#" data-toggle="modal" data-target="#productModal">'+data.ProductCode + '/' + data.Name+' <i class="fa fa-info-circle"></i></a><input type="hidden" name="products[]" value="'+data.Id+'" /></td>\n' +
                        '          <td>'+data.Unit+'</td>\n' +
                        '          <td class="qty" width="10%">\n' +
                        '              <input type="number" name="quantities[]" class="form-control qty" value="'+qty+'" min="0"/>\n' +
                        '          </td>\n' +
                        '          <td class="price" width="10%">\n' +
                        //'              <span>'+formatter.format(price)+'</span>\n' +
                        '              <input type="text" name="prices[]" value="'+price+'" class="form-control price money" />\n' +
                        '          </td>\n' +
                        '          <td class=""><input type="number" name="f_discount[]" value="" min="0" max="100" class="form-control f-discount" /></td>\n' +
                        '          <td class=""><input type="number" name="m_discount[]" value="" min="0" class="form-control m-discount" /></td>\n' +
                        '          <td class="total">\n' +
                        '              <span>'+totalText+'</span>\n' +
                        '              <input type="hidden" name="total[]" value="'+total+'" /></td>\n'+
                        '          <td class=""><textarea type="text" name="description[]" value="" class=""></textarea></td>\n' +
                        '          <td><a href="#" class="btn btn-sm btn-danger remove-row"><i class="fa fa-minus-circle"></i></a></td>\n' +
                        '       </tr>';

                    $('#products_table').find('tbody').append($tr);
                    //$('.money').simpleMoneyFormat();

                    calTotal();
                }
            });
        }

        $(".discountType").on('change', function(e) {
            $(".discount, .discountAmount").hide().prop("disabled", true);
            $(".discount, .discountAmount").closest('.input-group').find('.input-group-addon').hide();
            let val = $(".discount").val();
            let vall = $(".discountAmount").val();
            if($(this).val() == 'fDiscount') {
                $(".discount").show().prop("disabled", false).val(val);
                $(".discount").closest('.input-group').find('.input-group-addon')
                    .show().text('%');
                //$(".discountAmount").val(0);
            }
            if($(this).val() == 'mDiscount') {
                //$(".discount").val(0);
                $(".discountAmount").val(vall);
                $(".discountAmount").show().prop("disabled", false);
                $(".discountAmount").closest('.input-group').find('.input-group-addon').show().text('VND');
            }
            calTotal();
        });

        $(".discountType").trigger('change');

        function showProductDetailPopup(data) {
            let productName = data.Name ? data.Name : data.ProductName;
            let qty = data.InStock ? data.InStock : data.Qty;
            let html = '<div id="user-profile-1" class="user-profile row">\n' +
                '         <div class="col-xs-12 col-sm-4 center">\n' +
                '            <div>\n' +
                '               <span class="profile-picture">\n' +
                '                     <img id="avatar"\n' +
                '                          class="editable img-responsive editable-click editable-empty"\n' +
                '                          alt="Picture" src="'+data.Picture+'">\n' +
                '               </span>\n' +
                '            </div>\n' +
                '         </div>\n' +
                '         <div class="col-xs-12 col-sm-8">\n' +
                '            <div class="profile-user-info profile-user-info-striped">\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name">Tên sản phẩm</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="username">'+productName+'</span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '                <div class="profile-info-row">\n' +
                '                    <div class="profile-info-name">Mã sản phẩm</div>\n' +
                '                    <div class="profile-info-value">\n' +
                '                        <span class="editable editable-click" id="username">'+data.ProductCode+'</span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="profile-info-row">\n' +
                '                    <div class="profile-info-name">Nhóm hàng</div>\n' +
                '                    <div class="profile-info-value">\n' +
                '                        <span class="editable editable-click" id="username">'+data.GroupName+'</span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name"> Giá nhập</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="age">'+data.UnitPrice+'</span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name">Giá bán</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="age">'+formatter.format(data.Price)+'</span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name">Giảm giá</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="age">'+formatter.format(data.Price)+'</span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name">Số lượng</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="about">\n' + qty +
                '                     </span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '               <div class="profile-info-row">\n' +
                '                  <div class="profile-info-name">Đơn vị tính</div>\n' +
                '                  <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="about">\n' + data.Unit +
                '                     </span>\n' +
                '                  </div>\n' +
                '               </div>\n' +
                '                <div class="profile-info-row">\n' +
                '                    <div class="profile-info-name">Mô tả nhập hàng</div>\n' +
                '                    <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="s_NoteImport">\n' +
                '                         <span class=\'label label-default\'>'+data.s_NoteImport+'</span>\n' +
                '                     </span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="profile-info-row">\n' +
                '                    <div class="profile-info-name">Mô tả đặt hàng</div>\n' +
                '                    <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click" id="s_NoteOrder">\n' +
                '                         <span class=\'label label-default\'>'+data.s_NoteOrder+'</span>\n' +
                '                     </span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="profile-info-row">\n' +
                '                    <div class="profile-info-name">Mô tả</div>\n' +
                '                    <div class="profile-info-value">\n' +
                '                     <span class="editable editable-click">\n' +
                '                         <span class=\'label label-default\'>'+data.Description+'</span>\n' +
                '                     </span>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '      </div>\n' +
                '   </div>' +
                '</div>\n';

            $("#productModal .modal-body").html(html);
        }

        $('#productModal').on('show.bs.modal', function (event) {
            let triggerElement = $(event.relatedTarget);
            let attr = triggerElement.attr('data-product');
            if(typeof attr !== typeof undefined && attr !== false) {
                showProductDetailPopup(triggerElement.data("product"));
            }
        })

        function calTotal() {
            let subTotal = 0;
            let vat = Number($(".vat").val())
            let discount = Number($(".discount").val());
            let discountAmount = Number($('.discountAmount').val());

            $('#products_table').find('td.total').each(function(){
                subTotal += Number($(this).find('input').val());
            });

            let totalVat = (vat/100) * subTotal
            let totalDiscount = discount/100 * subTotal;

            if($(".discountType").val() == 'fDiscount') {
                discountAmount = 0;
            }

            if($(".discountType").val() == 'mDiscount') {
                totalDiscount = 0;
            }

            $(".priceText").val(formatter.format(subTotal + totalVat - totalDiscount - discountAmount));
            $("#totalPrice").val(subTotal + totalVat - totalDiscount - discountAmount);
        }

        $("#paymentMethod").on('change', function() {
            let value = $(this).val();
            $("#bankList").parents('.cardId').addClass('sr-only');
            if(value == 'card') {
                $.ajax({
                    url: "{{ route('customer.bank-list') }}",
                    method: "GET",
                    success: function(response) {
                        $("#bankList").parents('.cardId').removeClass('sr-only');
                        $.each(response.result,function(key, value)
                        {
                            $("#bankList").append('<option value=' + value.Id + '>' + value.BankName + '</option>');
                        });
                    }
                });
            }
        });

        $(document).ready(function () {
            var isTrue = false;
            $("#create-order-form").validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        employeeId: "required",
                        locationId: "required",
                        customerId: "required",
                        storeId: "required"
                    },
                    messages: {
                        locationId: "Không được để trống",
                        employeeId: "Không được để trống",
                        customerId: "Không được để trống",
                        storeId: "Không được để trống",
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
                        isTrue = true;
                    },
                    invalidHandler: function (form) {
                    }
                })

            $("#create-order-form").submit(function (e) {
                e.preventDefault();
                if(!isTrue) return false;
                let form_data = new FormData(this);
                let url = $(this).attr('action');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    method: "post",
                    enctype: 'multipart/form-data',
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $(".preview").toggle();
                        $("#overlay").fadeIn(500);
                    },
                    success: function (data) {
                        $("#overlay").fadeOut(300);
                        if (data.success === true) {
                            $(".preview").hide();
                            $(".gallery").empty();

                            let redirectURL = $('#redirect-route').val();
                            let msg = 'Cập nhật đơn hàng thành công!!!';
                            if(redirectURL) {
                                msg = 'Thêm đơn hàng thành công!!!';
                            }
                            Swal.fire(
                                msg,
                                '',
                                'success'
                            )

                            if(redirectURL)
                                window.location.replace(redirectURL);
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
                            return;
                        }
                        $(".preview").hide();
                        $("#error_result").empty();
                        $.each(json.errors, function (key, value) {
                            $('.alert-danger').show().append('<p>' + value + '</p>');
                        });
                    }
                });
            });
        });

    </script>

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

    @if(env('APP_AJAX'))
        <script type="text/javascript">
            $(document).ready(function () {
                var isTrue = false;
                var isProductTrue = false;

                $('input.price, #unitPrice, #purchasePrice').mask("#.##0", {reverse: true});



                $("#customer_form").validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        customer_name: "required",
                        customer_code: "required",
                        groupId: {required: true}
                    },
                    messages: {
                        customer_code: "Không được để trống",
                        customer_name: "Không được để trống",
                        groupId: "Không được để trống",
                        tel: "Không được để trống",
                        email: "Không được để trống",
                        description: "Không được để trống",
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
                        isTrue = true;
                    },
                    invalidHandler: function (form) {
                    }
                });

                $("#product_form").validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        product_name: "required",
                        product_code: "required",
                        groupId: {required: true},
                        //minInStock: "required",
                        //maxInStock: "required",
                        unitPrice: "required",
                        purchasePrice: "required",
                        unit: "required"
                    },
                    messages: {
                        product_code: "Không được để trống",
                        product_name: "Không được để trống",
                        groupId: "Không được để trống",
                        minInStock: "Không được để trống",
                        maxInStock: "Không được để trống",
                        unitPrice: "Không được để trống",
                        purchasePrice: "Không được để trống",
                        unit: "Không được để trống"
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
                        isProductTrue = true;
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

                $("#customer_form").submit(function (e) {
                    e.preventDefault();
                    if(!isTrue) return false;
                    let form_data = new FormData(this);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('customer.store') }}",
                        method: "post",
                        enctype: 'multipart/form-data',
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $(".preview").toggle();
                            $("#overlay").fadeIn(500);
                        },
                        success: function (data) {
                            $("#overlay").fadeOut(300);
                            if (data.success === true) {
                                $(".select-product").prop("disabled", false);
                                $("select#customerId").html('<option value="'+ data.result.Id+'" selected="selected">'+data.result.Name+'</option>');
                                $("select#customerId").trigger('change', 'select2');
                                Swal.fire(
                                    'Thêm khách hàng thành công!!!',
                                    '',
                                    'success'
                                );
                                $("#myModal .btn-secondary").trigger('click');
                            } else {
                                if(data.code == -1) {
                                    Swal.fire({
                                        title: data.message + ' Bạn có muốn hệ thống tự động sinh mã khách hàng ?',
                                        text: '',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Đồng ý sinh mã tự động!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $(".autoCustomerCode").val(1);
                                            $("#customer_form button[type='submit']").trigger('click');
                                        }
                                    });
                                } else {
                                    Swal.fire(
                                        'Đã có lỗi xảy ra!!!',
                                        '<h3>' + data.message + '</h3>',
                                        'error'
                                    );
                                }
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
                                return;
                            }
                            $(".preview").hide();
                            $("#error_result").empty();
                            $.each(json.errors, function (key, value) {
                                $('.alert-danger').show().append('<p>' + value + '</p>');
                            });
                        }
                    });
                });

                $("#product_form").submit(function (e) {
                    e.preventDefault();
                    if(!isProductTrue) return false;
                    let form_data = new FormData(this);
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
                        beforeSend: function () {
                            $(".preview").toggle();
                            $("#overlay").fadeIn(500);
                        },
                        success: function (data) {
                            $("#overlay").fadeOut(300);
                            if (data.success === true) {
                                $(".select-product").prop("disabled", false);
                                $('#select-product').data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:data.result});

                                Swal.fire(
                                    'Thêm sản phẩm thành công!!!',
                                    '',
                                    'success'
                                );
                                //$("#product_form").reset();
                                $("#newProductModal .btn-secondary").trigger('click');
                            } else {
                                if(data.code == -1) {
                                    Swal.fire({
                                        title: data.message + ' Bạn có muốn hệ thống tự động sinh mã sản phẩm ?',
                                        text: '',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Đồng ý sinh mã tự động!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $(".autoProductCode").val(1);
                                            $("#product_form button[type='submit']").trigger('click');
                                        }
                                    });
                                } else {
                                    Swal.fire(
                                        'Đã có lỗi xảy ra!!!',
                                        '<h3>' + data.message + '</h3>',
                                        'error'
                                    );
                                }
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
                                return;
                            }
                            $(".preview").hide();
                            $("#error_result").empty();
                            $.each(json.errors, function (key, value) {
                                $('.alert-danger').show().append('<p>' + value + '</p>');
                            });
                        }
                    });
                });
            });
        </script>
    @endif
@stop
