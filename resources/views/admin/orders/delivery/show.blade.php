@extends('layout.admin.index' )
@section('title')
   Order Details
@stop
@section('extra_css')
@stop
@section('content')
    <div class="box box-info">
        <div class="box-body">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
      <div class="widget-box transparent">
         <div class="widget-header widget-header-large">
            <h3 class="widget-title grey lighter">
               <i class="ace-icon fa fa-leaf green"></i>
                Hóa đơn của khách
            </h3>
            <span>
               @if($order->CustomerId)
                    Khách hàng :<a href="#" data-href="{{ route('customer.show', $order->CustomerId) }}?isPopup=1" data-toggle="modal" data-target="#customerModal">{{ $order->CustomerName}}</a>
               @else
                  <span class="label label-default">GUEST</span>
               @endif
            </span>

            <div class="widget-toolbar no-border invoice-info">
               <span class="invoice-info-label">Mã hóa đơn:</span>
               <span class="red bolder">#{{ $order->OrderCode }}</span>

               <br/>
               <span class="invoice-info-label">Ngày:</span>
               <span class="blue">{{ date('d/m/Y', strtotime($order->OrderDate)) }}</span>
            </div>

            <div class="widget-toolbar hidden-480">
               <a href="{{route('order.export', ['id' => $order->Id, 'type' => 'purchase'])}}">
                  <i class="ace-icon fa fa-print"></i>
               </a>
            </div>
         </div>

         <div class="widget-body">
            <div class="widget-main padding-24">
               <div class="row">
                  <div class="col-sm-6">
                     <div class="row">
                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                           <b>Thông tin khách hàng</b>
                        </div>
                     </div>

                     <div>
                        <ul class="list-unstyled spaced">
                           <li>
                              <i class="ace-icon fa fa-caret-right blue"></i>Họ tên:
                              <span> {{ $order->CustomerName }}</span>
                           </li>

                           <li>
                              <i class="ace-icon fa fa-caret-right blue"></i>
                              Số điện thoại:
                              <b class="red">{{ $order->CustomerPhone }}</b>
                           </li>
                           <li>
                              <i class="ace-icon fa fa-caret-right blue"></i>
                              Email:
                              <b class="red">{{ $order->CustomerEmail }}</b>
                           </li>
                            <li>
                                <i class="ace-icon fa fa-caret-right blue"></i>
                                Địa chỉ:
                                <b class="red">{{ $order->CustomerAddress }}</b>
                            </li>

                           <li class="divider"></li>
{{--
                           <li>
                              <i class="ace-icon fa fa-caret-right blue"></i>
                              <b>Gift Card:</b>
                              @if ($gift = $order->giftCard)
                                 <span>{{ $gift->gift_name }}</span>
                                 <ul class="list-unstyled">
                                    <li><i class="ace-icon fa fa-caret-right blue"></i><b>Gift Price:</b><i class="ace-icon fa fa-caret-right green"></i>{{ $gift->gift_amount }}</li>
                                    <li><i class="ace-icon fa fa-caret-right blue"></i><b>Gift Code :</b><i class="ace-icon fa fa-caret-right green"></i>{{ $gift->gift_code }}</li>
                                 </ul>
                                 @else
                                 <span class="label label-yellow label-large">NO GIFT CARD</span>
                              @endif

                           </li>--}}
                        </ul>
                     </div>
                  </div><!-- /.col -->

                  <div class="col-sm-6">
                      <div class="row">
                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                           <b>Địa chỉ hóa đơn</b>
                        </div>
                        </div>
                      <div>
                         <ul class="list-unstyled spaced">
                             <li>
                                 <i class="ace-icon fa fa-caret-right blue"></i>Địa chỉ hóa đơn:
                                 <span> {{ $order->BillingAddress }}</span>
                             </li>
                             <li>
                                 <i class="ace-icon fa fa-caret-right blue"></i>Địa chỉ giao hàng:
                                 <span> {{ $order->ShippingAddress }}</span>
                             </li>
                         </ul>
                     </div>
                      {{--
                     @if ($address = $order->address)
                        <div class="row">
                           <div class="col-sm-6">
                              <ul class="list-unstyled">
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>NAME :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>SURNAME:</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>STATE :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>CITY :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>AREA :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>AVENUE :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>STREET :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>NOM :</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>PHONE NUMBER:</li>
                                 <li><i class="ace-icon fa fa-caret-right blue"></i>POSTAL CODE :</li>
                              </ul>
                           </div>
                           <div class="col-sm-6">
                              <ul>
                                 <li class="bolder">{{ $address->name }} </li>
                                 <li class="bolder">{{ $address->surname }} </li>
                                 <li class="bolder">{{ $address->state }} </li>
                                 <li class="bolder">{{ $address->city }} </li>
                                 <li class="bolder">{{ $address->area }} </li>
                                 <li class="bolder">{{ $address->avenue }} </li>
                                 <li class="bolder">{{ $address->street }} </li>
                                 <li class="bolder">{{ $address->number }} </li>
                                 <li class="bolder">{{ $address->phone_number }} </li>
                                 <li class="bolder">{{ $address->postal_code }} </li>
                              </ul>
                           </div>
                        </div>
                     @endif
                    --}}
                  </div><!-- /.col -->
               </div><!-- /.row -->
               <!-- payment -->
               <div class="space"></div>
                  <div class="panel @if ($order->Status == 1) panel-success @elseif($order->Status == 2) label-warning @else panel-danger @endif ">
                     <div class="panel-heading">
                        <div class="panel-title">
                           <span class="h4">Thông tin thanh toán:</span>
                             <span class="label @if ($order->Status == 1)label-success @elseif($order->Status == 2) label-warning @else label-danger @endif label-large">{{ $order->StatusText }}</span>
                        </div>
                     </div>
                     <div class="panel-body">
                        <ul class="list-unstyled">
                           <li><i class="ace-icon fa fa-caret-right blue"></i>Trạng thái : <b>{{ $order->StatusText }}</b></li>
                           <li><i class="ace-icon fa fa-caret-right blue"></i>Tổng phụ:<b>{{ number_format($order->OrderTotalDiscount) }}</b></li>
                            <li><i class="ace-icon fa fa-caret-right blue"></i>Discount:<b>{{ floatval($order->f_Discount != 0 ? $order->f_Discount : ($order->m_Discount != 0? $order->m_Discount: 0)) }}</b></li>
                           <li><i class="ace-icon fa fa-caret-right blue"></i>Ngày :<b>{{ date('d/m/Y', strtotime($order->OrderDate)) }}</b></li>
                        </ul>
                     </div>
                  </div>
               <div>
                     <div class="table-responsive">
                  <table id="simple-table" class="table table-bordered table-hover table-responsive">
                     <thead>
                     <tr class="">
                        <th class="center">
                           #
                        </th>
                        <th class="center">Sản phẩm</th>
                        <th class="center">Đơn vị tính</th>
{{--                        <th class="center">Chi nhánh</th>--}}
{{--                         <th class="center">Kho hàng</th>--}}
                        <th class="center">Đơn giá</th>
                        <th class="center">Số lượng</th>
                        <th class="center">% Chiết khấu</th>
                        <th class="center">Chiết khẩu</th>
                        <th class="center">Ghi chú</th>
                        <th class="center">Thành tiền</th>
                     </tr>
                     </thead>
                     <tbody>
                     @forelse($order->OrderDetail as $key=> $d_order)
                        <tr>
                           <td class="center">{{++$key}}</td>
                           <td class="center">
                              <a class="click_me" href="{{ route('product.show',$d_order->ProductId) }}">
                                  {{ $d_order->ProductCode }}/{{ $d_order->ProductName }}</a>
                           </td>
                           <td class="center">{{$d_order->Unit}}</td>
{{--                           <td class="center">{{$d_order->LocationId}}</td>--}}
{{--                            <td class="center">{{$d_order->StoreName}}</td>--}}
                           <td class="center">{{number_format($d_order->Price)}}</td>
                           <td class="center">{{$d_order->Qty}}</td>
                           <td class="center">{{$d_order->f_Discount}}</td>
                           <td class="center">{{number_format($d_order->m_Discount)}}</td>
                           <td class="center">{{$d_order->Description}}</td>
                           <td class="center">{{number_format($d_order->Price * $d_order->Qty)}}</td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="11">No Data</td>
                        </tr>
                     @endforelse
                     </tbody>
                  </table>
                  </div>
               </div>

               <div class="hr hr8 hr-double hr-dotted"></div>

               <div class="row">
                  <div class="col-sm-5 pull-right">
                     <h4 class="pull-right">
                        Tổng tiền :
                        <span class="red">{{ number_format($order->OrderTotalDiscount) }}</span>
                     </h4>
                  </div>
                  <div class="col-sm-7 pull-left"><b>Thông tin thêm:</b>
                     <p class="">
                        {{ $order->OrderCode }}
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
        </div>
    </div>
   <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h3 class="modal-title" id="myModalLabel">Thông tin khách hàng</h3>
               </div>
               <div class="modal-body">
                   <div id="cusInfoH"></div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
               </div>
           </div>
       </div>
   </div>

@endsection
@section('extra_js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#customerModal").on("show.bs.modal", function(e) {
                let link = $(e.relatedTarget);
                $(this).find("#cusInfoH").load(link.data("href"));
            });
        });
    </script>
@stop
