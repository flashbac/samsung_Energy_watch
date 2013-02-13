<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>epoch_classes.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>

		
<script type="text/javascript">

		var dp_cal1,dp_cal2;
	
$(document).ready(function() {
  //addItem();
  dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
});

var chart;


function drawLineChart(id,from,to) {
var numberOfValues = 0;
var arbeit = 0;

$("#container").append('<p><img src="<?php echo base_url(); ?>/img/ajax-loader.gif" alt="Loading"></p>');

	function MeterValues(id,from,to){
		var series = new Array();
		for (var i=0;i< id.length; i++)
		{
			var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+id[i]);
			series.push({
		     	name: MeterDaten.Name+" ("+MeterDaten.Unit+")",
		     	
		        data: (function() {
		            var data = [];
		            var daten = getValues(id[i],from,to,"<?php echo base_url(); ?>");
		            for (var k=0,l = daten.length; k<l; k++)
		            {
		            	if (id[i] ==9)
		            	{
		            		arbeit+=daten[k].Value/12;
		            	}
			          	data.push({
				            x: daten[k].TimeStamp,
			    	        y: daten[k].Value
			            });
			        }
			        numberOfValues += daten.length;
			        return data;
		        })(),
		        turboThreshold: numberOfValues,
			});
		}
		//alert(numberOfValues);
		return series;
	};
	
	chart = new Highcharts.StockChart({
		chart: {
	    	renderTo: 'container',
	        type: 'spline',
	        //inverted: false,
	        //width: 500,
	        style: {
	        	margin: '0 auto'
	        }
	    },
	    
		rangeSelector: {
			selected: 0,
		    enabled: false
		},
	    title: {
	    	text: "Gesamtübersicht"
	    },
	    subtitle: {
	    	text: ""
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
	        	text: ""
	        },
	        lineWidth: 2
	    },
	    legend: {
	    	enabled: true,
	    	align: 'right',
        	borderColor: 'black',
        	borderWidth: 2,
	    	layout: 'vertical',
	    	verticalAlign: 'top',
	    	y: 25,
	    	shadow: true
	    },
		tooltip: {
			shared: true
		},
		credits: {
            enabled: false
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
        series: MeterValues(id,from,to)
	});
	
	var legegndx = chart.legend.group.translateX;
	var pRx = legegndx; //chart.chartWidth - 210;
    var pRy = 250
    // chart.renderer.rect(pRx, pRy, 200, 120, 5)
        // .attr({
            // 'stroke-width': 2,
            // stroke: 'black',
            // fill: 'white',
            // zIndex: 3
        // })
        // .add();

    var Mma = getJson("<?php echo base_url(); ?>index.php/data/getAreaValuesmma/"+"9"+"/"+from+"/"+to);
    var max = Mma[0].Max;
    var min = Mma[0].Min;
    var avg = Mma[0].Avg;
    chart.renderer.label('Gesamtverbrauch: <br>Max: '+max+' kW<br>Min: '+min+' kW<br>Durchschnitt: '+ runde(avg,3)+' kW<br>Arbeit: '+ runde(arbeit,3) +' kW/h', pRx+5, pRy-5)
    	.attr({
        	//fill: colors[0],
            stroke: 'black',
            'stroke-width': 2,
            padding: 5,
            r: 5
        })
        .css({
        	color: 'black',
            width: '200px'
        })
        .add()

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
	var ID = new Array();
	
	var meter = getJson("<?php echo base_url(); ?>index.php/data/getMeter/1");
	var element4 = document.getElementById("combo");
	
	for (var i=0,l = meter.length; i<l; i++)
	{
	 	ID.push(meter[i].ID);
 	} 	
	
	var timeVon = dp2dateTS(document.getElementById('datevon').value,'00:00:00');
    var timeBis = dp2dateTS(document.getElementById('datebis').value,'23:59:59');
	drawLineChart(ID,timeVon,timeBis);
}

var anzahl = 1;
function addMeterInView()
{
	var meter = getJson("<?php echo base_url(); ?>index.php/data/getMeter/1");
	
	$("#config").append('<form id="f'+ (anzahl) +'" class="FormArray">');
	$("#f"+anzahl).append('<select name=mytextarea" id="combo' + anzahl + '" ></select>');
	for (var i=0,l = meter.length; i<l; i++)
	{
		$("#combo"+anzahl).append('<option value="'+meter[i].ID+'">'+meter[i].Name+'</option>')
	}
	$("#f"+anzahl).append('Datum: von');
	var now = new Date();
	var nowstr = now.getFullYear()+"/"+now.getMonth()+"/"+now.getDay();
	$("#f"+anzahl).append('<input type="text" id="datevon'+anzahl+'" value="'+nowstr+'"  />');
	$("#f"+anzahl).append('Datum: bis');
	$("#f"+anzahl).append('<input type="text" id="datebis'+anzahl+'"  value="'+nowstr+'"  />');
	new Epoch('epoch_popup','popup',document.getElementById('datevon'+anzahl));
	new Epoch('epoch_popup','popup',document.getElementById('datebis'+anzahl));
	$("#f"+anzahl).append('<input type="button" value="-" onclick="delmeter('+anzahl+')" />');
	anzahl++;
}

function delmeter(id)
{
	$("#f"+id).remove();
	
}

</script>
<div id="config">
	<form name="hinzufügen">
			<!-- <input type="button" name="Hinzufuegen" value="Hinzuf&uuml;gen" onclick="addMeterInView()" /> -->
			Datum: von
			<input type="text" id="datevon" value=""  />
			bis
			<input type="text" id="datebis" value="" />
			<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	</form>
</div>		

<div id="container">

</div>


