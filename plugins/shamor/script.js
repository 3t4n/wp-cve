jQuery(document).ready(function(){
    const data = {
		"action" : "validate_wp_shammor"
	};
	if (typeof(shouldShammor) == 'undefined' || shouldShammor != false) {
		shouldShammor = false;
		setInterval(() => {
			jQuery.post(ajax_object.ajax_url, data, function(response) {
				if( response.blocked === true) {
					location.reload();
					return;
				}
			});
		}, 60000);
	}
});