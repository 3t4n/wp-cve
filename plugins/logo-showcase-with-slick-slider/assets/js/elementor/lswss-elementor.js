(function ($) {
	"use strict";

	var LswssElementorInit = function () {

		/* Slider */
		lswss_logo_slider_init();

	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/shortcode.default', LswssElementorInit);	
		elementorFrontend.hooks.addAction('frontend/element_ready/text-editor.default', LswssElementorInit);

	});
}(jQuery));