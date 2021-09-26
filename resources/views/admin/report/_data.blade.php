@forelse($data as $key=> $order)
    <tr>
        <td class="center">{{++$key}}</td>
        <td class="center" >
            {{ $order->Location_ID}}
        </td>
        <td>{{ $order->OrderCode }}</td>
        <td class="center">
            {{ $order->OrderDate}}
        </td>
        <td class="center">
            {{ $order->EmployeeName}}
        </td>
        <td class="center">
            {{ $order->CustomerCode}}
        </td>
        <td class="center">{{ $order->CustomerName }}</td>
        <td class="center">{{ $order->CustomerTel }}</td>
        <td class="center">{{ $order->CustomerAddress }}</td>
        <td class="bolder">{{ number_format($order->TotalMoney) }}</td>
        <td class="center">
            @if($order->b_isCash)
                Tiền mặt
            @else
                Chuyển khoản
            @endif
        </td>
        <td>{{ $order->BankName }}
        </td>
        <td>{{ $order->PaymentReason }}</td>
        <td></td>
        <td>{{ $order->Description }}</td>
    </tr>
@empty
    <tr class="center">
        <td colspan="15">Không có dữ liệu</td>
    </tr>
@endforelse