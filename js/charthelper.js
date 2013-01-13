function getJson(destination) {
  // strUrl is whatever URL you need to call

  jQuery.ajax({
    url: destination,
    success: function(html) {
      strReturn = html;
    },
    async:false
  });
  var json = $.parseJSON(strReturn);
  return json.data;
}


function getValues(id,from,to,basePath)
{
	var daten = getJson(basePath+"index.php/data/getAreaValues/"+id+"/"+from+"/"+to);
	for (var i=0,l = daten.length; i<l; i++)
	{
		daten[i].TimeStamp = splitTS(daten[i].TimeStamp);
		daten[i].Value = parseFloat(daten[i].Value);
	}
	return daten;
}

function getValue(id,basePath) {
  var daten = getJson(basePath+"index.php/data/getLastValue/"+id);
  daten[0].TimeStamp = splitTS(daten[0].TimeStamp);
  daten[0].Value = parseFloat(daten[0].Value);
  return daten[0];
}

function splitTS(date)
{
	var t = date.split(/[- :]/);
	var d = new Date(t[0], t[1]-1,t[2],t[3], t[4],t[5]);
	 
	return Date.parse(d);
}

function dp2dateTS(date)
{
	var t = date.split(/[/]/);
	var d = t[2]+"-"+t[0]+"-"+t[1]+" 00:00:00";
	 
	return d;
}