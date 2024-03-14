/************************************************************
* javascript/jquery script for astound_spam_prevention
************************************************************/

function astoundDiags() {
 	var nonce=document.astound_form['astound_opt'].value;
	var ip=document.astound_diags['ip'].value;
	var email=document.astound_diags['email'].value;
	var author=document.astound_diags['author'].value;
	var subject=document.astound_diags['subject'].value;
	var comment=document.astound_diags['comment'].value;
	astound_cursor_on();
    
	
	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_diags', 
			'astound_opt':nonce,
			'ip' : ip,
			'email' : email,
			'author' : author,
			'subject' : subject,
			'comment' : comment,
			'done':'done'
		}, 
		function(response){
			var id=document.getElementById('astound_results');
			id.innerHTML=response;
			id.style.display="block";
			astound_cursor_off();	
			return; 
		}
	);
	

}
function astoundmenu(item) {
	/* reset all the menu colors */
	if (item<5) {
		for(j=0;j<6;j++) {
			tab="astoundm"+j;
			id=document.getElementById(tab);
			if (id) {
				id.style.backgroundColor="LightGray";
				id.style.borderBottom="none";
				id.style.borderBottom="thin solid black";
			}
		}
		tab="astoundm"+item;
		id=document.getElementById(tab);
		id.style.backgroundColor="#f1f1f1";
		id.style.borderBottom="none";
	}
	/* all divs invisible or invisible */
	for(j=0;j<6;j++) {
		divid="astoundd"+j;
		id=document.getElementById(divid);
		if (id) {
			if (item==j) {
				id.style.display="block";
			} else {
				id.style.display="none";
			}
		}
	}
	if(item==1) {
		astound_show_logs();
	}  else if(item==5) { // not used at this time - only for testing
		astound_show_option_dump();
	} else if(item==4) {
		astound_show_cache();
	} 
}

 function astoundProcess(option) { 
 	var nonce=document.astound_form['astound_opt'].value;
	astound_cursor_on();	
	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_set_option', 
			'name' : option.name,
			'checked' : option.checked,
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			return; /* positive feedback here? */
		}
	);
}
function astound_show_cache() {
	var nonce=document.astound_form['astound_opt'].value;
	console.log("astound_show_cache entry");
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_show_cache', 
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			console.log("astound_show_cache return");
			console.log("response '"+response+"'");
			id=document.getElementById('astound_cache_msg');
			id.innerHTML="Cache Listing"; //response;
			id=document.getElementById('astound_cache');
			id.innerHTML=response.slice(0, -1); //response - last zero;
			astound_cursor_off();	
			return; /* positive feedback here? */
		}
	);

	
	
}
function astound_show_logs() {
 	var nonce=document.astound_form['astound_opt'].value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_show_logs', 
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			id=document.getElementById('astound_logs');
			id.innerHTML=response;
			astound_cursor_off();	
			return; /* positive feedback here? */
		}
	);
}
function astound_clear_cache() {
 	var nonce=document.astound_form['astound_opt'].value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_clear_cache', 
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_cursor_off();	
			id=document.getElementById('astound_cache');
			id.innerHTML="";
			id=document.getElementById('astound_cache_msg');
			id.innerHTML=response;
			return; /* positive feedback here? */
		}
	);
}
function astound_delete_logs() {
 	var nonce=document.astound_form['astound_opt'].value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_delete_log', 
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_show_logs();
			astound_cursor_off();	
			return; /* positive feedback here? */
		}
	);
}
function astound_show_option_dump() {
 	var nonce=document.astound_form['astound_opt'].value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_show_option_dump', 
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			id=document.getElementById('astound_options');
			id.innerHTML=response;
			astound_cursor_off();	
			return; /* positive feedback here? */
		}
	);

}
function astound_show_spamwords() {
	document.astound_form['astound_show_spamwords_button'].style.display="none";
	document.getElementById('astound_edit_spamwords').style.display="block";
	return false;
}
function astound_show_whitelist() {
	document.astound_form['astound_show_wlist'].style.display="none";
	document.getElementById('astound_edit_wlist').style.display="block";
	return false;
}
function astound_show_validtldlist() {
	document.astound_form['astound_show_tldlist'].style.display="none";
	document.getElementById('astound_edit_tldlist').style.display="block";
	return false;
}
function astound_show_invalidtldlist() {
	document.astound_form['astound_show_badtldlist'].style.display="none";
	document.getElementById('astound_edit_badtldlist').style.display="block";
	return false;
}

function astound_save_spamwords() {
 	var nonce=document.astound_form['astound_opt'].value;
	var spamwords=document.getElementById('astound_spamwords').value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_set_spamwords', 
			'spamwords' : spamwords,
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_cancel_spamwords();
			astound_cursor_off();	
		}
	);
	return false;
}

function astound_save_whitelist() {
 	var nonce=document.astound_form['astound_opt'].value;
	var wlist=document.getElementById('astound_wlist').value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_set_wlist', 
			'wlist' : wlist,
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_cancel_whitelist();
			astound_cursor_off();	
		}
	);
	return false;
}

function astound_save_tldlist() {
 	var nonce=document.astound_form['astound_opt'].value;
	var tldlist=document.getElementById('astound_tldlist').value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_set_tldlist', 
			'tldlist' : tldlist,
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_cancel_tldlist();
			astound_cursor_off();	
		}
	);
	return false;
}
function astound_save_badtldlist() {
 	var nonce=document.astound_form['astound_opt'].value;
	var badtldlist=document.getElementById('astound_badtldlist').value;
	astound_cursor_on();	
 	astound_ajaxLoad(
		ajaxurl, 
		{
			'action': 'astound_set_badtldlist', 
			'badtldlist' : badtldlist,
			'astound_opt':nonce,
			'done':'done'
		}, 
		function(response){
			astound_cancel_badtldlist();
			astound_cursor_off();	
		}
	);
	return false;
}
function astound_cancel_spamwords() {
	document.astound_form['astound_show_spamwords_button'].style.display="block";
	document.getElementById('astound_edit_spamwords').style.display="none";
	return false;	
}
function astound_cancel_whitelist() {
	document.astound_form['astound_show_wlist'].style.display="block";
	document.getElementById('astound_edit_wlist').style.display="none";
	return false;	
}
function astound_cancel_tldlist() {
	document.astound_form['astound_show_tldlist'].style.display="block";
	document.getElementById('astound_edit_tldlist').style.display="none";
	return false;	
}
function astound_cancel_badtldlist() {
	document.astound_form['astound_show_badtldlist'].style.display="block";
	document.getElementById('astound_edit_badtldlist').style.display="none";
	return false;	
}
function astound_cursor_on() {
    document.getElementById("astound_div").style.cursor = "wait";
}
function astound_cursor_off() {
    document.getElementById("astound_div").style.cursor = "auto";
}

/* jquery was not loading on some sites. */


function astound_ajaxLoad(url,post,handler) {
	var data = "";
	for (var prop in post) {
		data+="&"+prop+"="+encodeURIComponent(post[prop]);
	}
	data=data.substr(1);
 	var xhttp = new(this.XMLHttpRequest || ActiveXObject)('MSXML2.XMLHTTP.3.0');
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			astound_cursor_off();	
			handler(xhttp.responseText);
		}
	};
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhttp.send(data);	
}

