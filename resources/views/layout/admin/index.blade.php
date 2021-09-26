<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layout.admin._header')
<body class="no-skin">
<!-- navBar -->
@include('layout.admin._navbar')
<!-- /navbar -->

<div class="main-container ace-save-state " id="main-container">
   <script type="text/javascript">
       try {
           ace.settings.loadState('main-container')
       } catch (e) {
       }
   </script>

   <!-- MENU -->
@include('layout.admin._sidebar')
<!-- /MENU -->
   <div class="main-content">
      <div class="main-content-inner">
         <div class="page-content">
             {{--
            <div class="ace-settings-container" id="ace-settings-container">
               <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                  <i class="ace-icon fa fa-cog bigger-130"></i>
               </div>
               <div class="ace-settings-box clearfix" id="ace-settings-box">
                  <div class="pull-left width-50">
                     <div class="ace-settings-item">
                        <div class="pull-left">
                           <select id="skin-colorpicker" class="hide">
                              <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                              <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                              <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                              <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                           </select>
                        </div>
                        <span>&nbsp; Choose Skin</span>
                     </div>


                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar"
                               autocomplete="off"/>
                        <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                     </div>


                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off"/>
                        <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                     </div>

                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container"
                               autocomplete="off"/>
                        <label class="lbl" for="ace-settings-add-container">
                           Inside
                           <b>.container</b>
                        </label>
                     </div>
                  </div><!-- /.pull-left -->

                  <div class="pull-left width-50">
                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off"/>
                        <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                     </div>

                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off"/>
                        <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                     </div>

                     <div class="ace-settings-item">
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight"
                               autocomplete="off"/>
                        <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                     </div>
                  </div><!-- /.pull-left -->
               </div>
               <!-- /.ace-settings-box -->
            </div>
            --}}
            <!-- /.ace-settings-container -->
            <div class="row">
               <div id="content-load"  class="col-sm-12 col-lg-12 col-xs-12 col-xl-12">
                  <!-- PAGE CONTENT BEGINS -->
               @yield('content')
               <!-- PAGE CONTENT ENDS -->
               </div><!-- /.col -->
            </div>
            <!-- /.row -->
         </div>
         <!-- /.page-content -->
      </div>
   </div>
   <!-- /.main-content -->
</div>


<div class="footer">
   <div class="footer-inner">
      <div class="footer-content">
          <span class="blue bolder">Phần mềm bán hàng Master Pro | Hotline: 09099 34689 | Copyright © 2020 All rights reserved.</span>
      </div>
   </div>
    <style>
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left:-5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left:35%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
   <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>

<!-- /.main-container -->
@include('sweetalert::alert')
<script src="{{ asset('admin-assets/js/admin-app.js')}}"></script>
<script src="{{ asset('admin-assets/js/myCodes.js')}}"></script>


<!-- ace scripts -->
@if (env('APP_AJAX'))
   <!-- LOAD PJAX -->
<script src="{{ asset('js/pjax/pjax.min.js') }}"></script>
   <!-- script for load page on AJAX-->
   <script>
       var pjax = new Pjax({
           elements: ".click_me",
           selectors: ["title", "#extra_css", "#content-load", "#extra_js",],
           cacheBust : false,
           timeout: false ,
       });
   </script>
@endif
<!-- END script for load page on ajax-->

<!-- SEARCH SCRIPT -->
<script type="text/javascript">
    jQuery(document).on('submit', '#form-search', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var form_data = new FormData(this);
        $.ajax({
            url: url,
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $(".preview").show();
            }, success: function (data) {
                if (data.html == " ") {
                    $('.preview').html("No more records found");
                    return;
                }
                $(".table_data").empty().append(data.html);
                $('.preview').hide();
            }, error: function () {
                alert('error');
                $('.preview').hide();
            }
        })
    });

</script>
<!-- /SEARCH SCRIPT -->

<!-- BEGIN EXTRA JS-->
<div id="extra_js">
   @yield('extra_js')
</div>
<!-- END EXTRA JS-->
</body>
</html>

