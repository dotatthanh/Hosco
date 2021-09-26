@extends('layout.admin.index' )
@section('title')
   Bảng điều khiển
@stop
@section('extra_css')
@stop
@section('content')
    {{--
   <div class="row">
      <div class="col-sm-12">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-signal" aria-hidden="true"></i>
                  <h3 class="box-title">Đơn hàng <i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" data-container="body" data-toggle="popover" data-placement="auto bottom" data-content="Đang chờ thanh toán cho Bán hàng. <br/><small class='text-muted'>Dựa trên thời hạn thanh toán hóa đơn. <br/> Hiển thị các khoản thanh toán sẽ được nhận trong 7 ngày hoặc ít hơn.</small>" data-html="true" data-trigger="hover"></i></h3>
              </div>

              <div class="box-body">
                  <div class="infobox-container">
                      <div class="infobox infobox-black">
                          <div class="infobox-icon">
                              <i class="ace-icon fa fa-shopping-basket"></i>
                          </div>

                          <div class="infobox-data">
                              <span class="infobox-data-number">{{ $menu_count['orders']  }}</span>
                              <div class="infobox-content">All Orders</div>
                          </div>

                          <div class="stat stat-success"></div>
                      </div>

                      <div class="infobox infobox-orange">
                          <div class="infobox-icon">
                              <i class="ace-icon fa fa-circle-o"></i>
                          </div>
                          <div class="infobox-data">
                              <span class="infobox-data-number">{{ $order_sent }}</span>
                              <div class="infobox-content">Sent</div>
                          </div>
                          <div class="badge badge-success">
                            <i class="ace-icon fa fa-arrow-up"></i>
                          </div>
                      </div>


                      <div class="infobox infobox-red">
                          <div class="infobox-icon">
                              <i class="ace-icon fa fa-newspaper-o"></i>
                          </div>

                          <div class="infobox-data">
                              <span class="infobox-data-number">{{$order_news}}</span>
                              <div class="infobox-content">New Orders</div>
                          </div>
                      </div>

                      <div class="infobox infobox-purple2">
                          <div class="infobox-icon">
                              <i class="ace-icon fa fa-thumbs-o-down"></i>
                          </div>

                          <div class="infobox-data">
                              <span class="infobox-data-number">{{ $order_not_complete }}</span>
                              <div class="infobox-content">Not Complete </div>
                          </div>
                          <div class="stat stat-important">4%</div>
                      </div>

                      <div class="space-10"></div>
                  </div>
              </div>
          </div>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-6">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-credit-card orange" aria-hidden="true"></i>
                  <h3 class="box-title">Thanh toán</h3>
              </div>

              <div class="box-body">
                  <div class="widget-main no-padding">
                      <div class="infobox-container">

                          <div class="infobox infobox-blue">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-money"></i>
                              </div>
                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $menu_count['payments'] }}</span>
                                  <div class="infobox-content">All</div>
                              </div>
                              <div class="badge badge-success">
                                  <i class="ace-icon fa fa-arrow-up"></i>
                              </div>
                          </div>
                          <div class="infobox infobox-orange">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-credit-card"></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{$payment_week}}</span>
                                  <div class="infobox-content">This Week</div>
                              </div>
                              <div class="stat stat-success"></div>
                          </div>

                          <div class="infobox infobox-green">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-paypal"></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $payment_success }}</span>
                                  <div class="infobox-content">Successful Payments</div>
                              </div>
                              <div class="stat stat-important">4%</div>
                          </div>

                          <div class="infobox infobox-red">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-newspaper-o"></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $payment_failed }}</span>
                                  <div class="infobox-content">Invalid Payments</div>
                              </div>
                          </div>

                          <div class="space-6"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- USERS -->
      <div class="col-sm-6">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-users orange" aria-hidden="true"></i>
                  <h3 class="box-title">Người dùng</h3>
              </div>

              <div class="box-body">
                  <div class="widget-main no-padding">
                      <div class="clearfix">
                          <div class="grid3">
                        <span class="grey">
                           <i class="ace-icon fa fa-user fa-2x blue"></i>
                            Users</span>
                              <h4 class="bigger pull-right">{{ $menu_count['users'] }}</h4>
                          </div>

                          <div class="grid3">
                        <span class="grey">
                           <i class="ace-icon fa fa-user-times fa-2x purple"></i>
                           Employees
                        </span>
                              <h4 class="bigger pull-right">{{ $employees }}</h4>
                          </div>

                          <div class="grid3">
                        <span class="grey"><i class="ace-icon fa fa-user-plus fa-2x red"></i>
                           New Users
                        </span>
                              <h4 class="bigger pull-right">{{ $new_users }}</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div><!-- /.col -->

   </div>
   <div class="space-10"></div>
   <div class="row">
      <div class="col-sm-12">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-list orange" aria-hidden="true"></i>
                  <h3 class="box-title">Sản phẩm</h3>
              </div>

              <div class="box-body">
                  <div class="widget-main no-padding">
                      <div class="infobox-container">
                          <div class="infobox infobox-blue">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-list "></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number blue">{{ $menu_count['products'] }}</span>
                                  <div class="infobox-content">All Products</div>
                              </div>
                              <div class="stat stat-success"></div>
                          </div>

                          <div class="infobox infobox-blue">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-thumbs-up"></i>
                              </div>
                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $available_products }}</span>
                                  <div class="infobox-content">Available Products</div>
                              </div>
                              <div class="badge badge-success">
                                <i class="ace-icon fa fa-arrow-up"></i>
                              </div>
                          </div>

                          <div class="infobox infobox-blue">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-circle"></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $discounted_products }}</span>
                                  <div class="infobox-content">Discounted Products</div>
                              </div>
                              <div class="stat stat-important">4%</div>
                          </div>

                          <div class="infobox infobox-red">
                              <div class="infobox-icon">
                                  <i class="ace-icon fa fa-newspaper-o"></i>
                              </div>

                              <div class="infobox-data">
                                  <span class="infobox-data-number">{{ $product_news }}</span>
                                  <div class="infobox-content">last week products</div>
                              </div>
                          </div>

                          <div class="space-6"></div>

                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-credit-card orange" aria-hidden="true"></i>
                  <h3 class="box-title">Popular Products</h3>
              </div>

              <div class="box-body">
                  <div class="widget-main no-padding">
                      <table class="table table-bordered table-striped">
                          <thead class="thin-border-bottom">
                          <tr>
                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Image
                              </th>
                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Product Name
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Product Code
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Qty
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>price
                              </th>
                          </tr>
                          </thead>

                          <tbody>
                          @forelse($popular_products as $product)
                              <tr>
                                  <td>
                                      <img src="{{ $product->Picture}}" class="img-thumbnail" alt="{{  $product->ProductName }}" style="width: 50px;"/>
                                  </td>
                                  <td>
                                      <a href="{{ route('product.show',$product->ProductId) }}">{{ $product->ProductName }}</a>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->ProductCode }}</b>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->Qty }}</b>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->TotalPrice }}</b>
                                  </td>
                              </tr>
                          @empty
                              <tr>
                                  <td colspan="3">No Data</td>
                              </tr>
                          @endforelse

                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>

      <div class="col-sm-6">
          <div class="box box-info">
              <div class="box-header">
                  <i class="ace-icon fa fa-list orange" aria-hidden="true"></i>
                  <h3 class="box-title">Top Order Products</h3>
              </div>

              <div class="box-body">
                  <div class="widget-main no-padding">
                      <table class="table table-bordered table-striped">
                          <thead class="thin-border-bottom">
                          <tr>
                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Image
                              </th>
                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Product Name
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Product Code
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>Qty
                              </th>

                              <th>
                                  <i class="ace-icon fa fa-caret-right blue"></i>price
                              </th>
                          </tr>
                          </thead>

                          <tbody>
                          @forelse($top_order_products as $product)
                              <tr>
                                  <td>
                                      <img src="{{ $product->Picture}}" class="img-thumbnail" alt="{{  $product->ProductName }}" style="width: 50px;"/>
                                  </td>
                                  <td>
                                      <a href="{{ route('product.show',$product->ProductId) }}">{{ $product->ProductName }}</a>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->ProductCode }}</b>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->Qty }}</b>
                                  </td>
                                  <td>
                                      <b class="gray">{{ $product->TotalPrice }}</b>
                                  </td>
                              </tr>
                          @empty
                              <tr>
                                  <td colspan="3">No Data</td>
                              </tr>
                          @endforelse

                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>

   </div>
   --}}

    <div class="box box-info">
        <div class="box-header">
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h2 class="text-bold" style="font-weight: bold">Chào mừng đến với PHANMEMBANHANG.COM</h2>
                    <p class="text-black" style="font-size: 14px"><b>PHANMEMBANHANG.COM</b> - Là công cụ quản lý shop, giúp bạn quản lý sản phẩm, khách hàng - nhà cung cấp,
                        theo dõi đơn hàng, công nợ - tài chính, chăm sóc khách hàng và đánh giá hoạt động của shop.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 col-sm-offset-1">
                    <p><b>HOTLINE: 1900 6129</b></p>
                    <p><b>BÁN HÀNG DỄ DÀNG</b></p>
                    <ul class="nav">
                        <li><i class="fa fa-">*</i> <b>Quản lý hàng hóa không giới hạn</b></li>
                        <li><i class="fa fa-">*</i> <b>Theo dõi hàng tồn kho</b></li>
                        <li><i class="fa fa-">*</i> <b>Chăm sóc khách hàng</b></li>
                        <li><i class="fa fa-">*</i> <b>Kiểm soát kinh doanh mọi lúc, mọi nơi</b></li>
                        <li><i class="fa fa-">*</i> <b>Tích hợp với mọi thiết bị phần cứng</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
