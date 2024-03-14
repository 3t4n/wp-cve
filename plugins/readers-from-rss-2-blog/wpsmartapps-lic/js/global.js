function __rfr2b_ShowHide(curr, img, img_type, lib_path) {
	var curr = document.getElementById(curr);
	if ( img != '' ) {
		var img  = document.getElementById(img);
	}
	var elbproShowRow = 'block'
	if ( navigator.appName.indexOf('Microsoft') == -1 && curr.tagName == 'TR' ) elbproShowRow = 'table-row';
	if ( curr.style == '' || curr.style.display == 'none' ) {
		curr.style.display = elbproShowRow;
		if ( img != '' && img_type == 1 ) img.src = lib_path + 'images/minus.gif';
		if ( img != '' && img_type == 2 ) img.src = lib_path + 'images/minus-small.gif';
		if ( img != '' && img_type == 3 ) img.src = lib_path + 'images/close-form.gif';
	} else if ( curr.style != '' || curr.style.display == 'block' || curr.style.display == 'table-row' ) {
		curr.style.display = 'none';
		if ( img != '' && img_type == 1 ) img.src = lib_path + 'images/plus.gif';
		if ( img != '' && img_type == 2 ) img.src = lib_path + 'images/plus-small.gif';
		if ( img != '' && img_type == 3 ) img.src = lib_path + 'images/open-form.gif';
	}
}



function __rfr2b_showHidediv(curr,target,outer_div) {
	var target = document.getElementById(target);
	var elbproShowRow = 'block'
	if ( navigator.appName.indexOf('Microsoft') == -1 && target.tagName == 'TR' ) elbproShowRow = 'table-row';
	if ( curr.checked == true ) {
		target.style.display = elbproShowRow;
		if ( outer_div != '' ) document.getElementById(outer_div).style.display = 'block';
	} else {
	    target.style.display = 'none';
		if ( outer_div != '' ) document.getElementById(outer_div).style.display = 'none';
	}
}


function __rfr2b_catpage_openit(openid, closeid){
	var curr = document.getElementById(openid);
	var curr2 = document.getElementById(closeid);
	if ( curr.style.display == 'none' ) {
		curr.style.display = 'block';
	} else if ( curr.style.display == 'block' ) {
		curr.style.display = 'none';
	}
	curr2.style.display = 'none';
}

function __rfr2b_catpage_closeit(openid, closeid){
	var curr = document.getElementById(openid);
	var curr2 = document.getElementById(closeid);
	if ( curr.style.display == 'block' ) {
		curr.style.display = 'none';
	}
	curr2.style.display = 'block';
}


function Show_rfr2b_Control(id,Source,mainID,tagID, bgColor) { 
	if (Source=="1"){
		if (document.layers) document.layers[''+id+''].visibility = "show";
		else if (document.all) document.all[''+id+''].style.visibility = "visible";
		else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "visible";
		document.getElementById(''+mainID+'').style.background  = bgColor;

		if (document.layers) document.layers[''+tagID+''].visibility = "show";
		else if (document.all) document.all[''+tagID+''].style.visibility = "visible";
		else if (document.getElementById) document.getElementById(''+tagID+'').style.visibility = "visible";
		document.getElementById(''+tagID+'').style.display  = "block";
		
	}
	else if (Source=="0"){
		if (document.layers) document.layers[''+id+''].visibility = "hide";
		else if (document.all) document.all[''+id+''].style.visibility = "hidden";
		else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "hidden";
		document.getElementById(''+mainID+'').style.background  = bgColor;
		
		if (document.layers) document.layers[''+tagID+''].visibility = "hide";
		else if (document.all) document.all[''+tagID+''].style.visibility = "hidden";
		else if (document.getElementById) document.getElementById(''+tagID+'').style.visibility = "hidden";
		document.getElementById(''+tagID+'').style.display  = "none";
	}
}




var rfr2b_history_last_checked = [];
jQuery(document).ready(function(){ 
								
	jQuery('#display_rss_new_in_all').change(function(){ 
				if(jQuery(this).is(':checked')){
					rfr2b_history_last_checked = [];
					jQuery('.display_rss_showlist :checkbox:not(#display_rss_new_in_all):checked').each(function(){
						rfr2b_history_last_checked.push(jQuery(this));																									
						jQuery(this).attr('checked',false);
					});
				} else {
					if(rfr2b_history_last_checked.length > 0){
						jQuery.each(rfr2b_history_last_checked,function(){
							jQuery(this).attr('checked',true);
						});
					}
				}
	});

	jQuery('.display_rss_showlist :checkbox:not(#display_rss_new_in_all)').change(function(){ 
		if(jQuery(this).is(':checked')){
			jQuery('#display_rss_new_in_all').attr('checked',false);
		}
	});
	
	

});




function __rfr2b_ChkBlank() { 
	var campaignName = document.getElementById("targated_campaign_name").value;
	
	if( campaignName == null || campaignName == "" ) {  
		alert('Your Campaign Name Required');
		return false;
	}

}



