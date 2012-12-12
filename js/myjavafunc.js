	htmlrequest = [];
	function getRequest(url, div) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		htmlrequest[div] = new XMLHttpRequest();
		htmlrequest[div].onreadystatechange = function() {
			if (htmlrequest[div].readyState == 4 && htmlrequest[div].status == 200) {
				document.getElementById(div).innerHTML = htmlrequest[div].responseText;
			}
		}
		link = url + "&divchange="+ div; 
		//alert(url, div);
		htmlrequest[div].open("GET", link, true);
		htmlrequest[div].send();
	}

	function loadRequest(url, div)
	{
		var str = "?load=load";
		var link = url + str;
		getRequest(link, div);		
		
	}

	function update(url, div)
	{
				
		// code for IE7+, Firefox, Chrome, Opera, Safari
		htmlrequest[div] = new XMLHttpRequest();
		htmlrequest[div].onreadystatechange = function() {
			if (htmlrequest[div].readyState == 4 && htmlrequest[div].status == 200) {
				document.getElementById(div).innerHTML = htmlrequest[div].responseText;
			}
		}
		link = url + "?divchange="+ div; 
		//alert(url, div);
		htmlrequest[div].open("GET", link, true);
		htmlrequest[div].send();
	}
	
	

	function addRequest(url, div, value, type) {
		if (value == null) {
			value = "";
		} else {
			if (value <= 0) {
				alert("Bitte einen Wert angeben!");
				return;
			}
		}
		if (type == null) {
			type = "";
		}
		var str = "?value=" + value + "&type=" + type;
		var link = url + str;
		getRequest(link, div);
	}

	function delRequest(url, div, value) {
		var str = "?del=" + value;
		var link = url + str;
		getRequest(link, div);
	}
		function updateRequest(url, div, update, value, type) {
		if (value == null) {
			value = "";
		} else {
			if (value <= 0) {
				delRequest(url,div,update);
				return;
			}
		}
		if (type == null) {
			type = "";
		}
		var str = "?update=" + update + "&value=" + value + "&type=" + type;
		var link = url + str;
		getRequest(link, div);
	}
	
	function updatePQ(url, div, update, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;
			
		bisok = checkdate(bis)
		if (!bisok)
			return false;
			
		var str = "?update=" + update + "&value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}
	
	function addPQ(url, div, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;

		bisok = checkdate(bis)
		if (!bisok)
			return false;

		var str = "?value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}

	function updateP(url, div, update, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;
			
		bisok = checkdate(bis)
		if (!bisok)
			return false;
			
		var str = "?update=" + update + "&value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}
	
	function addP(url, div, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;

		bisok = checkdate(bis)
		if (!bisok)
			return false;

		var str = "?value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}

	function updateK(url, div, update, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;
		if (bis == "") {
			bis="NULL";
		}
		else
		{
			bisok = checkdate(bis)
			if (!bisok)
				return false;
		}
			
		var str = "?update=" + update + "&value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}

	function addK(url, div, value, von, bis) {
		vonok = checkdate(von)
		if (!vonok)
			return false;
		if (bis == "") {
			bis='NULL';
		}
		else
		{
			bisok = checkdate(bis)
			if (!bisok)
				return false;
		}
		var str = "?value=" + value + "&von=" + von + "&bis=" + bis;
		var link = url + str;
		getRequest(link, div);
	}

	function checkdate(s) {
			var err = '';
			if (!s.match(/^\d{2}\.\d{2}\.\d{4}$/)) err =
			'Bitte beachten Sie das angegebene Datumsformat!';
			else {
			var a = s.split(".");
			var j = new Date(), d = new Date(a[2], a[1]-1, a[0]);
			if (d.getDate() != a[0] || d.getMonth() != a[1]-1) err =
			'Das eingegebene Datum existiert nicht!';
			}
			if (err) { 
				alert(err);
				return false
			}
			else
			return true;

	}	
	
	function checkdateEmpty(s) {
		
		if(s != '')
		{
			var err = '';
			if (!s.match(/^\d{2}\.\d{2}\.\d{4}$/)) err =
			'Bitte beachten Sie das angegebene Datumsformat!';
			else {
			var a = s.split(".");
			var j = new Date(), d = new Date(a[2], a[1]-1, a[0]);
			if (d.getDate() != a[0] || d.getMonth() != a[1]-1) err =
			'Das eingegebene Datum existiert nicht!';
			}
			if (err) { 
				alert(err);
				return false
			}
			else
			return true;
		}
		return true;
	}	
	
	function colordate(obj)
	{	
		s= obj.value
		if(s!='')
		{
			var err = '';
			if (!s.match(/^\d{2}\.\d{2}\.\d{4}$/)) err =
			'Bitte beachten Sie das angegebene Datumsformat!';
			else {
			var a = s.split(".");
			var j = new Date(), d = new Date(a[2], a[1]-1, a[0]);
			if (d.getDate() != a[0] || d.getMonth() != a[1]-1) err =
			'Das eingegebene Datum existiert nicht!';
			}
			if (err){
			obj.style.backgroundColor="#fabfce";
			}
			else
			obj.style.backgroundColor='#d7ffd7';
				
		}
		else
			obj.style.backgroundColor="#ffffff";
	}
