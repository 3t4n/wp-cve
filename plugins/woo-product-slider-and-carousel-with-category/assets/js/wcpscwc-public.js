( function( $ ) {

	"use strict";

	wcpscwc_product_slider_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( Wcpscwc.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.wcpscwc-product-slider').each(function( index ) {

				/* Tweak for Vertical Tab */
				$(this).closest('.elementor-tabs-content-wrapper').addClass('wcpscwc-elementor-tab-wrap');

				var slider_id	= $(this).attr( 'id' );
				var slider_conf = JSON.parse( $(this).attr('data-conf') );
				var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
				$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

				setTimeout(function() {
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
						$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 350);
			});
		});
	}

	$(document).on('click', '.elementor-tab-title', function() {

		var ele_control	= $(this).attr('aria-controls');
		var slider_wrap	= $('#'+ele_control).find('.wcpscwc-product-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {
			var slider_id = $(this).attr('id');
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
			$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 350);
		});
	});

	/* SiteOrigin Compatibility For Accordion Panel */
	$(document).on('click', '.sow-accordion-panel', function() {

		var ele_control	= $(this).attr('data-anchor');
		var slider_wrap	= $('#accordion-content-'+ele_control).find('.wcpscwc-product-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr( 'id' );
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

			/* Tweak for slick slider */
			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
			}
		});
	});

	/* SiteOrigin Compatibility for Tab Panel */
	$(document).on('click focus', '.sow-tabs-tab', function() {
		var sel_index	= $(this).index();
		var cls_ele		= $(this).closest('.sow-tabs');
		var tab_cnt		= cls_ele.find('.sow-tabs-panel').eq( sel_index );
		var slider_wrap	= tab_cnt.find('.wcpscwc-product-slider');

		/* Tweak for slick slider */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr( 'id' );
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 350);
		});
	});

	/* Beaver Builder Compatibility for Accordion and Tabs */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control		= $(this).attr('aria-controls');
		var slider_wrap		= $('#'+ele_control).find('.wcpscwc-product-slider');

		/* Tweak for slick slider default */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr( 'id' );
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
			$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
				}
			}, 300);
		});
	});

	/* Divi Builder Compatibility for Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		if( $(this).hasClass('et_pb_toggle_open') ) {
			return false;
		}

		var acc_cont		= $(this).find('.et_pb_toggle_content');
		var slider_wrap		= acc_cont.find('.wcpscwc-product-slider');

		/* Tweak for slick slider default */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr( 'id' );
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

			if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
				$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
			}
		});
	});

	/* Divi Builder Compatibility for Tabs */
	$('.et_pb_tabs_controls li a').on('click', function() {
		var cls_ele			= $(this).closest('.et_pb_tabs');
		var tab_cls			= $(this).closest('li').attr('class');
		var tab_cont		= cls_ele.find('.et_pb_all_tabs .'+tab_cls);
		var slider_wrap		= tab_cont.find('.wcpscwc-product-slider');

		setTimeout(function() {
			/* Tweak for slick slider default */
			$( slider_wrap ).each(function( index ) {

				var slider_id	= $(this).attr('id');
				var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
				var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
				$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

				/* Tweak for slick slider */
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
				}
			});
		}, 550);
	});

	/* Fusion Builder Compatibility for Tabs */
	$(document).on('click', '.fusion-tabs li .tab-link', function() {
		var cls_ele			= $(this).closest('.fusion-tabs');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.wcpscwc-product-slider');

		/* Tweak for slick slider default */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr('id');
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
			$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

			setTimeout(function() {
				/* Tweak for slick slider */
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
				}
			}, 200);
		});
	});

	/* Fusion Builder Compatibility for Toggles */
	$(document).on('click', '.fusion-accordian .panel-heading a', function() {
		var cls_ele			= $(this).closest('.fusion-accordian');
		var tab_id			= $(this).attr('href');
		var tab_cont		= cls_ele.find(tab_id);
		var slider_wrap		= tab_cont.find('.wcpscwc-product-slider');

		/* Tweak for slick slider default */
		$( slider_wrap ).each(function( index ) {

			var slider_id	= $(this).attr('id');
			var slider_conf = JSON.parse( slider_wrap.attr('data-conf') );
			var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
			$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

			/* Tweak for slick slider */
			setTimeout(function() {
				if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
					$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
				}
			}, 200);
		});
	});

})( jQuery );

/* Function to Initialize Product Slider */
function wcpscwc_product_slider_init() {

	jQuery( '.wcpscwc-product-slider' ).each(function( index ) {

		var slider_id   = jQuery(this).attr('id');
		var slider_conf = JSON.parse( jQuery(this).attr('data-conf') );
		var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

		if( jQuery('#'+slider_id+' .'+slider_cls).hasClass('slick-initialized') ) {
			return;
		}

		// Flex Condition
		if( Wcpscwc.is_avada == 1 ) {
			jQuery(this).closest('.fusion-flex-container').addClass('wcpscwc-fusion-flex');
		}

		jQuery('#'+slider_id+' .'+slider_cls).slick({
			dots			: ( slider_conf.dots == "true" )		? true : false,
			infinite		: ( slider_conf.loop == "true" )		? true : false,
			arrows			: ( slider_conf.arrows == "true" )		? true : false,
			autoplay		: ( slider_conf.autoplay == "true" )	? true : false,
			speed			: parseInt( slider_conf.speed ),
			autoplaySpeed	: parseInt( slider_conf.autoplay_speed ),
			slidesToShow	: parseInt( slider_conf.slide_to_show ),
			slidesToScroll	: parseInt( slider_conf.slide_to_scroll ),
			rtl             : ( slider_conf.rtl == "true" ) ? true : false,
			responsive: [{
				breakpoint: 1023,
				settings: {
					slidesToShow	: (parseInt( slider_conf.slide_to_show ) > 3) ? 3 : parseInt( slider_conf.slide_to_show ),
					slidesToScroll	: parseInt( slider_conf.slide_to_scroll ),
				}
			},{
				breakpoint: 767,
				settings: {
					slidesToShow	: (parseInt( slider_conf.slide_to_show ) > 2) ? 2 : parseInt( slider_conf.slide_to_show ),
					slidesToScroll	: 1
				}
			},{
				breakpoint: 479,
				settings: {
					slidesToShow	: 1,
					slidesToScroll	: 1,
					dots			: false
				}
			},{
				breakpoint: 319,
				settings: {
					slidesToShow	: 1,
					slidesToScroll	: 1,
					dots			: false
				}
			}]
		});
	});
}