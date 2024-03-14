(function($) {
$(function(){

	a3_RSlider_Frontend = {

		setHeightProportional: function () {
			$(document).find( '.a3-rslider-container' ).each( function() {
				var slider_id = $(this).attr( 'slider-id' );
				var max_height = $(this).attr( 'max-height' );
				var width_of_max_height = $(this).attr( 'width-of-max-height' );
				var is_responsive = $(this).attr( 'is-responsive' );
				var is_tall_dynamic = $(this).attr( 'is-tall-dynamic' );
				if ( is_responsive == '1' && is_tall_dynamic == '1' ) {

					var a3_rslider_container_width = $(this).width();
					var width_of_max_height = parseInt( width_of_max_height );
					var a3_rslider_container_height = parseInt( max_height );
					if( width_of_max_height > a3_rslider_container_width ) {
						var ratio = width_of_max_height / a3_rslider_container_width;
						a3_rslider_container_height = a3_rslider_container_height / ratio;
					}
					$(this).find( '.a3-cycle-slideshow' ).css({ height: a3_rslider_container_height });

				}
			});
		},

		clickPauseResumEvent: function () {
			$(document).on( 'cycle-paused', '.a3-cycle-slideshow', function( event, opts ) {
				$(this).find( '.cycle-pause' ).hide();
				$(this).find( '.cycle-play' ).show();
			});
			$(document).on( 'cycle-resumed', '.a3-cycle-slideshow', function( event, opts ) {
				$(this).find( '.cycle-pause' ).show();
				$(this).find( '.cycle-play' ).hide();
			});
		}
	}

	a3_RSlider_Frontend.clickPauseResumEvent();

	//a3_RSlider_Frontend.setHeightProportional();
	$( window ).on('resize', function() {
		//a3_RSlider_Frontend.setHeightProportional();
	});

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
				$(".cycle-pre-initialized").find('.a3-cycle-lazy-hidden').remove();
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
	} else if($.fn.lazyLoadXT !== undefined ) {
		$(".a3-cycle-slideshow img.a3-rslider-image").on('lazyload', function(){
			var current_cycle_slidershow = $(this).parents('.a3-cycle-slideshow');
			if ( ! current_cycle_slidershow.hasClass('a3-cycle-reinited') ) {
				current_cycle_slidershow.cycle('reinit');
				current_cycle_slidershow.addClass('a3-cycle-reinited');
			}
		});
	}
});
})(jQuery);
