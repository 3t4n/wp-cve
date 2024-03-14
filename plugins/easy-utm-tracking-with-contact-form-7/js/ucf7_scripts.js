
function getQueryVariable(variable)
	{
    	var query = window.location.search.substring(1);
    	var vars = query.split("&");
    	for (var i=0;i<vars.length;i++) {
           	var pair = vars[i].split("=");
           	if(pair[0] == variable){return pair[1];}
    	}
    	return(false);
	}    
	
    
	function createCookie(name,value,days) {    
    	var expires = "";
    	if (days) {
        	var date = new Date();
        	date.setTime(date.getTime()+(days*24*60*60*1000));
        	var expires = "; expires="+date.toGMTString();
    	}
    	document.cookie = name+"="+value+expires+"; path=/";
	}

	function readCookie(name) {
    	var nameEQ = name + "=";
    	var ca = document.cookie.split(';');
    	for(var i=0;i < ca.length;i++) {
        	var c = ca[i];
        	while (c.charAt(0)==' ') c = c.substring(1,c.length);
        	if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    	}
    	return null;
	}
	function eraseCookie(name) {
    	createCookie(name,"",-1);
	}
	var c_name = "_deco_utmz";
	if(getQueryVariable("utm_source") != "") {
    	createCookie("_deco_utmz", getQueryVariable("utm_source") + "|" + getQueryVariable("utm_medium")+ "|" + getQueryVariable("utm_term")+ "|" + getQueryVariable("utm_campaign")+ "|" + getQueryVariable("utm_content"), 60);
        createCookie("_deco_utmurl", window.location.href.split('?')[0], 60);
	}
    //checking if referrer is external url.
    
    if(document.referrer.indexOf(location.protocol + "//" + location.host) === 0){
       createCookie("_deco_utm_referrer", document.referrer, 60);
       }