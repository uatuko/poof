// JavaScript Document

var xmlHttp;

function loadPage(attr, params) {
	if (attr.length != 0) {
		
		document.getElementById("ajax_page").innerHTML = "Loading...";
		
		xmlHttp = GetXmlHttpObject();
		if (xmlHttp == null) {
			alert("Your browser doesn't support HTTP Request");
			return;
		}
		
		switch (attr) {
			case ("research") :
				var url = "./research/";
				break;
			case ("projects") :
				var url = "./projects/";
				break;
			case ("profiles") :
				var url = "./profiles/";
				if (params != null) {
					url += "?id=";
					url += params;
				}
				break;
			case ("concepts") :
				var url = "./concepts/";
				break;
		}
		
		xmlHttp.onreadystatechange = stateChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		xmlHttp.setRequestHeader("Cache-Control", "no-cache");
		xmlHttp.send(null);
		
	}
}


function stateChanged() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
		document.getElementById("ajax_page").innerHTML=xmlHttp.responseText;
	} 
} 


function GetXmlHttpObject() { 

	var objXMLHttp = null;
	
	if (window.XMLHttpRequest) {
		objXMLHttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return objXMLHttp;
}
