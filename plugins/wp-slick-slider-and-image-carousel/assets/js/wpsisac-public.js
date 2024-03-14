/* Define global Variable */
var wpsisac_next_arrow = '<span class="slick-next slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></span>';
var wpsisac_prev_arrow = '<span class="slick-prev slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></span>';

( function( $ ) {

	"use strict";

	/* Slick Slider Initialize */
	wpsisac_slick_slider_init();

	/* Slick Carousel Slider Initialize */
	wpsisac_slick_carousel_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( Wpsisac.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.wpsisac-slick-init').each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wpsisac-elementor-tab-wrap');

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
	}

	$(document).on('click', '.elementor-tab-title', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wpsisac-slick-init');

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

	/* SiteOrigin Compatibility For Accordion Panel */
	$(document).on('click', '.sow-accordion-panel', function() {

		var ele_control	= $(this).attr('data-anchor');
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.wpsisac-slick-init');

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
		var slider_wrap	= tab_cnt.find('.wpsisac-slick-init');

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

	/* Beaver Builder Compatibility for Accordion and Tabs */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wpsisac-slick-init');

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

	/* Divi Builder Compatibility for Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		var acc_cont	= $(this).find('.et_pb_toggle_content');
		var slider_wrap	= acc_cont.find('.wpsisac-slick-init');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');

			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id).slick( 'setPosition' );
			}
		});
	});

	/* Divi Builder Compatibility for Tabs */
	$('.et_pb_tabs_controls li a').on('click', function() {
		var cls_ele		= $(this).closest('.et_pb_tabs');
		var tab_cls		= $(this).closest('li').attr('class');
		var tab_cont	= cls_ele.find('.et_pb_all_tabs .'+tab_cls);
		var slider_wrap	= tab_cont.find('.wpsisac-slick-init');

		setTimeout(function() {

			/* Tweak for slick slider */
			$( slider_wrap ).each(function( index ) {
				var slider_id = $(this).attr('id');
				$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			});
		}, 550);
	});

	/* Fusion Builder Compatibility for Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele		= $(this).closest('.fusion-tabs');
		var tab_id		= $(this).attr('href');
		var tab_cont	= cls_ele.find(tab_id);
		var slider_wrap	= tab_cont.find('.wpsisac-slick-init');

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

	/* Fusion Builder Compatibility for Toggles */
	$(document).on('click', '.fusion-accordian .panel-heading a', function() {
		var cls_ele		= $(this).closest('.fusion-accordian');
		var tab_id		= $(this).attr('href');
		var tab_cont	= cls_ele.find(tab_id);
		var slider_wrap	= tab_cont.find('.wpsisac-slick-init');

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

})( jQuery );

/* Function to Initialize Slick Slider */
function wpsisac_slick_slider_init() { 

	/* For Slider */
	jQuery( '.wpsisac-slick-slider' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(Wpsisac.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wpsisac-fusion-flex');
		}

		var slider_id   	= jQuery(this).attr('id');
		var slider_conf 	= JSON.parse( jQuery(this).closest('.wpsisac-slick-slider-wrp').attr('data-conf'));

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			jQuery('#'+slider_id).slick({
				slidesToShow	: 1,
				slidesToScroll	: 1,
				adaptiveHeight 	: false,
				lazyLoad		: slider_conf.lazyload,
				speed			: parseInt( slider_conf.speed ),
				autoplaySpeed	: parseInt( slider_conf.autoplay_interval ),
				dots			: ( slider_conf.dots == "true" )		? true : false,
				arrows			: ( slider_conf.arrows == "true")		? true : false,
				autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
				fade 			: ( slider_conf.fade == "true" )		? true : false,
				infinite 		: ( slider_conf.loop == "true" )		? true : false,
				pauseOnHover    : ( slider_conf.hover_pause == "true" )	? true : false,
				rtl             : ( slider_conf.rtl == "true" )			? true : false,
				nextArrow		: wpsisac_next_arrow,
				prevArrow		: wpsisac_prev_arrow,
			});
		}
	});
}

/* Function to Initialize Slick Carousel Slider */
function wpsisac_slick_carousel_init() {

	/* For Carousel Slider */
	jQuery( '.wpsisac-slick-carousal' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(Wpsisac.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wpsisac-fusion-flex');
		}

		var slider_id   = jQuery(this).attr('id');
		var slider_conf = JSON.parse( jQuery(this).closest('.wpsisac-slick-carousal-wrp').attr('data-conf'));

		jQuery('#'+slider_id).slick({
				centerPadding	: '0px',
				lazyLoad		: slider_conf.lazyload,
				speed			: parseInt( slider_conf.speed ),
				slidesToShow	: parseInt( slider_conf.slidestoshow ),
				autoplaySpeed	: parseInt( slider_conf.autoplay_interval ),
				slidesToScroll	: parseInt( slider_conf.slidestoscroll ),
				dots			: ( slider_conf.dots == "true" )			? true : false,
				arrows			: ( slider_conf.arrows == "true" )			? true : false,
				autoplay		: ( slider_conf.autoplay == "true" )		? true : false,
				infinite 		: ( slider_conf.loop == "true" )			? true : false,
				pauseOnHover    : ( slider_conf.hover_pause == "true" )		? true : false,
				centerMode 		: ( slider_conf.centermode == "true" )		? true : false,
				variableWidth 	: ( slider_conf.variablewidth == "true" )	? true : false,
				rtl             : ( slider_conf.rtl == "true") 				? true : false,
				nextArrow		: wpsisac_next_arrow,
				prevArrow		: wpsisac_prev_arrow,
				mobileFirst    	: ( Wpsisac.is_mobile == 1 ) 				? true : false,
				responsive 		: [{
					breakpoint 	: 1023,
					settings 	: {
						slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 3) ? 3 : parseInt(slider_conf.slidestoshow),
						slidesToScroll 	: 1,
					}
				},{
					breakpoint	: 767,
					settings	: {
						slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 3) ? 3 : parseInt(slider_conf.slidestoshow),
						centerMode 		: (slider_conf.centermode) == "true" ? true : false,
						slidesToScroll 	: 1,
					}
				},{
					breakpoint	: 639,
					settings	: {
						slidesToShow 	: 1,
						slidesToScroll 	: 1,
						dots 			: false,
						centerMode 		: true,
						variableWidth 	: false,
					}
				},{
					breakpoint	: 479,
					settings	: {
						slidesToShow 	: 1,
						slidesToScroll 	: 1,
						dots 			: false,
						centerMode 		: false,
						variableWidth 	: false,
					}
				},{
					breakpoint	: 319,
					settings	: {
						slidesToShow 	: 1,
						slidesToScroll 	: 1,
						dots 			: false,
						centerMode 		: false,
						variableWidth 	: false,
					}
				}]
		});
	});
}