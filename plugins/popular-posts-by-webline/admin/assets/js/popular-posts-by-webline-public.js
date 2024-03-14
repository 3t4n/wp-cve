(function( $ ) {
	$(document).ready(function(){

		//Initialize slider
		if( $('.wli_popular_posts-slider').length > 0 ) {
			$('.wli_popular_posts-slider').slick( WLIPP_ScriptsData.slider_options );
		}
	});
})( jQuery );