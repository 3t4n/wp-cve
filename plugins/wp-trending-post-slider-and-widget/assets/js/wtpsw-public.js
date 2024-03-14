/* Define global Variable */
var wtpsw_next_arrow = '<span class="slick-next slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M69.8437,43.3876,33.8422,13.3863a6.0035,6.0035,0,0,0-7.6878,9.223l30.47,25.39-30.47,25.39a6.0035,6.0035,0,0,0,7.6878,9.2231L69.8437,52.6106a6.0091,6.0091,0,0,0,0-9.223Z"/></svg></span>';
var wtpsw_prev_arrow = '<span class="slick-prev slick-arrow" data-role="none" tabindex="0" role="button"><svg fill="currentColor" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg"><title/><path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z"/></svg></span>';

( function($) {

	"use strict";

	/* Post views count ajax */
	if( Wtpsw.post_view_count != 0 ) {

		var data = {
					action		: 'wtpsw_post_view_count',
					is_ajax		: 1,
					post_id		: Wtpsw.post_view_count,
					nonce		: Wtpsw.data_nonce,
				};

		$.post(Wtpsw.ajaxurl,data,function(response) {
			/* Do Response Process */
		});
	}

	/* For Trending Post Slider */
	wtpsw_trending_slider_init();

	/* For Trending Post carousel */
	wtpsw_trending_carousel_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( Wtpsw.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.wtpsw-post-slider-init').each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wtpsw-elementor-tab-wrap');

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
		var slider_wrap	= $('#'+ele_control).find('.wtpsw-post-slider-init');

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


	/* Beaver Builder Compatibility for Accordion and Tabs */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wtpsw-post-slider-init');

		/* Tweak for filter */
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
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.wtpsw-post-slider-init');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');

			/* Tweak for slick slider */
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
		var slider_wrap	= tab_cnt.find('.wtpsw-post-slider-init');

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
		var slider_wrap	= acc_cont.find('.wtpsw-post-slider-init');

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
		var slider_wrap	= tab_cont.find('.wtpsw-post-slider-init');

		setTimeout(function() {

			/* Tweak for slick slider */
			$( slider_wrap ).each(function( index ) {

				var slider_id = $(this).attr('id');

				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
				}
			});
		}, 550);
	});

	/* Fusion Builder Compatibility for Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele		= $(this).closest('.fusion-tabs');
		var tab_id		= $(this).attr('href');
		var tab_cont	= cls_ele.find(tab_id);
		var slider_wrap	= tab_cont.find('.wtpsw-post-slider-init');

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
		var slider_wrap	= tab_cont.find('.wtpsw-post-slider-init');

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

})(jQuery);

/* Function to Initialize Featured Post Slider */
function wtpsw_trending_slider_init() {
	/* For Trending Post Slider */
	jQuery( '.wtpsw-post-slider' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(Wtpsw.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wtpsw-fusion-flex');
		}

		var slider_id	= jQuery(this).attr('id');
		var slider_conf	= jQuery.parseJSON( jQuery(this).attr('data-conf'));

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			jQuery('#'+slider_id).slick({
				infinite		: true,
				slidesToShow	: 1,
				slidesToScroll	: 1,
				dots			: ( slider_conf.dots == "true" )		? true : false,
				arrows			: ( slider_conf.arrows == "true" )		? true : false,
				autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
				speed			: parseInt(slider_conf.speed),
				autoplaySpeed	: parseInt( slider_conf.autoplayinterval ),
				nextArrow 		: wtpsw_next_arrow,
				prevArrow 		: wtpsw_prev_arrow,
				rtl				: ( Wtpsw.is_rtl == 1 ) ? true : false,
			});
		}
	});
}

/* Function to Initialize Featured Post Slider */
function wtpsw_trending_carousel_init() {
	/* For Trending Post Carousel */
	jQuery( '.wtpsw-post-carousel' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(Wtpsw.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wtpsw-fusion-flex');
		}

		var slider_id	= jQuery(this).attr('id');
		var slider_conf	= jQuery.parseJSON( jQuery(this).attr('data-conf'));

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			jQuery('#'+slider_id).slick({
				infinite		: true,
				dots			: ( slider_conf.dots == "true" )		? true : false,
				arrows			: ( slider_conf.arrows == "true" )		? true : false,
				autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
				speed			: parseInt( slider_conf.speed ),
				autoplaySpeed	: parseInt( slider_conf.autoplayinterval ),
				slidesToShow	: parseInt( slider_conf.slides_to_show ),
				slidesToScroll	: parseInt( slider_conf.slides_to_scroll ),
				nextArrow 		: wtpsw_next_arrow,
				prevArrow 		: wtpsw_prev_arrow,
				rtl				: ( Wtpsw.is_rtl == 1 )					? true : false,
				mobileFirst		: ( Wtpsw.is_mobile == 1 )				? true : false,
				responsive: [{
					breakpoint: 767,
						settings: {
							slidesToShow: (parseInt(slider_conf.slides_to_show) > 3) ? 3 : parseInt(slider_conf.slides_to_show),
							slidesToScroll: 1,
							dots: true
						}
					},
					{
						breakpoint: 640,
						settings: {
							slidesToShow: (parseInt(slider_conf.slides_to_show) > 2) ? 2 : parseInt(slider_conf.slides_to_show),
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 479,
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
					}
				]
			});
		}
	});
}