/**
 * Slider init
 * Init bxSlider for Slider Widget
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {

	$('#lrw-slider > .slides').each( function() {

		$.fn.exists = function() {
			return this.length;
		}

		var $$ = $(this);
		var $slides = $$.find('.slide-item');
		var settings = $$.data('settings');

		var parameters = {
		    mode: settings.slidemode,
	    	speed: settings.slidespeed,
		    captions: settings.captions,
		    auto: settings.auto,
		    autoHover: settings.pausehover
		};

		if ( $(settings.slidetype === 'carousel' ).exists() ) {
			parameters.slideWidth = $slides.data('slidewidth');
			parameters.slideMargin = $slides.data('slidemargin');
			parameters.minSlides = settings.minslides;
			parameters.maxSlides = settings.maxslides;
			parameters.moveSlides = settings.moveslides;
		}

		$$.bxSlider( parameters );

	});
});
