( function( $ ) {

	"use strict";

	/* Testimonial Slider */
	wtwp_testimonial_slider_init();

	/* Testimonial widget */
	wtwp_testimonial_widget_init();

	/***** Elementor Compatibility Start *****/
	if( Wtwp.elementor_preview == 0 ) {
		jQuery(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$( '.wptww-testimonials-slidelist' ).each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wtwp-elementor-tab-wrap');

				var slider_id = $(this).attr('id');
				$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

				setTimeout(function() {
					if( typeof( slider_id ) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id).slick( 'setPosition' );
						$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 350);
			});

		});
	}

	/* For Tabs, Accordion & Toggle */
	$(document).on('click', '.elementor-tab-title', function() {

		var ele_control		= $(this).attr('aria-controls');
		var slider_wrap		= $('#'+ele_control).find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 350);
		});
	});
	/***** Elementor Compatibility End *****/

	/* Beaver Builder Compatibility for Accordion and Tabs */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 300);
		});
	});

	/* SiteOrigin Compatibility For Accordion Panel */
	$(document).on('click', '.sow-accordion-panel', function() {

		var ele_control	= $(this).attr('data-anchor');
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');

			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id).slick( 'setPosition' );
			}
		});
	});

	/* SiteOrigin Compatibility for Tab Panel */
	$(document).on('click focus', '.sow-tabs-tab', function() {
		var sel_index	= $(this).index();
		var cls_ele		= $(this).closest('.sow-tabs');
		var tab_cnt		= cls_ele.find('.sow-tabs-panel').eq( sel_index );
		var slider_wrap	= tab_cnt.find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 300);
		});
	});

	/***** Divi Builder Compatibility Start *****/
	/* For Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		var acc_cont		= $(this).find('.et_pb_toggle_content');
		var slider_wrap		= acc_cont.find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');

			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id).slick( 'setPosition' );
			}
		});
	});

	/* For Tabs */
	$('.et_pb_tabs_controls li a').on('click', function() {
		
		var cls_ele			= $(this).closest('.et_pb_tabs');
		var tab_cls			= $(this).closest('li').attr('class');
		var tab_cont		= cls_ele.find('.et_pb_all_tabs .'+tab_cls);
		var slider_wrap		= tab_cont.find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');

			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 550);
		});
	});
	/***** Divi Builder Compatibility End *****/

	/***** Fusion Builder Compatibility Start *****/
	/* For Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele			= $(this).closest('.fusion-tabs');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_id).slick( 'setPosition' );
				}
			}, 200);
		});
	});

	/* For Toggles */
	$(document).on('click', '.fusion-accordian .panel-heading a', function() {
		var cls_ele			= $(this).closest('.fusion-accordian');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.wptww-testimonials-slidelist');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_id).slick( 'setPosition' );
				}
			}, 200);
		});
	});
	/***** Fusion Builder Compatibility End *****/

})( jQuery );

/* Function to initialize testimonial Slider */
function wtwp_testimonial_slider_init() {

	/* Testimonial Slider */
	jQuery( '.wptww-testimonials-slidelist' ).each(function( index ) {
		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		var slider_id			= jQuery(this).attr('id');
		var testimonial_conf	= JSON.parse( jQuery(this).closest('.wtwp-testimonials-slider-wrp').attr('data-conf'));

		/* flex Condition */
		if(Wtwp.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wtwp-fusion-flex');
		}

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {

			jQuery('#'+slider_id).slick({
				infinite		: true,
				speed 			: parseInt( testimonial_conf.speed ),
				autoplaySpeed 	: parseInt( testimonial_conf.autoplay_interval ),
				slidesToShow 	: parseInt( testimonial_conf.slides_column ),
				slidesToScroll 	: parseInt( testimonial_conf.slides_scroll ),
				adaptiveHeight	: ( testimonial_conf.adaptive_height == "true" )	? true : false,
				dots			: ( testimonial_conf.dots == "true" )				? true : false,
				arrows			: ( testimonial_conf.arrows == "true" )				? true : false,
				autoplay 		: ( testimonial_conf.autoplay == "true" )			? true : false,
				rtl				: ( testimonial_conf.rtl == "true" )				? true	: false,
				responsive 		: [
				{
					breakpoint: 1023,
					settings: {
						slidesToShow: (parseInt(testimonial_conf.slides_column) > 3) ? 3 : parseInt(testimonial_conf.slides_column),
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: (parseInt(testimonial_conf.slides_column) > 2) ? 2 : parseInt(testimonial_conf.slides_column),
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 319,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}]
			});
		}
	});
}

/* Function to initialize testimonial Widget */
function wtwp_testimonial_widget_init() {

	/* Testimonial widget Slider */
	jQuery( '.wptww-testimonials-slide-widget' ).each(function( index ) {
		
		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		var slider_id			= jQuery(this).attr('id');
		var testimonial_conf	= JSON.parse( jQuery(this).closest('.widget_sp_testimonials').attr('data-conf'));		

		if(Wtwp.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wtwp-fusion-flex');
		}

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {

			jQuery('#'+slider_id).slick({
				infinite		: true,
				slidesToScroll 	: parseInt( testimonial_conf.slides_scroll ),
				slidesToShow 	: parseInt( testimonial_conf.slides_column ),
				autoplaySpeed 	: parseInt( testimonial_conf.autoplay_interval ),
				speed 			: parseInt( testimonial_conf.speed ),
				dots			: ( testimonial_conf.dots == "true" )				? true : false,
				arrows			: ( testimonial_conf.arrows == "true" )				? true : false,
				autoplay 		: ( testimonial_conf.autoplay == "true" )			? true : false,
				adaptiveHeight	: ( testimonial_conf.adaptive_height == "true" )	? true : false,
				responsive 		: [
				{
					breakpoint: 1023,
					settings: {
						slidesToShow: (parseInt(testimonial_conf.slides_column) > 3) ? 3 : parseInt(testimonial_conf.slides_column),
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: (parseInt(testimonial_conf.slides_column) > 2) ? 2 : parseInt(testimonial_conf.slides_column),
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 319,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}]
			});
		}
	});
}