(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	/*
		try {
			$('.lgx_app_item_link').tooltipster();
		} catch(e) {
			//console.log('not start');
		}
	*/

	jQuery(document).ready(function ($) {


		if ( $('.lgx_counter_up').length ) {

			var counterUp = window.counterUp["default"]; // import counterUp from "counterup2"
    
			var $counters = $(".lgx_counter_value");
		
			/* Start counting, do this on DOM ready or with Waypoints. */
			$counters.each(function (ignore, counter) {
				var lgx_counter_item 	= $(this);
				var lgxCounterDataAttr = lgx_counter_item.data();
				//console.log(lgxCounterDataAttr);
				var waypoint = new Waypoint( {
					element: $(this),
					handler: function() { 
						counterUp(counter, {
							satrt:500,
							duration:5000,
							delay: 16
						}); 
						this.destroy();
					},
					offset: 'bottom-in-view',
				} );
			});

	}


	if ( $('.lgx_lsw_preloader').length ) {
		$('body').find('.lgx_counter_up_app').each(function () {
			var lgx_lsw_preloader_item = $(this).children('.lgx_lsw_preloader');
			$(document).ready(function() {
				//alert('yes');
				$(lgx_lsw_preloader_item).animate({ opacity: 0 }, 600).remove();
			})
		})
	}

	
	})//DOM

})( jQuery );
