(function($) {
	
	// Hook into the "notice-my-class" class we added to the notice, so
	// Only listen to YOUR notices being dismissed
	$(document).on('click', '.notice-snow-storm .notice-dismiss', function () {						
		// Read the "data-notice" information to track which notice
		// is being dismissed and send it via AJAX
		var slug = $(this).closest('.notice-snow-storm').data('notice');
		
		// Make an AJAX call
		// Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.ajax(ajaxurl + '?action=snowstorm_dismissed_notice', {
			type: 'POST',
			data: {
				action: 'snowstorm_dismissed_notice',
				slug: slug,
			}
		});
	});
})(jQuery);