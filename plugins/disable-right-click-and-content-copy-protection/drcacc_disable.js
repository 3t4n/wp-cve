jQuery( document ).ready( function( event ) {
	
	window.onload = function(){drcacc_disableSelection(document.body);};
	document.oncontextmenu = drcacc_nocontext;
	document.onkeydown = drcacc_disableEnterKey;
	document.onselectstart = drcacc_disableCopyIE;
	if(navigator.userAgent.indexOf('MSIE')==-1) {
		document.onmousedown = drcacc_disableCopy;
		document.onclick = drcacc_reEnable;
	}

	function drcacc_nocontext(e) {
		return false;
	}
	
	function drcacc_disableSelection(target){
		//For IE
		if (typeof target.onselectstart!="undefined")
		target.onselectstart = drcacc_disableCopyIE;
		
		//For Firefox
		else if (typeof target.style.MozUserSelect!="undefined")
		{target.style.MozUserSelect="none";}
		
		//All other
		else
		target.onmousedown=function(){return false}
		target.style.cursor = "default";
	}

	function drcacc_disableCopyIE() {
		var e = e || window.event;
		var elemtype = window.event.srcElement.nodeName;
		elemtype = elemtype.toUpperCase();
		if (elemtype == "IMG") {return false;}
		if (elemtype != "TEXT" && elemtype != "TEXTAREA" && elemtype != "INPUT" && elemtype != "PASSWORD" && elemtype != "SELECT" && elemtype != "OPTION" && elemtype != "EMBED"){
			return false;
		}
	}

	function drcacc_disableEnterKey(e){
		var elemtype = e.target.tagName;
		elemtype = elemtype.toUpperCase();
		if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED"){
			elemtype = 'TEXT';
		}
		
		if (e.ctrlKey){
		var key;
		if(window.event)
			key = window.event.keyCode;     //IE
		else
			key = e.which;
		
		if (elemtype!= 'TEXT' && (key == 26 || key == 43 || key == 65 || key == 67 || key == 73 || key == 83 || key == 85 || key == 86  || key == 88 || key == 97 || key == 99 || key == 120)){
			return false;
		}else
			return true;
		}
	}

	function drcacc_disableCopy(e){	
		var e = e || window.event;
		var elemtype = e.target.tagName;
		elemtype = elemtype.toUpperCase();
		
		if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED"){
			elemtype = 'TEXT';
		}
		var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
		if (elemtype == "IMG" && e.detail >= 2) {return false;}
		if (elemtype != "TEXT") {
			if (isSafari)
				return true;
			else
				return false;
		}	
	}

	function drcacc_reEnable(){
		return true;
	}

});



















