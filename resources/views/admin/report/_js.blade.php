@section('extra_js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('input[name="dates"]').daterangepicker({
                locale: {
                    format: 'D/M/YYYY',
                    "customRangeLabel": "Tùy chọn",
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(6, 'days'), moment()],
                    '30 ngày trước': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            @can('order-delete')
            <!-- DELETE -->
            deleteAjax("/admin/orders/", "delete_me", "Order");
            @endcan
            <!-- SENT -->
            $(".sent_me").click(function (e) {
                e.preventDefault();
                if (!confirm('DO YOU WANT TO CHANGE STATUS?')) {
                    return false
                }
                var obj = $(this); // first store $(this) in obj
                var status = $(this).data("status");
                var href = $(this).attr("href");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: href,
                    method: "get",
                    success: function ($results) {
                        alert($results.message);
                        $(obj).closest("a").remove(); //delete icon
                        // var x = $(obj).parents('tr').load(location.href + obj); //delete icon

                        console.log($results);
                    },
                    error: function (xhr) {
                        alert(xhr.responseText.message);
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@stop
