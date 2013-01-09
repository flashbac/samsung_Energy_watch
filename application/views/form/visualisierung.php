<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>datepicker.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>


		
<script type="text/javascript">
	
$(document).ready(function() {
  addItem();
});

function drawLineChart(id,from,to) {
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
	                var daten = getValues(id,from,to);
	                
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
	}



function addItem()
{
	var meter = getJson("samsung_Energy_watch/index.php/data/getMeter/1");
	var element4 = document.getElementById("combo");
	
	//for (var i in meter)
	for (var i=0,l = meter.length; i<l; i++)
	{
	 	var option1 = document.createElement("option");
	 	option1.value=meter[i].ID;
	 	option1.innerHTML=meter[i].Name;
	 	element4.options.add(option1);
 	} 	
}

function drawChart(){
var selObj = document.getElementById('combo');
var selIndex = selObj.selectedIndex;
drawLineChart(selObj.options[selIndex].value,'2013-01-06 00:00:00','2013-01-07 00:00:00');

}


Date.firstDayOfWeek = 0;
Date.format = 'yyyy/mm/dd';
$(function() {
        $( "#datebis" ).datepicker();
		$( "#datevon" ).datepicker();
    });

</script>

<form name=myform ">
	<select name=mytextarea id="combo" >
	</select>
	<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	<input type="text" id="datevon" value="" />
	<input type="text" id="datebis" value="" />
</form>
		
<div id="container">

</div>