<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>datepicker.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>

		
<script type="text/javascript">
	
$(document).ready(function() {
  addItem();
});

function drawLineChart(id,from,to) {

chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline',
                //inverted: false,
                //width: 500,
                style: {
                    margin: '0 auto'
                }
            },
            title: {
                text: 'Atmosphere Temperature by Altitude'
            },
            subtitle: {
                text: 'According to the Standard Atmosphere Model'
            },
            xAxis: {
                reversed: false,
                title: {
                    enabled: true,
                    text: 'Tempeture'
                },
                labels: {
                    formatter: function() {
                        return this.value +'km';
                    }
                },
                maxPadding: 0.05,
                showLastLabel: true
            },
            yAxis: {
                title: {
                    text: 'Time'
                },
                labels: {
                    formatter: function() {
                        return this.value + '°';
                    }
                },
                lineWidth: 2
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +'°C'+ this.y +'Time';
                }
            },
            plotOptions: {
                spline: {
                    marker: {
                        enable: false
                    }
                }
            },
            series: [{
                name: 'Temperature',
                data: (function() {
	                var data = [];
	                var daten = getValues(id,from,to);
	                
	                for (var i=0,l = daten.length; i<l; i++)
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
drawLineChart(selObj.options[selIndex].value,'2013-01-08 00:00:00','2013-01-09 00:00:00');

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