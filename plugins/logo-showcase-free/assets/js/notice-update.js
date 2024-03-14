jQuery(document).on( 'click', '.cwcfp-update-notice .notice-dismiss', function() {

	jQuery.ajax({
		url: ajaxurl,
		data: {
			action: 'cwcfp_dismiss_notice'
		}
	})

})