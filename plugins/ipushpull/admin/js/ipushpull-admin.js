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

	$(function(){
		var wpcontent = $('#wpbody-content');
		if(wpcontent){
			wpcontent.css('padding',0);
		}
		$(window).resize(function(){
			resize()
		});
		function resize(){
			var wpadminbar = $('#wpadminbar') ? $('#wpadminbar').outerHeight() : 0;
			var wpfooter = $('#wpfooter') ? $('#wpfooter').outerHeight() : 0;
			var wpnag = $('#wpbody-content .update-nag') ? $('#wpbody-content .update-nag').outerHeight() : 0;
			var h = $(document).height() - 65 - wpfooter - wpadminbar - wpnag;
			$('#ipp-wrap iframe').height(h);
		}
		resize();
	});

})( jQuery );
