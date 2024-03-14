jQuery(document).ready(function($) {
	if($("#afbsbhidenotice").length) {
		$("#afbsbhidenotice").click( function(e) {
			e.preventDefault();

			var data = {
				'action': 'afbsb_hide_notice',
				'nonce': aflb_admin.nonce
			};

			$.post(ajaxurl, data, function(response) {
				
				if($("#afbsbnotice").length) {
					$("#afbsbnotice").hide("slow");
				}
			});
		});
	}
});