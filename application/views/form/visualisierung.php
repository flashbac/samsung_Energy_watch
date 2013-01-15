<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>epoch_classes.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>


		
<script type="text/javascript">
	
	var dp_cal1,dp_cal2;
	
$(document).ready(function() {
  addItem();
  dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
});

function drawLineChart(id,from,to) {
		// Create the chart
		var numberOfValues;
		var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+id);
		window.chart = new Highcharts.StockChart({
		    chart: {
		        renderTo: 'container'
		    },

		    rangeSelector: {
		        selected: 1
		    },

		    title: {
		        text: MeterDaten.Name
		    },
		    subtitle: {
                text: MeterDaten.Description
            },
            xAxis: {
                type: 'datetime',
				//maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    enabled: true,
                    text: 'Timestamp'
                }
            },
            yAxis: {
                title: {
                    text: MeterDaten.Unit
                },
                lineWidth: 2
            },
            legend: {
                enabled: false
            },
			tooltip: {
				shared: true
			}, 
            plotOptions: {
                spline: {
                     marker: {
						enabled: false,
						states: {
							hover: {
								enabled: true,
								radius: 5
							}
						}
					} 
                }
            },
		    series: [{
		    	
		        name: 'Value',
		        type: 'spline',
		    	tooltip: {
		    		valueDecimals: 2
		       },
		        data: (function() {
	                var data = [];
	                var daten = getValues(id,from,to,"<?php echo base_url(); ?>");
	                
	                for (var i=0,l = daten.length; i<l; i++)
	                {
	                	data.push({
	                            x: daten[i].TimeStamp,
	                            y: daten[i].Value
	                        });
	                 
	                }
	                numberOfValues = daten.length;
	                return data;
                })(),
                turboThreshold: numberOfValues,
		    }]
		});
	}



function addItem()
{
	var meter = getJson("<?php echo base_url(); ?>index.php/data/getMeter/1");
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
var timeVon = dp2dateTS(document.getElementById('datevon').value,'00:00:00');
var timeBis = dp2dateTS(document.getElementById('datebis').value,'23:59:59');
drawLineChart(selObj.options[selIndex].value,timeVon,timeBis);

}

</script>

<form name=myform ">
	<select name=mytextarea id="combo" >
	</select>
	<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	Datum: von
	<input type="text" id="datevon" value=""  />
	bis
	<input type="text" id="datebis" value="" />
</form>
		
<div id="container">

</div>