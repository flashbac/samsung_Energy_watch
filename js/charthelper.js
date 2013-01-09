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


function getValues(id,from,to)
{
	var daten = getJson("samsung_Energy_watch/index.php/data/getAreaValues/"+id+"/"+from+"/"+to);
	for (var i=0,l = daten.length; i<l; i++)
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