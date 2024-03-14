( function($) {

	'use strict';

	/* Slider Initialize */
	lswss_logo_slider_init();

})( jQuery );

/* Logo Slider Initialize */
function lswss_logo_slider_init() {
	jQuery( '.lswssp-logo-carousel' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		var slider_id	= jQuery(this).attr('id');
		var logo_conf	= JSON.parse( jQuery(this).attr('data-conf') );

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			jQuery('#'+slider_id).slick({
				pauseOnFocus		: false,
				pauseOnHover		: logo_conf.pause_on_hover,
				slidesToShow		: parseInt(logo_conf.slides_show),
				slidesToScroll		: parseInt(logo_conf.slides_scroll),
				dots				: logo_conf.dots,
				arrows				: logo_conf.arrow,
				autoplay			: logo_conf.autoplay,
				infinite			: logo_conf.loop,
				speed				: parseInt(logo_conf.speed),
				autoplaySpeed		: parseInt(logo_conf.autoplay_speed),
				centerMode			: logo_conf.centermode,
				centerPadding		: parseInt(logo_conf.center_padding)+'px',
				prevArrow			: '<span type="button" class="lswssp-slick-prev">&#10094;</span> ',
				nextArrow			: '<span type="button" class="lswssp-slick-next">&#10095;</span>',
				rtl					: (Lswssp.is_rtl == 1) ? true : false,
				mobileFirst			: (Lswssp.is_mobile == 1) ? true : false,
				responsive: [{
					breakpoint: 767,
					settings: {
							slidesToShow	: parseInt(logo_conf.slide_to_show_ipad),
							slidesToScroll	: 1
						}
					},{
					breakpoint: 639,
					settings: {
							slidesToShow	: parseInt(logo_conf.slide_to_show_tablet),
							slidesToScroll	: 1,
							centerPadding	: '0px'
						}
					},{
					breakpoint: 479,
					settings: {
							slidesToShow	: parseInt(logo_conf.slide_to_show_mobile),
							slidesToScroll	: 1,
							centerPadding	: '0px'
					}
					},{
					breakpoint: 319,
					settings: {
							slidesToShow	: parseInt(logo_conf.slide_to_show_mobile),
							slidesToScroll	: 1,
							centerPadding	: '0px'
					}
				}]
			});
		}
	});
}