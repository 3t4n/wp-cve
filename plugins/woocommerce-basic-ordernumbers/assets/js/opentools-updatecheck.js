/**
 * OpenTools Update Check Admin JS
 */
var showUpdateCredentialsRow = function (btn) {
	var ajaxurl = jQuery(btn).data('ajaxurl');
	var slug = jQuery(btn).data("slug");
	var nonce = jQuery(btn).data("nonce");

	var credentialRow = jQuery(btn).closest('table').find('tr#'+slug+'-credentials');
	if (credentialRow.length > 0) {
		jQuery(credentialRow).fadeOut(1000, function() { jQuery(this).remove(); });
	} else {
		var ajaxargs = {
			type: "POST",
			url: ajaxurl,
			data: { 
				action: 'getUpdateCredentialsRow_'+slug,
				slug: slug,
				_ajax_nonce: nonce
			},
			success: function ( json ) {
				jQuery(btn).closest('tr').after(json['row']);
			},
			error: function() {  },
			complete: function() {  },
		};
		jQuery.ajax(ajaxargs);
	}
	return false;
};

var submitUpdateCredentials = function(btn) {
	var ajaxurl = jQuery(btn).data('ajaxurl');
	var slug = jQuery(btn).data("slug");
	var nonce = jQuery(btn).data("nonce");
	// the credentialvars data field contains a json-encoded array of variables!
	var credentialvars = jQuery(btn).data("credentialvars");
	
	var tr = jQuery(btn).closest('tr');
	var data = { 
			action: 'submitUpdateCredentials_'+slug,
			slug: slug,
			_ajax_nonce: nonce,
		};
	
	var index;
	for	(index = 0; index < credentialvars.length; index++) {
		var credname = credentialvars[index];
		data[credname] = jQuery(tr).find("input[name='otup_update_credentials["+slug+"]["+credname+"]']").val();
	}
	
	var ajaxargs = {
		type: "POST",
		url: ajaxurl,
		data: data,
		success: function ( json ) {
			if (json['success']) {
				jQuery(tr).find('div.update-credentials-message').html(json['message']);
				jQuery(tr).find('div.update-credentials').removeClass('message-fail').addClass('message-success')
				jQuery(tr).find('div.update-credentials-form').fadeOut( 500, function() { jQuery(this).remove(); });
				jQuery(tr).closest('table').find('a.otup_credentials_link_'+slug).removeClass('dashicons-no').addClass('dashicons-yes');
				jQuery(tr).delay(5000).fadeOut(1000, function() { jQuery(this).remove(); });
				
			} else {
				jQuery(tr).find('div.update-credentials-message').html(json['message']);
				jQuery(tr).find('div.update-credentials').addClass('message-fail').removeClass('message-success');
				jQuery(tr).closest('table').find('a.otup_credentials_link_'+slug).removeClass('dashicons-yes').addClass('dashicons-no');
			}
		},
		error: function() { 
			jQuery(tr).find('div.update-credentials-message').html("Unable to validate the update credentials. Please make sure the server is available.");
			jQuery(tr).find('div.update-credentials').addClass('message-fail').removeClass('message-success');
		},
		complete: function() {  },
	};
	jQuery.ajax(ajaxargs);
	return false;
}
