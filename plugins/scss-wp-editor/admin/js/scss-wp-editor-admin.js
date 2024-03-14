(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	

})( jQuery );

jQuery(document).ready(function($) {
  	wp.codeEditor.initialize($('#fancy-textarea'), cm_settings);

  	/*Tabbing*/
	jQuery('#tabs li').on('click', function($){
		var el = jQuery(this);
		var tab_name = el.data('tab');
		el.addClass('current').siblings().removeClass('current');
		jQuery(tab_name).addClass('current').siblings().removeClass('current');
	});


	jQuery(".set > a").on("click", function() {
	    if (jQuery(this).hasClass("active")) {
	        jQuery(this).removeClass("active");
	        jQuery(this)
	            .siblings(".content")
	            .slideUp(200);
	        jQuery(this)
				.find("span")
	            .removeClass("dashicons-arrow-down")
	            .addClass("dashicons-arrow-right");
	    } else {
	        jQuery(".set > a")
				.find("span")
	            .removeClass("dashicons-arrow-down")
	            .addClass("dashicons-arrow-right");
			jQuery(this)
				.find("span")
	            .removeClass("dashicons-arrow-right")
	            .addClass("dashicons-arrow-down");
	        jQuery(".set > a").removeClass("active");
	        jQuery(this).addClass("active");
	        jQuery(".content").slideUp(200);
	        jQuery(this)
	            .siblings(".content")
	            .slideDown(200);
	    }
	});
})