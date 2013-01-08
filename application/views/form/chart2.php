<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts-more.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/modules/exporting.js"></script>


<script type="text/javascript">
var value;
	$(function () {
    
    var chart = new Highcharts.Chart({
    
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
            max: 200,
            
            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',
    
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
            data: [parseFloat(getvalue())],
            tooltip: {
                valueSuffix: ' Watt'
            }
        }]
    
    }, 
    // Add some life
    function (chart) {
        setInterval(function () {
            var point = chart.series[0].points[0];
            
            point.update(parseFloat(getvalue()));
            
        }, 5000);
    });
});


function getvalue() {
  // strUrl is whatever URL you need to call
  var strUrl = "", strReturn = "";

  jQuery.ajax({
    url: "<?php echo site_url("data/getLastValue/1"); ?>",
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
  return json.data[0].Value;
}

</script>


<div id="container">

</div>
