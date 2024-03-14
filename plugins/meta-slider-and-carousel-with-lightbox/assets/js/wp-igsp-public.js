(function($) {

	"use strict";

	/* Slider Initialize */
	wp_igsp_slider_init()

	/* Initialize Carousel */
	wp_igsp_carousel_init();

	/* Magnific Popup */
	wp_igsp_popup_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( WpIsgp.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.msacwl-common-slider').each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wp-igsp-elementor-tab-wrap');

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
		var slider_wrap	= $('#'+ele_control).find('.msacwl-common-slider');

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
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.msacwl-common-slider');

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
		var slider_wrap	= tab_cnt.find('.msacwl-common-slider');

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
		var slider_wrap	= $('#'+ele_control).find('.msacwl-common-slider');

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
		var slider_wrap	= acc_cont.find('.msacwl-common-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr( 'id' );

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
		var slider_wrap	= tab_cont.find('.msacwl-common-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				/* Tweak for slick slider */
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id).slick( 'setPosition' );
					$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 550);
		});
	});

	/* Fusion Builder Compatibility for Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele			= $(this).closest('.fusion-tabs');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.msacwl-common-slider');

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

	/* Fusion Builder Compatibility for Toggles */
	$(document).on('click', '.fusion-accordian .panel-heading a', function() {
		var cls_ele			= $(this).closest('.fusion-accordian');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.msacwl-common-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id).slick( 'setPosition' );
				$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
			}
		});
	});

})(jQuery);

/* Function to Initialize Slider */
function wp_igsp_slider_init() {
	jQuery( '.msacwl-slider' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(WpIsgp.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wp-igsp-fusion-flex');
		}

		var slider_id   	= jQuery(this).attr('id');
		var slider_conf 	= JSON.parse( jQuery(this).closest('.msacwl-slider-wrap').attr('data-conf') );

		jQuery('#'+slider_id).slick({
			lazyLoad		: slider_conf.lazyload,
			speed			: parseInt( slider_conf.speed ),
			autoplaySpeed	: parseInt( slider_conf.autoplay_speed ),
			dots			: ( slider_conf.dots == "true" )		? true : false,
			arrows			: ( slider_conf.arrows == "true" )		? true : false,
			autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
			rtl				: ( WpIsgp.is_rtl == "true" )			? true : false,
			mobileFirst		: ( WpIsgp.is_mobile == 1 )				? true : false,
			infinite		: true,
			adaptiveHeight	: true,
		});
	});
}

/* Function to Initialize Carousel Slider */
function wp_igsp_carousel_init() {
	jQuery( '.msacwl-carousel' ).each(function( index ) {

		if( jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		/* flex Condition */
		if(WpIsgp.is_avada == 1) {
			jQuery(this).closest('.fusion-flex-container').addClass('wp-igsp-fusion-flex');
		}

		var slider_id		= jQuery(this).attr('id');
		var slider_conf		= jQuery.parseJSON( jQuery(this).closest('.msacwl-carousel-wrap').attr('data-conf'));

		jQuery('#'+slider_id).slick({
			lazyLoad		: slider_conf.lazyload,
			slidesToScroll	: parseInt( slider_conf.slide_to_scroll ),
			slidesToShow	: parseInt( slider_conf.slide_to_show ),
			speed			: parseInt( slider_conf.speed ),
			autoplaySpeed	: parseInt( slider_conf.autoplay_speed ),
			dots			: ( slider_conf.dots == "true" )		? true : false,
			arrows			: ( slider_conf.arrows == "true" )		? true : false,
			autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
			rtl				: ( WpIsgp.is_rtl == "true" )			? true : false,
			mobileFirst		: ( WpIsgp.is_mobile == 1 )				? true : false,
			infinite		: true,
			adaptiveHeight  : true,
			responsive: [{
				breakpoint: 1023,
				settings: {
					slidesToShow: (parseInt(slider_conf.slide_to_show) > 3) ? 3 : parseInt(slider_conf.slide_to_show),
					slidesToScroll: 1,
				}
			},{
				breakpoint: 767,
				settings: {
					slidesToShow: (parseInt(slider_conf.slide_to_show) > 2) ? 2 : parseInt(slider_conf.slide_to_show),
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 479,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			},
			{
				breakpoint: 319,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			}]
		});
	});
}

/* Function to Initialize Magnific Popup */
function wp_igsp_popup_init() {
	jQuery( '.msacwl-slider-popup' ).each(function( index ) {

		var popup_id = jQuery(this).attr('id');

		if( typeof('popup_id') !== 'undefined' && popup_id != '' ) {

			var total_item	= jQuery('#'+popup_id+' .msacwl-slide:not(.slick-cloned) a').length;

			jQuery('#'+popup_id).magnificPopup({

				delegate	: '.slick-slide a',
				type		: 'image',
				tLoading	: 'Loading image #%curr%...',
				mainClass	: 'mfp-with-zoom mfp-img-mobile',
				gallery		: {
								enabled				: true,
								navigateByImgClick	: true,
								preload				: [0,1], /* Will preload 0 - before current, and 1 after the current image */
							},
				image		: {
								titleSrc : function(item) {
									return item.el.find('.msacwl-img').attr('data-title');
								}
							},
				callbacks	: {
								markupParse: function(template, values, item) {
									var current_indx 	= item.el.closest('.msacwl-slide').attr('data-item-index');
									values.counter 		= current_indx+' of '+total_item;
								}
							},
			});
		}
	});
}