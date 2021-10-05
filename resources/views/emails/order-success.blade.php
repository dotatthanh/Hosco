<p>Xin chào {{ $customer['Name'] }}!</p>
<p>Đơn hàng #{{ $order['OrderCode']  }} của bạn đã được đặt thành công ngày {{ date('d-m-Y', strtotime($order['OrderDate'])) }}</p>
<p>Chi tiết đơn hàng:</p>
<p>- Mã đơn hàng: {{ $order['OrderCode']  }}</p>
<p>- Ngày đặt: {{ date('d-m-Y', strtotime($order['OrderDate'])) }}</p>
<p>- Email: {{ $customer['Email'] }}</p>
<p>- Số điện thoại: {{ $customer['Tel'] }}</p>
<p>- Địa chỉ nhận hàng: {{ $customer['Address'] }}</p>
<p>- Thành tiền: {{ number_format($order['OrderTotalDiscount'], 0, ',', '.') }} VNĐ</p>
<p>- Phương thức thanh toán: {{ $order['CashMoney'] == 1 ? 'Tiền mặt' : 'Chuyển khoản' }}</p>