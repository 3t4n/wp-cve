(function ($) {
	'use strict';
	// Reload extensions action. Used to clear the transients and reload the extensions.
	$(document).ready(function () {
		$('#reviveso-reload-extensions').click(function (evt) {
			evt.preventDefault();
			$(this).addClass('updating-message');

			$.ajax(
				{
					method: 'POST',
					url   : ajaxurl,
					data  : {
						action: 'reviveso_reload_extensions',
						nonce : $(this).data('nonce'),
					},
				}
			).done(function (msg) {
				location.reload();
			});
		});
	});
})(jQuery);

/**
 * Function to install/activate/deactivate Revive.so's free addons
 */
(function (wp, $) {
	'use strict';

	if (!wp) {
		return;
	}

	function revivesoActivatePlugin(url) {
		$.ajax(
			{
				async   : true,
				type    : 'GET',
				dataType: 'html',
				url     : url,
				success : function () {
					location.reload();
				},
			}
		);
	}

	$(document).on(
		'click',
		'.reviveso-addons-container .reviveso-addon .reviveso-addon-actions > a',
		function (e) {
			// Don't do anything if the link has the 'no-js' class
			if (!$(this).hasClass('no-js')) {
				const url       = $(this).data('url'),
					  action    = $(this).data('action'),
					  slug      = $(this).data('slug'),
					  info_span = $(this).parent().find('.reviveso-addon-info');

				e.preventDefault();
				switch (action) {
					case 'install':
						info_span.html(revivesoAddons.installing_text);
						break;
					case 'activate':
						info_span.html(revivesoAddons.activating_text);
						break;
					case 'deactivate':
						info_span.html(revivesoAddons.deactivating_text);
						break;
				}

				if ('install' === action) {

					const args = {
						slug   : slug,
						success: (response) => {
							revivesoActivatePlugin(response.activateUrl);
						},
					};

					wp.updates.installPlugin(args);
				} else {
					revivesoActivatePlugin(url);
				}
			}
		}
	);
})(window.wp, jQuery);
