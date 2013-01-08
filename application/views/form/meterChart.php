<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
		
<script type="text/javascript">

var lastTs = (new Date()).getTime();
$(function () {
    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
    
        var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline',
                marginRight: 10,
                events: {
                    load: function() {
    
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function() {
                        	var data = getValue();
                        	if (data != null)
                        	{
                            var x = (new Date()).getTime(), //data[0].TimeStamp, // current time
                                y = data[0].Value;
                            series.addPoint([x, y]);
                           }
                        }, 10000);
                    }
                }
            },
            title: {
                text: 'Live Data'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'Value'
                },
                plotLines: [{
                    value: 0,
                    width: 5,
                    color: '#808080'
                },]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: 'Random data',
                data: (function() {
                var data = [];
                var d = getValue();
                 
                data.push({
                            x: d[0].TimeStamp,
                            y: d[0].Value
                        });
                return data;
                })()
                /*(function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
    				var d = getValue();

                    for (i = -50; i <= 0; i++) {
                        data.push({
                            x: d[0].TimeStamp,
                            y: d[0].Value
                        });
                    }
                    return data;
                })()*/
            }]
        });
    });
    
});

function getLastValue() {
  // strUrl is whatever URL you need to call
  var strUrl = "", strReturn = "";

  jQuery.ajax({
    url: "<?php echo site_url("data/getLastValue/18"); ?>",
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
  
  return json.data;
}

function getValue()
{
	var data = getLastValue();
	var ts = splitTS(data[0].TimeStamp);
	data[0].TimeStamp = ts;
	data[0].Value = parseFloat(data[0].Value) 
	return data;
}
function getNewerValue(){
	var data = getValue();
	if(data[0].TimeStamp > lastTs)
	{
		lastTs = ts;
		return data;
	}
	else
	{
		return null;
	}
}

function splitTS(date)
{
	var t = date.split(/[- :]/);
	var d = new Date(t[0], t[1]-1,t[2],t[3], t[4],t[5]);
	 
	return Date.parse(d);
}

		</script>
		
<div id="container">

</div>