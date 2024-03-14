(function ( $ ) {
	
	/**
	 * WPBakery Shortcode Events
	 */
	window.vc.events.on( 'shortcodeView:ready', function ( model ) {
		lswss_vc_init_shortcodes( model );
	});

	/* Initialize Plugin Shortcode */
	function lswss_vc_init_shortcodes( model ) {

		var modelId, settings;
		modelId		= model.get( 'id' );
		settings	= vc.map[ model.get( 'shortcode' ) ] || false;

		if( settings.base == 'vc_raw_html'
			|| settings.base == 'vc_column_text'
			|| settings.base == 'vc_wp_text'
			|| settings.base == 'vc_message'
			|| settings.base == 'vc_toggle'
			|| settings.base == 'vc_cta'
		) {

			window.vc.frame_window.lswss_logo_slider_init();
		}
	}

})( window.jQuery );