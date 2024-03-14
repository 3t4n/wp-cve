/* Define global Variable */
var wphtsp_next_arrow = '<span class="slick-next slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></span>';
var wphtsp_prev_arrow = '<span class="slick-prev slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></span>';

( function($) {

	"use strict";

	/* Timeline Slider Initialize */
	wpostahs_timeline_slider_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( Wpostahs.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.wpostahs-slider-nav').each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wpostahs-elementor-tab-wrap');

				var slider_nav_id		= $(this).attr( 'id' );
				var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

				$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
				$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

				setTimeout(function() {
					if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
						$('.'+wphtsp_slider_id).slick( 'setPosition' );
						$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
					}

					if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
						$('#'+slider_nav_id).slick( 'setPosition' );
						$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 350);
			});
		});
	}

	/* Elementor Compatibility */
	$(document).on('click', '.elementor-tab-title', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr( 'id' );
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
			$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
				}

				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 350);
		});
	});

	/* SiteOrigin Compatibility For Accordion Panel */
	$(document).on('click', '.sow-accordion-panel', function() {

		var ele_control	= $(this).attr('data-anchor');
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr('id');
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
				$('.'+wphtsp_slider_id).slick( 'setPosition' );
			}

			if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
				$('#'+slider_nav_id).slick( 'setPosition' );
			}
		});
	});

	/* SiteOrigin Compatibility for Tab Panel */
	$(document).on('click focus', '.sow-tabs-tab', function() {
		var sel_index	= $(this).index();
		var cls_ele		= $(this).closest('.sow-tabs');
		var tab_cnt		= cls_ele.find('.sow-tabs-panel').eq( sel_index );
		var slider_wrap	= tab_cnt.find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr('id');
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
			$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {

				/* Tweak for slick slider */
				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
				}

				/* Tweak for slick slider */
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 300);
		});
	});

	/* Beaver Builder Compatibility for Accordion and Tabs */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wpostahs-slider-nav');

		/* Tweak for filter */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr( 'id' );
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
			$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				/* Tweak for slick slider */
				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
				}

				/* Tweak for slick slider */
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 300);
		});
	});

	/* Divi Builder Compatibility for Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		var acc_cont	= $(this).find('.et_pb_toggle_content');
		var slider_wrap	= acc_cont.find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr( 'id' );
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
				$('.'+wphtsp_slider_id).slick( 'setPosition' );
			}

			if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
				$('#'+slider_nav_id).slick( 'setPosition' );
			}
		});
	});

	/* Divi Builder Compatibility for Tabs */
	$('.et_pb_tabs_controls li a').click( function() {
		var cls_ele		= $(this).closest('.et_pb_tabs');
		var tab_cls		= $(this).closest('li').attr('class');
		var tab_cont	= cls_ele.find('.et_pb_all_tabs .'+tab_cls);
		var slider_wrap	= tab_cont.find('.wpostahs-slider-nav');

		setTimeout(function() {

			/* Tweak for slick slider */
			$( slider_wrap ).each(function( index ) {

				var slider_nav_id		= $(this).attr('id');
				var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

				$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
				$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

				/* Tweak for slick slider */
				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
				}

				/* Tweak for slick slider */
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			});
		}, 550);
	});

	/* Fusion Builder Compatibility for Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele		= $(this).closest('.fusion-tabs');
		var tab_id		= $(this).attr('href');
		var tab_cont	= cls_ele.find(tab_id);
		var slider_wrap	= tab_cont.find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr('id');
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
			$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {

				/* Tweak for slick slider */
				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_nav_id).slick( 'setPosition' );
				}

				/* Tweak for slick slider */
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
				}

			}, 200);
		});
	});

	/* Fusion Builder Compatibility for Toggles */
	$(document).on('click', '.fusion-accordian .panel-heading a', function() {
		var cls_ele		= $(this).closest('.fusion-accordian');
		var tab_id		= $(this).attr('href');
		var tab_cont	= cls_ele.find(tab_id);
		var slider_wrap	= tab_cont.find('.wpostahs-slider-nav');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_nav_id		= $(this).attr('id');
			var wphtsp_slider_id	= $(this).attr( 'data-slider-nav-for' );

			$('#'+slider_nav_id).css({'visibility': 'hidden', 'opacity': 0});
			$('.'+wphtsp_slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				/* Tweak for slick slider */
				if( typeof(slider_nav_id) !== 'undefined' && slider_nav_id != '' ) {
					$('#'+slider_nav_id).slick( 'setPosition' );
					$('#'+slider_nav_id).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_nav_id).slick( 'setPosition' );
				}

				/* Tweak for slick slider */
				if( typeof(wphtsp_slider_id) !== 'undefined' && wphtsp_slider_id != '' ) {
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
					$('.'+wphtsp_slider_id).css({'visibility': 'visible', 'opacity': 1});
					$('.'+wphtsp_slider_id).slick( 'setPosition' );
				}
			}, 200);
		});
	});

})(jQuery);

function wpostahs_timeline_slider_init() {

	// Initialize slick slider
	jQuery( '.wpostahs-slider-nav' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		// Flex Condition
		if(Wpostahs.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wpostahs-fusion-flex');
		}

		var slider_id		= jQuery(this).attr('id');
		var slider_nav_id	= jQuery(this).attr('data-slider-nav-for');
		var slider_conf		= JSON.parse( jQuery(this).closest('.wpostahs-slider-wrp').find('.wpostahs-slider-conf').attr('data-conf') );

		// For Navigation
		if( typeof(slider_nav_id) != 'undefined' && slider_nav_id != '' ) {
			nav_id = '.'+slider_nav_id;
		}

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {

			jQuery('.'+slider_nav_id).slick({
				lazyload		: slider_conf.lazyload,
				dots			: ( slider_conf.dots == "true" ) ? true : false,
				infinite		: ( slider_conf.loop == "true" ) ? true : false,
				arrows			: false,
				speed			: parseInt(slider_conf.speed),
				autoplay		: ( slider_conf.autoplay 	== "true" ) ? true : false,
				fade			: ( slider_conf.fade 		== "true" ) ? true : false,
				autoplaySpeed	: parseInt(slider_conf.autoplayInterval),
				asNavFor		: '#'+slider_id,
				slidesToShow	: 1,
				mobileFirst		: ( Wpostahs.is_mobile == 1 ) 	? true : false,
				rtl				: ( slider_conf.rtl == "true" ) ? true : false,
				slidesToScroll	: 1,
				adaptiveHeight	: true
			});
		}

		// For Navigation
		if( typeof(slider_nav_id) != 'undefined' ) {

			jQuery('#'+slider_id).slick({
				lazyload		: slider_conf.lazyload,
				slidesToShow	: parseInt(slider_conf.slidestoshow),
				slidesToScroll	: 1,
				infinite		: (slider_conf.loop) == "true" ? true : false,
				asNavFor		: nav_id,
				arrows			: (slider_conf.arrows) == "true" ? true : false,
				dots			: false,
				speed			: parseInt(slider_conf.speed),
				centerMode		: (slider_conf.centermode) == "true" ? true : false,
				rtl				: (slider_conf.rtl == "true") ? true : false,
				focusOnSelect	: true,
				centerPadding	: '10px',
				nextArrow 		: wphtsp_next_arrow,
				prevArrow 		: wphtsp_prev_arrow,
				responsive		: [
					{
						breakpoint: 768,
						settings: {
							arrows: true,
							centerMode: true,
							centerPadding: '10px',
							slidesToShow: 3
						}
					},
					{
						breakpoint: 480,
						settings: {
							arrows: true,
							centerMode: true,
							centerPadding: '10px',
							slidesToShow: 1
						}
					}
				]
			});
		}
	});
}