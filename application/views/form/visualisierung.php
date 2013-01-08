<?php $url = base_url() . 'js/'; ?>
<script type="text/javascript" src="<?php echo $url?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $url?>highcharts/js/highcharts.js"></script>
		
<script type="text/javascript">
	
$(document).ready(function() {
  addItem();
});


function getJson(extention) {
  // strUrl is whatever URL you need to call
  var strUrl = "", strReturn = "";
  var newURL = window.location.protocol + "//" + window.location.host + "/" + extention;
  
  jQuery.ajax({
    url: newURL,
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
  return json.data;
}

function addItem()
{
	var meter = getJson("samsung_Energy_watch/index.php/data/getMeter/1");
	var element4 = document.getElementById("combo");
	
	for (var i in meter)
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


</script>

<form name=myform onchange="tauschen(this)">
	<select name=mytextarea id="combo" >
	</select>
	<input type="button" name="Anzeigen" value="Anzeigen"
	      onclick="addItem()">
</form>


		
<div id="container">

</div>