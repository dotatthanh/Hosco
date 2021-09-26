@extends('layout.admin.index' ) 
@section('title') So sánh doanh thu trong năm/Compare revenue in year @stop
@section('extra_css') 
<link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@stop 
@section('content')

<div class="box box-info">
	<div class="box-header">
		<i class="ace-icon fa fa-chart-bar orange" aria-hidden="true"></i>
		<h3 class="box-title">So sánh doanh thu trong năm/Compare revenue in year</h3>
	</div>
	<div class="box-body">
		@include('admin.report._formSearch', ['type' => 'monthyear'])
		
		@if(count($data) > 0)

		<div id="curve_chart" style="width: 100%; height: 500px"></div>
		

		<script type="text/javascript"
			src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">

			google.charts.load('current', {'packages':['corechart', 'bar']});
     	  	google.charts.setOnLoadCallback(drawChart);

	      	function drawChart() {
    	        var data = google.visualization.arrayToDataTable(({!! json_encode(convertDataToChartForm($data)) !!}));
    
    	        var options = {
    	          title: 'So sánh doanh thu trong năm/Compare revenue in year',
    	          titlePosition: 'out',
	        	  titleTextStyle: {
	        	      bold: true,
	        	      italic: false,
	        	      fontSize: 18,
	        	      color: 'gray',
	        	      align: 'center'
	        	  },
    	          curveType: 'function',
    	          legend: { position: 'bottom' },
    	          bar: { groupWidth: '75%' },
    	        };
    
    	        var chart = new google.visualization.ColumnChart(document.getElementById('curve_chart'));
    
    	        chart.draw(data, options);
    	    }
        </script>
        @endif
	</div>
</div>
@endsection

@section('extra_js')
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.vi.min.js" charset="UTF-8"></script>
    
    <script>
        $(document).ready(function () {
        	$('#dpMonths').datepicker({
        		format: "yyyy",
        		autoclose: true,
        		language: "vi",
        	    viewMode: "years", 
        	    minViewMode: "years"
        	});
        });
    </script>
@stop
