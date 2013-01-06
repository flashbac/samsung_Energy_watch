<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts-more.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/modules/exporting.js"></script>


<script type="text/javascript">
	jQuery(document).ready(function() {

		var chart = new Highcharts.Chart({
    
        chart: {
            renderTo: 'container',
            type: 'gauge',
            alignTicks: false,
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
    
        title: {
            text: 'Speedometer with dual axes'
        },
        
        pane: {
            startAngle: -150,
            endAngle: 150
        },          
    
        yAxis: [{
            min: -40,
            max: 100,
            lineColor: '#339',
            tickColor: '#339',
            minorTickColor: '#339',
            offset: -25,
            lineWidth: 2,
            labels: {
                distance: -20,
                rotation: 'auto'
            },
            tickLength: 5,
            minorTickLength: 5,
            endOnTick: false
        }],
    
        series: [{
            name: 'Speed',
            data: [80],
            dataLabels: {
                formatter: function () {
                    var kmh = this.y;
                    return '<span style="color:#339">'+ kmh + ' km/h</span>';
                },
                backgroundColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                    },
                    stops: [
                        [0, '#DDD']
                    ]
                }
            },
            tooltip: {
                valueSuffix: ' km/h'
            }
        }]
    
    },
    // Add some life
    function(chart) {
        setInterval(function() {
            var point = chart.series[0].points[0],
                newVal, inc = Math.round((Math.random() - 0.5) * 20);
    
            newVal = point.y + inc;
            if (newVal < 0 || newVal > 200) {
                newVal = point.y - inc;
            }
    
            point.update(newVal);
            getValue();
    
        }, 3000);
    
    });

	}); 


</script>


<div id="container">

</div>
