jQuery(document).on( 'click', '.cwcf-review-notice .notice-dismiss', function() {

	jQuery.ajax({
		url: ajaxurl,
		data: {
			action: 'cwcf_review_dismiss_notice'
		}
	})

})