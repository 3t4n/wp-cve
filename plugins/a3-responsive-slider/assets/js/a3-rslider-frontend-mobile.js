(function($) {
$(function(){
	$( '.a3-cycle-slideshow' ).on( 'cycle-before', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
		$( outgoingSlideEl ).find('iframe').each( function(){
			origin_src = $(this).attr( 'origin_src' );

			$(this).attr( 'src', origin_src );
		});

		$( incomingSlideEl ).find('iframe').each( function(){
			origin_src = $(this).attr( 'origin_src' );
			autoplay = $(this).data( 'autoplay' );
			if ( 1 == autoplay ) {

				$(this).attr( 'src', origin_src + '&autoplay=1' );
			}
		});
	});
	
	if($.fn.lazyLoadXT !== undefined && a3_rslider_frontend_params.enable_lazyload == 1 ) {
		function removeLazyHidden(){
			var myVar = setInterval( function(){
				$(".cycle-pre-initialized").find('div.a3-cycle-lazy-hidden').remove();
				clearInterval(myVar);
			}, 700 );
		}
		$(".a3-cycle-slideshow img.a3-rslider-image").on('lazyload', function(){
			$(this).parents('.a3-cycle-slideshow').on( 'cycle-pre-initialize', function( event, opts ) {
				$(this).parent().addClass('cycle-pre-initialized');
				removeLazyHidden();
			});
			$(this).parents('.a3-cycle-slideshow').cycle();
		}).lazyLoadXT();
	}
});
})(jQuery);
