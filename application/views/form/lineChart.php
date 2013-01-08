<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>

<script type="text/javascript" src="<?php echo $url?>highstock/js/highstock.js"></script>
<script type="text/javascript" src="<?php echo $url?>highstock/js/modules/exporting.js"></script>



		
<script type="text/javascript">

$(function() {
		// Create the chart
		window.chart = new Highcharts.StockChart({
		    chart: {
		        renderTo: 'container'
		    },

		    rangeSelector: {
		        selected: 1
		    },

		    title: {
		        text: 'AAPL Stock Price'
		    },
		    
		    series: [{
		        name: 'AAPL Stock Price',
		        type: 'spline',
		    	tooltip: {
		    		valueDecimals: 2
		       },
		        data: (function() {
	                var data = [];
	                var daten = getValues();
	                
	                for (var i in daten)
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
	});


function getLastValues() {
  // strUrl is whatever URL you need to call
  var strUrl = "", strReturn = "";

  jQuery.ajax({
    url: "<?php echo site_url("data/getAreaValues/15/2013-01-06 00:00:00/2013-01-07 00:00:00"); ?>",
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
  
  return json.data;
}

function getValues()
{
	var daten = getLastValues();
	for (var i in daten)
	{
		daten[i].TimeStamp = splitTS(daten[i].TimeStamp);
		daten[i].Value = parseFloat(daten[i].Value);
	}
	return daten;
}

function splitTS(date)
{
	var t = date.split(/[- :]/);
	var d = new Date(t[0], t[1]-1,t[2],t[3], t[4],t[5]);
	 
	return Date.parse(d);
}

</script>
		
<div id="container" style="height: 500px"></div>