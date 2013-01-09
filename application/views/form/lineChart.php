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
  addItem();
  dp_cal1 = new Epoch('epoch_popup','popup',document.getElementById('datevon'));
  dp_cal2 = new Epoch('epoch_popup','popup',document.getElementById('datebis'));
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
	                var daten = getValues(id,from,to,"<?php echo base_url(); ?>");
	                
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
var timeVon = dp2dateTS(document.getElementById('datevon').value);
var timeBis = dp2dateTS(document.getElementById('datebis').value);
drawLineChart(selObj.options[selIndex].value,timeVon,timeBis);

}


$(function() {

	
        //$( "#datebis" ).datepicker();
		//$( "#datevon" ).datepicker();
    });



</script>

<form name=myform ">
	<select name=mytextarea id="combo" >
	</select>
	<input type="button" name="Anzeigen" value="Anzeigen" onclick="drawChart()"/>
	<input type="text" id="datevon" value=""  />
	<input type="text" id="datebis" value="" />
</form>
		
<div id="container">

</div>