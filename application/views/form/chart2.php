<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts-more.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo $url?>charthelper.js"></script>
<script type="text/javascript">

$('#combo').change(function()
{
    alert('Value change to ' + $(this).attr('value'));
});

$(document).ready(function() {
  addItem();
});

var chart = null;
var id = 0;
var updateftk = null;

function drawChart() {
    if(chart != null){
        chart.destroy();
    }
    chart = new Highcharts.Chart({
    
        chart: {
            renderTo: 'container',
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
            },
        title: {
            text: 'Wattmeter'
        },
        
        pane: {
            startAngle: -150,
            endAngle: 150,
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
        },
           
        // the value axis
        yAxis: {
            min: 0,
            max: 1000,
            
            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',
            endOnTick: true ,
    
            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: 'Watt'
            }       
        },
        credits: {
            enabled: false
            },
        series: [{
            name: 'Energie Verbrauch',
            data: [parseFloat(getValue(id,"<?php echo base_url(); ?>").Value)],
            tooltip: {
                valueSuffix: ' Watt'
            }
        }]
    
    }, 
    // Add some life
    updateChart());
}

function updateChart() {
        if(typeof updatefkt!='undefined'){
	        clearInterval(updatefkt);
	        
        }
        updatefkt = setInterval(function (){
        	var point = chart.series[0].points[0];
	            var daten = getValue(id,"<?php echo base_url(); ?>");
	            var d = new Date(daten.TimeStamp);
	            var text;
	            if(isToday(d)){
	                text = "Heute\n"+addDigit(d.getHours())+":"+addDigit(d.getMinutes())+":"+addDigit(d.getSeconds());
	            }else{
	                text = addDigit(d.getDate())+"."+addDigit(d.getMonth()+1)+"."+d.getFullYear()+" "+addDigit(d.getHours())+":"+addDigit(d.getMinutes())+":"+addDigit(d.getSeconds());
	            }
	            
	            point.update(parseFloat(daten.Value));
	            chart.yAxis[0].axisTitle.attr({
	                text: text
	            });}, 5000);
        chartValueUpdate();
         
    }


function chartValueUpdate() {
            if(chart!=null)
         	{
	            var point = chart.series[0].points[0];
	            var daten = getValue(id,"<?php echo base_url(); ?>");
	            var d = new Date(daten.TimeStamp);
	            var text;
	            if(isToday(d)){
	                text = "Heute\n"+addDigit(d.getHours())+":"+addDigit(d.getMinutes())+":"+addDigit(d.getSeconds());
	            }else{
	                text = addDigit(d.getDate())+"."+addDigit(d.getMonth()+1)+"."+d.getFullYear()+" "+addDigit(d.getHours())+":"+addDigit(d.getMinutes())+":"+addDigit(d.getSeconds());
	            }
	            
	            point.update(parseFloat(daten.Value));
	            chart.yAxis[0].axisTitle.attr({
	                text: text,
	                max: parseFloat(getValue(id,"<?php echo base_url(); ?>").Value)+120,
	            });
        	}
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
    id = meter[0].ID;
    drawChart();
    
    updateMeterID();
}

function updateMeterID(){
    var selObj = document.getElementById('combo');
    var selIndex = selObj.selectedIndex;
    id = selObj.options[selIndex].value;
   chart.setTitle({text: selObj.options[selIndex].innerHTML});
    
    updateChart();

}
</script>

<div id="combobox">
    <select name="combo" id="combo" onchange="updateMeterID()">
    </select>
</div>

<div id="container">

</div>
