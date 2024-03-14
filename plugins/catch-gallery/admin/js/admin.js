(function ($) {
	'use strict';
	$(function () {
		// Tabs
		$('.catch_widget_settings .nav-tab-wrapper a').on(
			'click',
			function (e) {
				e.preventDefault();

				if (!$(this).hasClass('ui-state-active')) {
					$('.nav-tab').removeClass('nav-tab-active');

					$('.wpcatchtab').removeClass('active').fadeOut(0);

					$(this).addClass('nav-tab-active');

					var anchorAttr = $(this).attr('href');

					$(anchorAttr).addClass('active').fadeOut(0).fadeIn(500);
				}
			}
		);

		/* CPT switch */
		$('.ctp-switch').on('click', function () {
			var loader = $(this).parent().next();

			loader.show();

			var main_control = $(this);
			var data = {
				action: 'ctp_switch',
				value: this.checked,
				security: $('#ctp_tabs_nonce').val(),
				option_name: main_control.attr('rel'),
			};

			$.post(ajaxurl, data, function (response) {
				response = $.trim(response);

				if ('1' == response) {
					main_control.parent().parent().addClass('active');
					main_control.parent().parent().removeClass('inactive');
				} else if ('0' == response) {
					main_control.parent().parent().addClass('inactive');
					main_control.parent().parent().removeClass('active');
				} else {
					alert(response);
				}
				loader.hide();
			});
		});
		/* CPT switch End */
	});

	// jQuery Match Height init for sidebar spots
	$(document).ready(function () {
		$(
			'.catch-sidebar-spot .sidebar-spot-inner, .col-2 .catch-lists li, .col-3 .catch-lists li, .catch-modules'
		).matchHeight();
	});
	// jQuery UI Tooltip initializaion
	$(document).ready(function () {
		$('.tooltip').tooltip();
	});
})(jQuery);
