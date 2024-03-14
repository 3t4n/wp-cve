( function($) {

	'use strict';

	jQuery(window).on('elementor/frontend/init', function() {

		/* Latest News vertical scrolling widget */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wp-widget-sp_news_s_widget.default', function() {
			news_scrolling_slider_init();
		});
	});
})(jQuery);