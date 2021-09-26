@extends('layout.admin.index' )
@section('title')
   Doanh thu trong tháng
@stop
@section('extra_css')
<link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@stop
@section('content')

	<div class="box box-info">
        <div class="box-header">
            <i class="ace-icon fa fa-chart-bar orange" aria-hidden="true"></i>
            <h3 class="box-title">Doanh thu trong tháng/Revenue by Month</h3>
        </div>
        <div class="box-body">
            @include('admin.report._formSearch', ['type' => 'month'])

           <div class="table-responsive">
              <table id="simple-table" class="table table-bordered table-hover table-responsive">
                 <thead>
                 <tr class="text-center">
                 	<th class="text-uppercase">Chi Nhánh / Location</th>
                 	@foreach($data as $d)
             		 <th>{{$d['ChiNhanh']}}</th>
                 	@endforeach
                 	
                 	@if(count($data) > 0)
                 		@php
                 			$thead = array_keys($data[0]);
                 			$totalArr = array_flip($thead);
                 			$lastElement = end($thead);
                 			$dayOfMonth = $data[0][$lastElement];
                 		@endphp
                 		{{--
                     	@foreach($thead as $k => $name)
                     		@if($name != $lastElement)
                     		@php $totalArr[$name] = 0; @endphp
                 			<th class="center">{{$name == 'ChiNhanh' ? 'Chi Nhánh / Location' : str_replace('T', 'Ngày ', $name)}}</th>
                 			@endif
                 		@endforeach
                 		--}}
                 		<th class="text-uppercase">Tổng cộng/Sum</th>
                 		{{--<th class="text-uppercase">Bình quân</th>--}}
                 	@endif
                 </tr>
                 </thead>
                 <tbody class="table_data">
                    @forelse($data as $key=> $rows)
                    @foreach($rows as $name => $v)
                    	@if($name == 'ChiNhanh' || $name == 'SoNgayTrongThang') @php continue; @endphp @endif
                    	
                    	@if ($key > 0)
                    		@php continue; @endphp
                    	@endif
                    <tr>
                    	                    	
                    	<td>{{ $name != 'SoNgayTrongThang' ? str_replace('T', 'Ngày/Day ', $name) : $name }}</td>
                    	
                    	@php $tt = 0; @endphp
                    	@for($i = 0; $i < count($data); $i++)
                    		@php $tt += $data[$i][$name]; 
                    			$mlastElement = end($data[$i]);
                    			
                    			if($mlastElement == count($data[$i]) - 3) {
                    				$tt = $data[$i][$name];
                    			}
                    		@endphp
                    		<td style="text-align: right;">{{ number_format($data[$i][$name]) }}</td>
                    	@endfor
                    	                   	
                    	<td style="text-align: right;">{{ number_format($tt)}}</td>
                    	{{--
                    	@php $total = 0;@endphp
                    	@foreach($thead as $k => $name)
                    		@if($name != $lastElement)
                    		@php if(is_float($rows[$name])) {
                    			$totalArr[$name] += $rows[$name];
                    		}
        					$total += is_float($rows[$name]) ? $rows[$name] : 0;
                    		@endphp
        					<td class="@if($k >0) text-right @else center @endif">{{ is_string($rows[$name]) ? $rows[$name] : number_format($rows[$name])}}</td>
        					@endif
        				@endforeach
        				<td class="text-right">
        					{{number_format($total)}}
        				</td>
        				<td class="text-right">{{number_format($total / $dayOfMonth)}}</td>
        				--}}
        				
    				</tr>
    				@endforeach
    				
    				@if ($key > 0)
                		@php continue; @endphp
                	@endif
    				<tr style="font-weight: bold" class="text-uppercase">
    					<td>Tổng / Total</td>
    					@php $total = 0;@endphp
    					@foreach($data as $ki => $rows)
    						@php $totall = 0;@endphp
    						@foreach($rows as $name => $v)
    							@if($name == 'ChiNhanh' || $name == 'SoNgayTrongThang') @php continue; @endphp @endif
    							@php $total += $v; $totall += $v; @endphp
    						@endforeach
    					<td style="text-align: right;">{{ number_format($totall) }}</td>
    					@endforeach
    					<td style="text-align: right;">{{ number_format($total) }}</td>
    				</tr>
    				<tr style="font-weight: bold" class="text-uppercase">
    					<td>Bình quân / AVERAGE</td>
    					@foreach($data as $ki => $rows)
    						@php $subtotal = 0;@endphp
    						@foreach($rows as $name => $v)
    							@if($name == 'ChiNhanh' || $name == 'SoNgayTrongThang') @php continue; @endphp @endif
    							@php $subtotal += $v; @endphp
    						@endforeach
    					<td style="text-align: right;">{{ number_format($subtotal / $dayOfMonth) }}</td>
    					@endforeach
    					<td style="text-align: right;">{{ number_format($total / $dayOfMonth) }}</td>
					</tr>
                 	@empty
                        <td colspan="15">Không có dữ liệu/ No data</td>
                    @endforelse
                    {{--
                    @if(count($data) > 0)
                	<tr class="bg-info text-right">
                		<th class="text-uppercase center">
                			Tổng cộng
                		</th>
                		@php array_shift($thead); array_pop($thead);@endphp
                		@foreach($thead as $k => $name)
                			<th class="text-uppercase center">
                			@if($name != $lastElement)
             					{{number_format($totalArr[$name] ? $totalArr[$name] : 0)}}
                			@endif
                 			</th>
                 		@endforeach
                 		<th class="text-right">
                 			@php array_pop($totalArr); @endphp
                 			{{number_format(array_sum($totalArr))}}
                 		</th>
                 		<th class="text-right">{{number_format(array_sum($totalArr) / $dayOfMonth)}}</th>
                    </tr>
                    @endif
                    --}}
                 </tbody>
              </table>
           </div>

            <div class="form-group text-center">
                {{-- $data->appends(request()->except('page'))->links() --}}
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.vi.min.js" charset="UTF-8"></script>
    
    <script>
        $(document).ready(function () {
        	$('#dpMonths').datepicker({
        		format: "mm-yyyy",
        		autoclose: true,
        		language: "vi",
        	    viewMode: "months", 
        	    minViewMode: "months"
        	});
        });
    </script>
@stop
