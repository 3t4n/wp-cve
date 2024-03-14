(function( $ ) {
	'use strict';
	
	// The DOM needs to be fully loaded (including graphics, iframes, etc)
	$(window).load(function() {

		// Maximum value for the progressbar
		var winHeight = $(window).height(),
		docHeight = $(document).height();
		var max = docHeight - winHeight;
		$('.catchProgressbar').attr('max', max);

		// Inital value (if the page is loaded within an anchor)
		var value = $(window).scrollTop();
		$('.catchProgressbar').attr('value', value);
		// Maths & live update of progressbar value
		$(document).on('scroll', function() {
			value = $(window).scrollTop();
			$('.catchProgressbar').attr('value', value);
		});
	});
	
})( jQuery );
