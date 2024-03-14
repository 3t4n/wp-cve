jQuery(document).ready(function () {
	jQuery('.holded-sync-button').click(function (e) {
		e.preventDefault();

		let btn = jQuery(this);
		let btnOriginaltxt = btn.html();
		btn.attr("disabled", true);
		jQuery('.holded-sync-button').attr("disabled", true);
		btn.html(holdedWC_ajax_object.synctxt+'... <img src="/wp-admin/images/loading.gif">');

		let period = btn.data('period');
		let action = btn.data('action');
		var data = {
			'action': 'holdedwc_' + action,
			'nonce' : holdedWC_ajax_object.nonce,
			'period': period
		};

		// Call to ajax function.
		jQuery.post(holdedWC_ajax_object.ajax_url, data, function(response) {
			jQuery('.holded-sync-button').attr("disabled", false);

			let replywrapper = btn.parent().find('.holdedwc-ajaxreply');
			replywrapper.removeClass('hidden');
			if(response.error) {
				replywrapper.addClass('error');
			} else {
				replywrapper.addClass('success');
			}

			btn.html(btnOriginaltxt);
			replywrapper.html(response.message);
		});
	});

	jQuery('#see-holded-logs').click(function (e) {
		e.preventDefault();
		jQuery("#holded-logs").toggle();
	});
});