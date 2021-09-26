@forelse($data as $key=> $order)
   <tr>
      <td class="center">{{++$key}}</td>
      <td class="center order_status" >
          {{$order->OrderCode}}
      </td>
      <td>{{ $order->OrderDate }}</td>
      <td class="center">{{$order->CustomerName}}</td>
      <td class="center">{{$order->Detail->CustomerPhone}}</td>
       <td class="center">{{$order->EmployeeName}}</td>
      <td class="center">{{ $order->Detail->Location }}</td>
      <td class="center">{{ $order->OrderStatus }}</td>
      <td class="bolder text-right">{{ number_format($order->TotalMoney) }}</td>

      <td class="center">

         <div class="hidden-md hidden-lg">
            <div class="inline pos-rel">
               <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
                  <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
               </button>

               <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
               	@if(isRead(env('SALES_ORDER_ROLE_CODE')))
                 <li>
                    <a class="btn btn-info2 btn-xs click_mee" title="Chi tiết đơn hàng"
                         href="{{ route('order.show',['id' => $order->Id, 'type' => 'sel']) }}">
                       <i class="ace-icon fa fa-eye bigger-120"></i>
                    </a>
                 </li>
                 @endif
                 @if(isUpdate(env('SALES_ORDER_ROLE_CODE')))
                 <li>
                  <a class="btn btn-warning btn-xs" title="Sửa đơn hàng"
                        href="{{ route('selOrder.edit', ['type' => 'sel', 'id' => $order->Id]) }}" data-id="{{ $order->Id }}">
                        <i class="ace-icon fa fa-edit bigger-120"></i>
                     </a>
                 </li>
                  @endif
                  @if(isDelete(env('SALES_ORDER_ROLE_CODE')))
                     <li>
                        <a class="btn btn-sm btn-danger delete_me" title="Delete" data-id="{{ $order->Id }}">
                           <i class="ace-icon fa fa-trash bigger-120"></i>
                        </a>
                     </li>
                  @endif
                   <li>
                      <a class="btn btn-sm btn-danger delete_me" title="Tạo phiếu thu" data-id="{{ $order->Id }}">
                         <i class="ace-icon fa fa-trash bigger-120"></i>
                      </a>
                   </li>
               </ul>
            </div>
         </div>

         <div class="hidden-sm hidden-xs btn-group">
            <form>
            	@if(isRead(env('SALES_ORDER_ROLE_CODE')))
               <a class="btn btn-info2 btn-xs click_mee" title="Chi tiết đơn hàng"
                  href="{{ route('order.show', ['id' => $order->Id, 'type' => 'sel']) }}">
                  <i class="ace-icon fa fa-eye bigger-120"></i>
               </a>
               @endif
				@if(isUpdate(env('SALES_ORDER_ROLE_CODE')))
                  <a class="btn btn-warning btn-xs" title="Sửa đơn hàng"
                     href="{{ route('selOrder.edit', ['type' => 'sel', 'id' => $order->Id]) }}" data-id="{{ $order->Id }}">
                     <i class="ace-icon fa fa-edit bigger-120"></i>
                  </a>
                @endif
               
               @if(isDelete(env('SALES_ORDER_ROLE_CODE')))
                  <button class="btn btn-sm btn-danger delete_me" title="Delete" data-id="{{ $order->Id }}">
                     <i class="ace-icon fa fa-trash bigger-120"></i>
                  </button>
               @endif
                   @if($order->OrderStatus!='Đã thu' && isCreate(env('ADD_INCOME_CODE')))
                      <button class="btn btn-sm btn-info2 open-modal-income" data-money="{{ $order->TotalMoney }}" type="button" data-id="{{ $order->Id }}" data-toggle="modal" data-target="#myModal">
                         <i class="ace-icon fa fa-money-bill-alt bigger-120"></i>
                      </button>
                   @endif

            </form>
         </div>

      </td>
   </tr>
@empty
   <tr class="center">
      <td colspan="12">Không có dữ liệu</td>
   </tr>
@endforelse