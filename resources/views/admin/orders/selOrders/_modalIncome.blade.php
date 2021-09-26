<div id="myModal" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalLabel">Thêm mới phiếu thu</h3>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="{{ route('order.selOrder.create_income') }}" id="create_income_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="orderId" value="0" id="orderId" />
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-12 col-lg-12">
                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="orderDate">Ngày lập phiếu</label>
                                <div class="clearfix">
                                    <input placeholder="Ngày lập phiếu" type="date" value="" name="orderDate"
                                           class="form-control" id="orderDate">
                                </div>
                            </div>
                            <div class="form-group col-xs-6 col-md-6 col-lg-6">
                                <label class=" control-label no-padding-right" for="total_money"> Số tiền </label>
                                <div class="clearfix">
                                    <input type="number" class="form-control paid" value="" min="0" name="total_money" id="total_money">
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-md-12 col-lg-12">
                                <label class=" control-label no-padding-right" for="description">Ghi chú</label>
                                <div class="clearfix">
                                    <textarea id="description" rows="6" class="form-control" name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 form-group col-md-12 col-lg-12">
                            <div class="form-group row">
                                <label class="control-label col-sm-4 no-padding-right" for="paymentMethod">Hình thức thanh toán</label>
                                <div class="col-sm-8">
                                    <select name="paymentMethod" class="form-control" id="paymentMethod">
                                        <option value="cash">Tiền mặt</option>
                                        <option value="card">Chuyển khoản</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row cardId sr-only">
                                <label class="control-label col-sm-4 no-padding-right" for="bankList">Danh sách ngân hàng</label>
                                <div class="col-sm-8">
                                    <select name="cardId" class="form-control" id="bankList"></select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group clearfix"></div>
                    <div class="modal-footer" style="background: none;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var isTrue = true;
        $(document).on('click','.open-modal-income', function () {
            let orderId=$(this).data('id');
            $('#orderId').val(orderId);
            let money=$(this).data('money');
            $('#total_money').val(money);
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            console.log(today);
            $('#orderDate').val(today);
        });
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
        $(document).on('submit','#create_income_form', function (e) {
            e.preventDefault();
            if(!isTrue) return false;
            let form_data = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('order.selOrder.create_income') }}",
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
                        Swal.fire(
                            'Thêm phiếu thu thành công!!!',
                            '',
                            'success'
                        );
                        $("#myModal .btn-secondary").trigger('click');
                    }else {
                        Swal.fire(
                            'Đã có lỗi xảy ra!!!',
                            '<h3>' + data.message + '</h3>',
                            'error'
                        );
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
    })
</script>