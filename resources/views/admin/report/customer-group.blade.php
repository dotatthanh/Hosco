@extends('layout.admin.index' ) @section('title') Revenue by groups @stop
@section('extra_css') @stop @section('content')

<div class="box box-info">
	<div class="box-header">
		<i class="ace-icon fa fa-chart-bar orange" aria-hidden="true"></i>
		<h3 class="box-title">Doanh thu theo group/Revenue by group</h3>
	</div>
	<div class="box-body">

		<div id="curve_chart" style="width: 100%; height: 500px"></div>
		
		<script type="text/javascript"
			src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">

			google.charts.load('current', {'packages':['corechart', 'bar']});
     	  	google.charts.setOnLoadCallback(drawChart);

	      	function drawChart() {
    	        var data = google.visualization.arrayToDataTable(({!! json_encode(convertDataToChartForm($data, 'circle')) !!}));
    
    	        var options = {
    	          title: 'Doanh thu theo group/Revenue by group',
    	          titlePosition: 'out',
	        	  titleTextStyle: {
	        	      bold: true,
	        	      italic: false,
	        	      fontSize: 18,
	        	      color: 'gray'
	        	  },
    	          curveType: 'function',
    	          legend: { position: 'bottom' },
    	          bar: { groupWidth: '75%' },
    	        };
    
    	        var chart = new google.visualization.PieChart(document.getElementById('curve_chart'));
    
    	        chart.draw(data, options);
    	    }
        </script>
	</div>
</div>
@endsection

@section('extra_js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function () {
        	        	
            $('input[name="date"]').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'DD-MM-YYYY',
                },
            }).on('hide.daterangepicker', function (ev, picker) {
          	  $('.table-condensed tbody tr:nth-child(2) td').click();
        	  alert(picker.startDate.format('MM/YYYY'));
        	});
        });
    </script>
@stop
