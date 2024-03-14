(function($) {

	"use strict";

	/* News vertical ticker Initialize */
	news_scrolling_slider_init();

})(jQuery);

/* Function to Initialize news vertical ticker */
function news_scrolling_slider_init() {

	/* Initialize news vertical ticker */
	jQuery( '.sp-news-scrolling-slider' ).each(function( index ) {
		var slider_id	= jQuery(this).attr('id');
		var slider_conf	= jQuery(this).attr('data-conf');
		slider_conf		= (slider_conf) ? JSON.parse( slider_conf ) : '';

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {

			jQuery('#'+slider_id).vTicker({
				padding	: 10,
				speed	: parseInt( slider_conf.speed ),
				height	: parseInt( slider_conf.height ),
				pause	: parseInt( slider_conf.pause )
			});
		}
	});
}