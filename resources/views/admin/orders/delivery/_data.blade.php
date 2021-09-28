@forelse($data as $key=> $order)
    <tr>
        <td class="center">{{++$key}}</td>
        <td class="center order_status" >
            {{$order->OrderCode}}
        </td>
        <td>{{ $order->OrderDate }}</td>
        <td class="center">{{ $order->CustomerName }}</td>
        <td class="center">{{ $order->EmployeeName }}</td>
        {{-- <td class="center">{{ $order->OrderStatus }}</td> --}}
        <td class="center">
            @if ($order->Status == "-1")
                Chờ xác nhận
            @elseif ($order->Status == "0")
                Đã xác nhận
            @elseif ($order->Status == "1")
                Đang giao
            @elseif ($order->Status == "2")
                Đã giao
            @elseif ($order->Status == "3")
                Thất bại
            @else
                Chờ xác nhận
            @endif
        </td>
        <td class="center">{{ $order->Discount }}</td>
        <td class="bolder text-right">{{ number_format($order->TotalMoney) }}</td>

        <td class="center">

            {{-- <div class="hidden-md hidden-lg">
                <div class="inline pos-rel">
                    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
                        <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    	@if(isRead(env('ORDER_ROLE_CODE')))
                        <li>
                            <a class="btn btn-info2 btn-xs click_me" title="Chi tiết đơn hàng"
                               href="{{ route('order.show', ['id' => $order->Id, 'type' => $type]) }}">
                                <i class="ace-icon fa fa-eye bigger-120"></i>
                            </a>
                        </li>
                        @endif
                        @if(isUpdate(env('ORDER_ROLE_CODE')))
                        <li>
                            <a class="btn btn-warning btn-xs" title="Sửa đơn hàng"
                            href="{{ route('selOrder.edit', ['type' => 'purchase', 'id' => $order->Id]) }}" data-id="{{ $order->Id }}">
                                <i class="ace-icon fa fa-edit bigger-120"></i>
                            </a>
                        </li>
                        @endif
                        @if(isDelete(env('ORDER_ROLE_CODE')))
                            <li>
                                <a class="btn btn-sm btn-danger delete_me" title="Delete" data-id="{{ $order->Id }}">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div> --}}

            <div class="hidden-sm hidden-xs btn-group">
             	{{-- @if(isRead(env('ORDER_ROLE_CODE')))
                <a class="btn btn-info2 btn-xs click_me" title="Chi tiết đơn hàng"
                   href="{{ route('order.show',['id' => $order->Id, 'type' => $type]) }}">
                    <i class="ace-icon fa fa-eye bigger-120"></i>
                </a>
                @endif
                 @if(isUpdate(env('ORDER_ROLE_CODE')))
                <a class="btn btn-warning btn-xs" title="Sửa đơn hàng"
                   href="{{ route('selOrder.edit', ['type' => 'purchase', 'id' => $order->Id]) }}" data-id="{{ $order->Id }}">
                    <i class="ace-icon fa fa-edit bigger-120"></i>
                </a>
                @endif
                @if(isDelete(env('ORDER_ROLE_CODE')))
                    <button class="btn btn-sm btn-danger delete_me" title="Delete" data-id="{{ $order->Id }}">
                        <i class="ace-icon fa fa-trash bigger-120"></i>
                    </button>
                @endif --}}
                
                {{-- <form action="{{ route('order.selOrder.change_status', $order->Id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Chuyển trạng thái</button>
                </form> --}}

                <a class="btn btn-warning btn-xs" title="Chuyển trạng thái"
                   href="{{ route('order.edit-delivery-status', ['id' => $order->Id]) }}" data-id="{{ $order->Id }}">
                    <i class="ace-icon fa fa-edit bigger-120"></i>
                </a>
            </div>

        </td>
    </tr>
@empty
    <tr class="center">
        <td colspan="12">Không có dữ liệu</td>
    </tr>
@endforelse
