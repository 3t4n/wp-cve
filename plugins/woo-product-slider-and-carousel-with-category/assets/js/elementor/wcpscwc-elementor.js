( function($) {

	'use strict';

	jQuery(window).on('elementor/frontend/init', function() {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/wp-widget-text.default', wcpscwc_elementor_init );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/shortcode.default', wcpscwc_elementor_init );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/text-editor.default', wcpscwc_elementor_init );

		/* Tabs Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/tabs.default', function($scope) {

			if( $scope.find('.wcpscwc-product-slider').length >= 1 ) {
				$scope.find('.elementor-tabs-content-wrapper').addClass('wcpscwc-elementor-tab-wrap');
			} else {
				$scope.find('.elementor-tabs-content-wrapper').removeClass('wcpscwc-elementor-tab-wrap');
			}

			/* Tweak for slick slider */
			$scope.find('.wcpscwc-product-slider').each(function( index ) {

				var slider_id	= $(this).attr('id');
				var slider_conf = $.parseJSON( $(this).attr('data-conf') );
				var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

				$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

				wcpscwc_product_slider_init();

				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
						$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 550);
			});
		});

		/* Accordion Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/accordion.default', function($scope) {

			/* Tweak for slick slider */
			$scope.find('.wcpscwc-product-slider').each(function( index ) {

				var slider_id	= $(this).attr('id');
				var slider_conf = $.parseJSON( $(this).attr('data-conf') );
				var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
				$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

				wcpscwc_product_slider_init();

				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
						$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 300);
			});
		});

		/* Toggle Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/toggle.default', function($scope) {

			/* Tweak for slick slider */
			$scope.find('.wcpscwc-product-slider').each(function( index ) {

				var slider_id = $(this).attr('id');
				var slider_conf = $.parseJSON( $(this).attr('data-conf') );
				var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';
				$('#'+slider_id+' .'+slider_cls).css({'visibility': 'hidden', 'opacity': 0});

				wcpscwc_product_slider_init();

				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id+' .'+slider_cls).slick( 'setPosition' );
						$('#'+slider_id+' .'+slider_cls).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 300);
			});
		});
	});

	/**
	 * Initialize Plugin Functionality
	 */
	function wcpscwc_elementor_init() {
		wcpscwc_product_slider_init();
	}
})(jQuery);