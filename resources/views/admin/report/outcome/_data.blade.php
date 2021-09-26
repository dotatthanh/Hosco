@forelse($data as $key=> $order)
   <tr>
        <td class="center">{{++$key}}</td>
        <td class="center" >{{ $order->Location_ID}}</td>
        <td class="center">{{$order->OrderCode}}</td>
        <td>{{ $order->OrderDate }}</td>
        <td class="center">{{ $order->EmployeeName}}</td>
        <td class="center">{{ $order->CustomerCode}}</td>
        <td class="center">{{ $order->CustomerName}}</td>
        <td class="center">{{ $order->CustomerTel }}</td>
        <td class="center">{{ $order->CustomerAddress }}</td>
        <td class="center">{{ number_format($order->TotalMoney) }}</td>
        <td class="center">
           @if($order->b_isCash)
               Tiền mặt
           @else
               Chuyển khoản
           @endif
        </td>
        <td class="center">{{ $order->PaymentReason }}</td>
        <td>
            @if($order->b_isCash)
                Tiền mặt
            @else
                Chuyển khoản
            @endif
        </td>
        <td>{{ $order->Description }}</td>
   </tr>
@empty
   <tr class="center">
      <td colspan="14">No Data</td>
   </tr>
@endforelse
