@extends('layout.admin.index' )
@section('title')
   Doanh thu chi tiết CH/Revenue by Location
@stop
@section('extra_css')
@stop
@section('content')

	<div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-chart-bar orange" aria-hidden="true"></i>
            <h3 class="box-title">Doanh thu chi tiết CH/Revenue by Location</h3>
        </div>
        <div class="box-body">
            @include('admin.report._formSearch', ['type' => 'location'])
            
            @php $titleArr = ['Location_ID' => 'Chi Nhánh/Location', 'SoPhieu' => 'Số phiếu/OrderID', 'Ngay' => 'Ngày/Date',
            	'MaSanPham' => 'Mã sản phẩm/Product Code', 'TenSanPham' => 'Tên sản phẩm/ Product Name', 'SoLuong' => 'Số lượng/ Quantity',
            	'GiamGia' => 'Giảm giá/Discount', 'DoanhSo' => 'Doanh số/Revenue'
             ]; @endphp

           <div class="table-responsive">
              <table id="simple-table" class="table table-bordered table-hover table-responsive">
                 <thead>
                 <tr class="center">
                 	@if(count($data) > 0)
                     	@foreach($data as $k => $row)
                     		@php
                     			$thead = array_keys($row[0]);
                     			$totalArr = array_flip($thead);
                     			$lastElement = end($thead);
                     		@endphp
                     		<th>{{$titleArr[$lastElement]}}</th>
                     		@foreach($thead as $name)
                         		@if($name != $lastElement)
                         		@php $totalArr[$name] = 0; @endphp
                     			<th class="center">{{ $titleArr[$name]}}</th>
                     			@endif
                     		@endforeach
                 			@php break; @endphp
                 		@endforeach
                 	@endif
                 </tr>
                 </thead>
                 <tbody class="table_data">
                    @forelse($data as $key=> $rows)
                    	@php $sumArray = []; @endphp
                    	@foreach($rows as $i => $row)
                        <tr>
                        	@if($i == 0)
                        	<td class="text-center align-middle font-weight-bold" rowspan="{{count($rows)}}" style="font-weight: bold;">{{$key}}</td>
                        	@endif
                        	@foreach($thead as $k => $name)
                        		@if($name != $lastElement)
                        		@php 
                        			if(is_float($row[$name])) {
                        				if(!isset($sumArray[$name])) {
                        					$sumArray[$name] = 0;
                        				}
                            			$sumArray[$name]+=$row[$name];
                            		}
                            	@endphp
            					<td class="center">{{ !is_float($row[$name])? $row[$name] : number_format($row[$name])}}</td>
            					@endif
            				@endforeach
        				</tr>
        				@endforeach
        				<tr class="bg-info text-right text-danger" style="font-weight: bold;">
                    		<td class="text-uppercase center font-weight-bold">
                    			Tổng cộng/Sum
                    		</td>
                    		@foreach(array_slice($thead, 3) as $k => $name)
                    			@if($name != $lastElement)
                    				<td class="text-uppercase center" colspan="@if($k == 0) 4 @else 0 @endif">
                    				@if($k > 0)
                 					{{ number_format(isset($sumArray[$name]) ? $sumArray[$name] : 0)}}
                 					@endif
                 					</td>
                    			@endif
                     		@endforeach
                        </tr>
                 	@empty
                        <td colspan="7">Không có dữ liệu/ No data</td>
                    @endforelse
                 </tbody>
              </table>
           </div>

            <div class="form-group text-center">
                {{-- $data->appends(request()->except('page'))->links() --}}
            </div>
        </div>
    </div>
@endsection
@include('admin.report._js')