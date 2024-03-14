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
	 $(document).ready(function(){
	 	jQuery("#wpdisabler-tabs-wrapper > a").each(function(){
			jQuery(this).on('click',function(){
				jQuery("#wpdisabler-tabs-wrapper > a").removeClass("nav-tab-active");
				jQuery(".ps-tabcontent").removeClass("ps-tabs-active");
				jQuery(this).addClass("nav-tab-active");
				//alert(jQuery(this).prop('id'));
				jQuery(jQuery(this).attr("href")).addClass('wpdisabler-tab-active');
			});
			
		});

		jQuery("a[aria-label='Deactivate WP Disabler']").click(function(e){
			e.preventDefault();
			jQuery(".overlay").css("visibility", "visible");
			jQuery(".overlay").css("opacity", "1");
			
		});

		jQuery(".close").click(function(e){
			e.preventDefault();
			jQuery(".overlay").css("visibility", "hidden");
			jQuery(".overlay").css("opacity", "0");
		});

		jQuery(".deactivate-button").click(function(){
			window.location.href = jQuery("a[aria-label='Deactivate WP Disabler']").attr('href');
		});

	 	

	  	
	});


})( jQuery );
