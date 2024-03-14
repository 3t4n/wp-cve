(function( $ ){
	"use strict";

	$( document ).ready(function(){

		// Copy shortcode functionality
		$('.copy-timeline-wp-shortcode').click(function (e) {
			e.preventDefault();
			var gallery_shortcode = $(this).parent().find('input');
			gallery_shortcode.focus();
			gallery_shortcode.select();
			document.execCommand("copy");
			$(this).next('span').text('Shortcode copied');
			$('.copy-timeline-wp-shortcode').not($(this)).parent().find('span').text('');

		});
	});

})(jQuery);