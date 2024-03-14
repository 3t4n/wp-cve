; (function ($) {
	$(document).on('ready', function () {
		window.onloadTurnstileCallback = function(){
		var ECTFormId = document.querySelectorAll('#wppool-turnstile-container');
		if (ECTFormId) {
			ECTFormId.forEach(function (ectID) {
				turnstile.render(ectID, {
					sitekey: window.WP_TURNSTILE_OBJ.CF_SITE_KEY,
					callback: function (token) {
						// Get the submit buttons and reset password buttons
						const submitButtons = document.querySelectorAll('.submit input[type="submit"], input[name="wc_reset_password"] + button.woocommerce-Button, .woocommerce button[type="submit"], .bbp-form button[type="submit"]');
						// Set the pointer-events and opacity of the buttons
						submitButtons.forEach(button => {
							button.style.pointerEvents = 'auto';
							button.style.opacity = 1;
						});
					}
				});
			});
		};
	}
	});
})(jQuery);



var ECTSize = document.querySelectorAll('.ect-turnstile-container');
if (ECTSize) {
	function isViewportSmall() {
		return window.innerWidth <= 425;
	}
	function updateECTSize() {
		ECTSize.forEach(function (element) {
			if (isViewportSmall()) {
				element.setAttribute('data-size', 'compact');
			} else {
				element.setAttribute('data-size', 'normal');
			}
		});
	}
	updateECTSize();
}
window.addEventListener('resize', updateECTSize);