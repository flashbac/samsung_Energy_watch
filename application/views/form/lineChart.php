<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>

<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>



		
<script type="text/javascript">

$(function() {
		// Create the chart
		window.chart = new Highcharts.StockChart({
		    chart: {
		        renderTo: 'container'
		    },

		    rangeSelector: {
		        selected: 1
		    },

		    title: {
		        text: 'AAPL Stock Price'
		    },
		    
		    series: [{
		        name: 'AAPL Stock Price',
		        type: 'spline',
		    	tooltip: {
		    		valueDecimals: 2
		       },
		        data: (function() {
	                var data = [];
	                var daten = getValues(15,'2013-01-06 00:00:00','2013-01-07 00:00:00');
	                
	                for (var i in daten)
	                {
	                	data.push({
	                            x: daten[i].TimeStamp,
	                            y: daten[i].Value
	                        });
	                }
	                return data;
                })()	    
		    }]
		});
	});


</script>
		
<div id="container" style="height: 500px"></div>