(function ($) {
	'use strict';

	$(document).ready(function () {

		setTimeout(function() {
			// Prevent Codestar image click for skins.
			$('.premade_skins').find('.csf--sibling').unbind( 'click' );

			// Import skin.
			$('.premade_skins .csf--image').click(function (event) {
				event.preventDefault();

				if (confirm(wp_bnav_messages.skin_change_confirmation_alert) === true) {
					var skin = $(this).find('input').val();
				
					$.ajax({
						type: 'POST',
						url: wp_bnav.ajaxurl,
						data: {
							'action': wp_bnav.action,
							'nonce': wp_bnav.nonce,
							'skin': skin,
						},
						success: function (data) {
							if (data['status'] === 'success') {
								alert(wp_bnav_messages.skin_change_alert);
								window.location.reload();
							} else {
								alert(data['message']);
							}
						}
					});
				}
			});
		}, 1000);

	});

})(jQuery);