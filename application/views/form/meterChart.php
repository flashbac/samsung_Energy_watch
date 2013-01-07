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
                            var x = data[0].TimeStamp, // current time
                                y = data[0].Value;
                            series.addPoint([x, y], true, true);
                        }, 2000);
                    }
                }
            },
            title: {
                text: 'Live random data'
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
                    width: 1,
                    color: '#808080'
                }]
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
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -10; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,
                            y: getValue()
                        });
                    }
                    return data;
                })()
            }]
        });
    });
    
});

function getLastValue() {
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
  return json.data;
}
function getValue(){
	var data = getLastValue();
	var ts = splitTS(data[0].TimeStamp);
	var mydate = new Date ("July 1, 1986 02:20:00");
	var myepoch = mydate.getTime()/1000.0;
	if(ts <= lastTs)
	{
		alert('Date ist kleiner.');
		
		// lastTs = ts;
		return data;
	}
	else
	{
		alert('Date ist größer.');
		return null;
	}
}
function splitTS(date)
{
	vat t = date.split(/[- :]/);
	var d = new date(t[0], t[1],t[2],t[3], t[4],t[5]); 
	return d;
}

		</script>
		
<div id="container">

</div>