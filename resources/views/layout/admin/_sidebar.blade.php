<div id="sidebar" class="sidebar responsive ace-save-state">
   <script type="text/javascript">
       try {
           ace.settings.loadState('sidebar')
       } catch (e) {
       }
   </script>

{{--<div class="sidebar-shortcuts" id="sidebar-shortcuts">
   <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
      <button class="btn btn-success">
         <i class="ace-icon fa fa-signal"></i>
      </button>

      <button class="btn btn-info">
         <i class="ace-icon fa fa-pencil"></i>
      </button>

      <button class="btn btn-warning">
         <i class="ace-icon fa fa-users"></i>
      </button>

      <button class="btn btn-danger">
         <i class="ace-icon fa fa-cogs"></i>
      </button>
   </div>

   <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
      <span class="btn btn-success"></span>

      <span class="btn btn-info"></span>

      <span class="btn btn-warning"></span>

      <span class="btn btn-danger"></span>
   </div>
</div>--}}
<!-- /.sidebar-shortcuts -->

   <ul class="nav nav-list">
      <li class="">
         <a class="click_me" data-path="/admin/dashboard" href="{{ route('admin.dashboard') }}">
            <i class="menu-icon fa fa-tachometer-alt"></i>
            <span class="menu-text"> Bảng điều khiển </span>
         </a>

         <b class="arrow"></b>
      </li>
      <li class="@if (strpos(\Request::url(), 'admin/customer' ) !=false) open @endif">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-user"></i>
            <span class="menu-text"> Tin tức-thông Báo </span>
            <span class="badge badge-primary"></span>
            <span class="arrow fa fa-angle-left"></span>
        </a>
        <b class="arrow"></b>
        <ul class="submenu nav-hide" style="display: @if (strpos(\Request::url(), 'admin/customer' ) !=false) block @else none @endif;;">

            <li class="">
                <a class="click_me" data-pjax="" href="{{ route('news.index') }}" data-title="Tin tức"
                    data-pjax-state="">
                    <i class="menu-iconn fa fa-list"></i>
                    Danh sách tin tức
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a class="click_me" data-pjax="" href="{{ route('news.create') }}"
                    data-title="Khách hàng" data-pjax-state="">
                    <i class="menu-iconn fa fa-plus-circle"></i>
                    Thêm mới tin tức
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a class="click_me" data-pjax="" href="{{ route('notification.index') }}"
                    data-title="Khách hàng" data-pjax-state="">
                    <i class="menu-iconn fa fa-list"></i>
                    Danh sách thông báo
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a class="click_me" data-pjax="" href="{{ route('notification.create') }}"
                    data-title="Khách hàng" data-pjax-state="">
                    <i class="menu-iconn fa fa-plus-circle"></i>
                    Tạo thông báo
                </a>
                <b class="arrow"></b>
            </li>
            <li class="">
                <a class="click_me" data-pjax="" href="{{ route('CategoryNew.index') }}"
                    data-title="Khách hàng" data-pjax-state="">
                    <i class="menu-iconn fa fa-list"></i>
                    Quán lý danh mục
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>
       @if(isFullRoleForTenant())
           @if(isRead(env('CUSTOMER_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/customer') != false) open @endif">
                   <a href="#" class="dropdown-toggle">
                       <i class="menu-icon fa fa-user"></i>
                       <span class="menu-text"> Khách hàng </span>
                       <span class="badge badge-primary"></span>
                       <span class="arrow fa fa-angle-left"></span>
                   </a>
                   <b class="arrow"></b>
                   <ul class="submenu nav-hide" style="display: @if(strpos(\Request::url(), 'admin/customer') != false) block @else none @endif;;">

                       <li class="">
                           <a class="click_me" data-pjax="" href="{{ route('customer.index') }}" data-title="Khách hàng" data-pjax-state="">
                               <i class="menu-iconn fa fa-list"></i>
                               Danh sách khách hàng
                           </a>
                           <b class="arrow"></b>
                       </li>
                       @if(isCreate(env('CUSTOMER_ROLE_CODE')))
                           <li class="">
                               <a class="click_me" data-pjax="" href="{{ route('customer.create') }}" data-title="Khách hàng" data-pjax-state="">
                                   <i class="menu-iconn fa fa-plus-circle"></i>
                                   Thêm khách hàng
                               </a>
                               <b class="arrow"></b>
                           </li>
                       @endif
                   </ul>
               </li>
           @endif
           @if(isRead(env('PRODUCT_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/product') != false) open @endif">
                   <a href="#" class="dropdown-toggle">
                       <i class="menu-icon fa fa-cubes"></i>
                       <span class="menu-text"> Sản phẩm </span>
                       <span class="badge badge-primary"></span>
                       <span class="arrow fa fa-angle-left"></span>
                   </a>
                   <b class="arrow"></b>
                   <ul class="submenu nav-hide" style="display: @if(strpos(\Request::url(), 'admin/product') != false) block @else none @endif;">
                       <li class="">
                           <a class="click_me" data-pjax="" href="{{ route('product.index') }}" data-title="Sản phẩm" data-pjax-state="">
                               <i class="menu-iconn fa fa-list"></i>
                               Danh sách sản phẩm
                           </a>
                           <b class="arrow"></b>
                       </li>
                       @if(isCreate(env('PRODUCT_ROLE_CODE')))
                           <li class="">
                               <a class="click_me" data-pjax="" href="{{ route('product.create') }}" data-title="Sản phẩm" data-pjax-state="">
                                   <i class="menu-iconn fa fa-plus-circle"></i>
                                   Thêm sản phẩm
                               </a>
                               <b class="arrow"></b>
                           </li>
                       @endif
                   </ul>
               </li>
           @endif

           @if(isRead(env('ORDER_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/orders/purchase') != false) open @endif">
                   <a href="{{ route('order.index', ['type' => 'purchase']) }}">
                       <i class="menu-icon fa fa-arrow-circle-down"></i>
                       <span class="menu-text"> Đơn đặt hàng bán </span>
                   </a>
               </li>
           @endif
           
           <li class="@if(strpos(\Request::url(), 'admin/orders/delivery') != false) open @endif">
             <a href="{{ route('order.index', ['type' => 'delivery']) }}">
               <i class="menu-icon fa fa-arrow-circle-down"></i>
               <span class="menu-text"> Đơn giao hàng </span>
             </a>
           </li>

           @if(isRead(env('SALES_ORDER_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/orders/sel') != false) open @endif">
                   <a href="{{ route('order.index', ['type' => 'sel']) }}">
                       <i class="menu-icon fa fa-arrow-circle-up"></i>
                       <span class="menu-text"> Đơn bán hàng</span>
                   </a>
               </li>
           @endif

           @if(isRead(env('RECEIPTS_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/reports/income') != false) open @endif">
                   <a href="{{ route('report.index', ['type' => 'income']) }}">
                       <i class="menu-icon fa fa-database"></i>
                       <span class="menu-text"> Phiếu thu tiền </span>
                   </a>
               </li>
           @endif

           @if(isRead(env('PAYMENT_ROLE_CODE')))
               <li class="@if(strpos(\Request::url(), 'admin/reports/outcome') != false) open @endif">
                   <a href="{{ route('report.index',  ['type' => 'outcome']) }}">
                       <i class="menu-icon fa fa-minus-circle"></i>
                       <span class="menu-text"> Phiếu chi tiền</span>
                   </a>
               </li>
           @endif
       @endif

       <li class="@if(strpos(\Request::url(), 'admin/reports/month') != false || strpos(\Request::url(), 'admin/reports/monthyear') != false
            || strpos(\Request::url(), 'admin/reports/year') != false || strpos(\Request::url(), 'admin/reports/allyear') != false) open @endif">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-chart-bar"></i>
                <span class="menu-text"> Báo cáo/Reports </span>
                <span class="badge badge-primary"></span>
                <span class="arrow fa fa-angle-left"></span>
            </a>
            <b class="arrow"></b>
            @php
                $isShow = false;
                if(strpos(\Request::url(), 'admin/reports/month') != false || strpos(\Request::url(), 'admin/reports/monthyear') != false
                || strpos(\Request::url(), 'admin/reports/year') != false || strpos(\Request::url(), 'admin/reports/allyear') != false 
                || strpos(\Request::url(), 'admin/reports/customer-group') != false || strpos(\Request::url(), 'admin/reports/location') != false) 
                {
                    $isShow = true;
                }
                    
            @endphp
            
            <ul class="submenu nav-hide" style="display: @if($isShow) block @else none @endif;;">

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'month']) }}" data-title="Báo cáo tháng" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        Doanh thu trong tháng/Revenue by Month
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'monthyear']) }}" data-title="Báo cáo năm" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        So sánh doanh thu trong năm/Compare revenue in year
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'year']) }}" data-title="Báo cáo năm" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        So sánh doanh thu các năm/Compare revenue by year
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'allyear']) }}" data-title="Báo cáo năm" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        Doanh thu theo năm/Revenue by year
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'customer-group']) }}" data-title="Báo cáo năm" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        Doanh thu theo group/Revenue by group
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a class="click_me" data-pjax="" href="{{ route('report.index', ['type' => 'location']) }}" data-title="Báo cáo năm" data-pjax-state="">
                        <i class="menu-iconn fa fa-chart"></i>
                        Doanh thu chi tiết CH/Revenue by Location
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        

      {{--@endcan--}}

      @can('gift-list')
         @include('layout.admin._menu',
         ['menu_name' => 'Gift cards', 'number' => 'gift_cards','gate' => 'gift' ,'icon' => 'fa-gift', 'route_create' => 'giftCard.create' ,'route_list' => 'giftCard.index'])
      @endcan

      @can('role-list')
         <li class="">
            <a class="click_me" href="{{ route('settings.index') }}">
               <i class="menu-icon fa fa-cogs"></i>
               <span class="menu-text"> Settings </span>
            </a>

            <b class="arrow"></b>
         </li>
      @endcan

   </ul>
   <!-- /.nav-list -->

   <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
      <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
         data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
   </div>
</div>


