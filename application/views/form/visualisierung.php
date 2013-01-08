<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo $url?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo $url?>datepicker.js"></script>


		
<script type="text/javascript">
	
$(document).ready(function() {
  addItem();
});




function addItem()
{
	var meter = getJson("samsung_Energy_watch/index.php/data/getMeter/1");
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

function tauschen(obj){
var selObj = document.getElementById('combo');
var object = document.getElementById("container");
var selIndex = selObj.selectedIndex;
var austausch = "<p>"+selObj.options[selIndex].value;+"</p>";
object.innerHTML= austausch;
}


Date.firstDayOfWeek = 0;
Date.format = 'yyyy/mm/dd';
$(function() {
        $( "#datebis" ).datepicker();
		$( "#datevon" ).datepicker();
    });

</script>

<form name=myform ">
	<select name=mytextarea id="combo" >
	</select>
	<input type="button" name="Anzeigen" value="Anzeigen" onclick="tauschen(this)" />
	<input type="text" id="datevon" value="" />
	<input type="text" id="datebis" value="" />
</form>



		
<div id="container">

</div>