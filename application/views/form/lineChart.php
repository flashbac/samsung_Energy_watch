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
  //dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  //dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
});

var chart;

function drawLineChart(id,from,to) {
var numberOfValues;


	function MeterValues(id,from,to){
		var series = new Array();
		for (var i=0;i< id.length; i++)
		{
			var MeterDaten = getJson("<?php echo base_url(); ?>index.php/data/getDataFromMeter/"+id);
			series.push({
		     	name: MeterDaten.Name+" ("+MeterDaten.Unit+")",
		        data: (function() {
		            var data = [];
		            var daten = getValues(id[i],from,to,"<?php echo base_url(); ?>");
		               
		            for (var k=0,l = daten.length; k<l; k++)
		            {
			          	data.push({
				            x: daten[k].TimeStamp,
			    	        y: daten[k].Value
			            });
			        }
			        numberOfValues = daten.length;
			        return data;
		        })(),
		        turboThreshold: numberOfValues,
			});
		}
		// var series = [{
	     	// name: 'Temperature',
	        // data: (function() {
	            // var data = [];
	            // var daten = getValues(id,from,to,"<?php echo base_url(); ?>");
// 	               
	            // for (var i=0,l = daten.length; i<l; i++)
	            // {
		          	// data.push({
			            // x: daten[i].TimeStamp,
		    	        // y: daten[i].Value
		            // });
		        // }
		        // numberOfValues = daten.length;
		        // return data;
	        // })(),
	        // turboThreshold: numberOfValues,
		// },{
	    	// name: 'Temperature',
	        	// data: (function() {
		        	// var data = [];
		            // var daten = getValues(15,from,to,"<?php //echo base_url(); ?>");
// 		               
		            // for (var i=0,l = daten.length; i<l; i++)
		            // {
		            	// data.push({
		                	// x: daten[i].TimeStamp,
		                    // y: daten[i].Value
		                // });
		            // }
		            // numberOfValues = daten.length;
		            // return data;
	    		// })(),
	      	// turboThreshold: numberOfValues,
	     // }];
		 return series;
	};
	
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
	    	text: "MultiChart"
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
        series: MeterValues(id,from,to)
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
	var elemnetlist = document.getElementsByClassName('FormArray');
	
	var ID = new Array();
	var StartTS;
	var EndTS;
	for(var i = 0; i < elemnetlist.length;i++)
	{
		ID.push(elemnetlist[i][0].value);
		StartTS = elemnetlist[i][1].value;
		EndTS = elemnetlist[i][2].value;
	}
	
	//var selObj = document.getElementById('combo');
	//var selIndex = selObj.selectedIndex;
	var timeVon = dp2dateTS(StartTS,'00:00:00');
	var timeBis = dp2dateTS(EndTS,'23:59:59');
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
	$("#f"+anzahl).append('<input type="text" id="datevon'+anzahl+'" value=""  />');
	$("#f"+anzahl).append('Datum: bis');
	$("#f"+anzahl).append('<input type="text" id="datebis'+anzahl+'"  value=""  />');
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
			<input type="button" name="Hinzufuegen" value="Hinzuf&uuml;gen" onclick="addMeterInView()" />
			<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	</form>
</div>		

<div id="container">

</div>

<p>Minimalwert</p>
<p>Mittelwert</p>
<p>Maximalwert</p>
<p>Arbeit</p>

