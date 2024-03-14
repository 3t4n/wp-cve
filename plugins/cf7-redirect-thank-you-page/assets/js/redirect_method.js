jQuery(document).ready(function($) {

	var formid;
	var formid_long;

	// for redirect method 1
	document.addEventListener('wpcf7mailsent', function( event ) {
		var id_long =			event.detail.id;
		var id = 				event.detail.contactFormId;
		
		var formid = id_long;
		var formid = id;
		
		var forms = cf7rl_ajax_object.cf7rl_forms;
		
		var array_list = forms.split(",");
		
		array_list.forEach(function(item) {
			
			// check to see if this array item has redirect enabled
			var result_url = 	forms.indexOf(id+'|url');
			var result_thank = 	forms.indexOf(id+'|thank');
			
			
			var item_list = item.split("|");
			
			if (item_list[1] == id) {
				
				var url = item_list[3];
				var tab = item_list[4];
				
				
				// url
				if (result_url > -1) {
					// open in same tab
					if (tab == 0) {
						window.location.href = url;
					}
					// open in new tab
					if (tab == 1) {
						var win = window.open(url, '_blank');
						win.focus();
					}
				}		
				
				
				// thank you page
				if (result_thank > -1) {
					
					var data = {
						'action':	'cf7rl_get_form_thank',
						'formid':	formid,
					};
					
					jQuery.ajax({
						type: "POST",
						data: data,
						dataType: "json",
						async: false,
						url: cf7rl_ajax_object.cf7rl_ajax_url,
						xhrFields: {
							withCredentials: true
						},
						success: function (response) {
							
							jQuery('#'+id_long).html(response.html);
							
						}
					});
					
				}
				
			}
			
		});
		
	}, false );











	// for redirect method 2 - with WPCF7_LOAD_JS off
	if (jQuery('.wpcf7-mail-sent-ok')[0]) {
		
		var id_long = jQuery('.wpcf7-mail-sent-ok').closest('.wpcf7').attr("id");
		var id = id_long.split('f').pop().split('-').shift();
		
		var formid = id_long;
		var formid = id;
		
		var forms = cf7rl_ajax_object.cf7rl_forms;
		
		var array_list = forms.split(",");
		
		array_list.forEach(function(item) {
			
			// check to see if this array item has redirect enabled
			var result_url = 	forms.indexOf(id+'|url');
			var result_thank = 	forms.indexOf(id+'|thank');
			
			
			var item_list = item.split("|");
			
			if (item_list[1] == id) {
				
				var url = item_list[3];
				var tab = item_list[4];
				
				
				// url
				if (result_url > -1) {
					// open in same tab
					if (tab == 0) {
						window.location.href = url;
					}
					// open in new tab
					if (tab == 1) {
						var win = window.open(url, '_blank');
						win.focus();
					}
				}		
				
				
				// thank you page
				if (result_thank > -1) {
					
					var data = {
						'action':	'cf7rl_get_form_thank',
						'formid':	formid,
					};
					
					jQuery.ajax({
						type: "POST",
						data: data,
						dataType: "json",
						async: false,
						url: cf7rl_ajax_object.cf7rl_ajax_url,
						xhrFields: {
							withCredentials: true
						},
						success: function (response) {
							
							jQuery('#'+id_long).html(response.html);
							
						}
					});
					
				}
				
			}
			
		});
	};
	
	
	
	
	


});
