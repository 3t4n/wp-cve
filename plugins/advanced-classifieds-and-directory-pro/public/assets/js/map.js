'use strict';

(function( $ ) {
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		// Init map.
		if ( acadp.map_service === 'osm' ) {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/openstreetmap.js' );
		} else {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/googlemap.js' );
		}

	});

})( jQuery );
