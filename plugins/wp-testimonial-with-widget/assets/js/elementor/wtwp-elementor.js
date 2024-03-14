( function($) {

	'use strict';

	jQuery(window).on('elementor/frontend/init', function() {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/wp-widget-text.default', wtwp_elementor_init );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/shortcode.default', wtwp_elementor_init );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/text-editor.default', wtwp_elementor_init );

		/* Tabs Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/tabs.default', function( $scope ) {

			if( $scope.find('.wptww-testimonials-slidelist').length >= 1 ) {
				$scope.find('.elementor-tabs-content-wrapper').addClass('wtwp-elementor-tab-wrap');
			} else {
				$scope.find('.elementor-tabs-content-wrapper').removeClass('wtwp-elementor-tab-wrap');
			}

			/* Testimonial Slider */
			$( '.wptww-testimonials-slidelist' ).each(function( index ) {

				var slider_id = $(this).attr('id');
				$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

				wtwp_testimonial_slider_init();
				
				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id).slick( 'setPosition' );
						$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 300);
			});
		});

		/* Accordion Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/accordion.default', function() {

			/* Testimonial Slider */
			$( '.wptww-testimonials-slidelist' ).each(function( index ) {

				var slider_id = $(this).attr('id');
				$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

				wtwp_testimonial_slider_init();
				
				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id).slick( 'setPosition' );
						$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 300);
			});
		});

		/* Toggle Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/toggle.default', function() {

			/* Testimonial Slider */
			$( '.wptww-testimonials-slidelist' ).each(function( index ) {

				var slider_id = $(this).attr('id');
				$('#'+slider_id).css({'visibility': 'hidden', 'opacity': 0});

				wtwp_testimonial_slider_init();
				
				setTimeout(function() {

					/* Tweak for slick slider */
					if( typeof(slider_id) !== 'undefined' && slider_id != '' ) {
						$('#'+slider_id).slick( 'setPosition' );
						$('#'+slider_id).css({'visibility': 'visible', 'opacity': 1});
					}
				}, 300);
			});
		});

		/* Widget Latest Post List/Slider 1 Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wp-widget-sp_testimonials.default', function() {
			wtwp_testimonial_widget_init();
		});

		/**
		 * Initialize Plugin Functionality
		 */
		function wtwp_elementor_init() {
			wtwp_testimonial_slider_init();
		}

	});
})(jQuery);