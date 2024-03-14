;( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	const WidgetOwlCarouselHandler = function ($scope, $) {
		const $carouselContainer = $scope.find( '.js-owce-carousel-container' );
		const $carousel = $carouselContainer.find( '.js-owce-carousel' );

		if ( ! $carousel.length ) {
			return;
		}

		const {
			rtl,
			items_count,
			items_count_tablet,
			items_count_mobile,
			items_slideby = 1,
			items_slideby_tablet,
			items_slideby_mobile,
			margin,
			lazyLoad,
			auto_height,
			autoplay,
			autoplay_timeout,
			autoplay_hover_pause,
			mouse_drag,
			touch_drag,
			rewind,
			smart_speed,
			animate_in,
			animate_out,
			nav_mobile,
			dots_mobile,
			loop_mobile,
			margin_mobile,
			nav_tablet,
			dots_tablet,
			loop_tablet,
			margin_tablet,
			nav,
			dots,
			loop,
		} = $carousel.data( 'options' ) || {};

		$carousel.owlCarousel({
			rtl: rtl === 'yes',
			margin: margin,
			lazyLoad: lazyLoad,
			autoHeight: auto_height,
			autoplay: autoplay,
			autoplayTimeout: autoplay_timeout
				? autoplay_timeout
				: 5000,
			autoplayHoverPause: autoplay_hover_pause,
			mouseDrag: mouse_drag,
			touchDrag: touch_drag,
			rewind: rewind,
			smartSpeed: smart_speed,
			slideTransition: 'ease',
			animateIn: animate_in,
			animateOut: animate_out,
			navText: [
				'<i class="eicon-chevron-left" aria-hidden="true"></i>',
				'<i class="eicon-chevron-right" aria-hidden="true"></i>',
			],
			responsiveClass: true,
			responsive: {
				0: {
					items: items_count_mobile || 1,
					slideBy: items_slideby_mobile || items_slideby,
					margin: owce_value_exists( margin_mobile, margin ),
					nav: nav_mobile,
					dots: dots_mobile === null || dots_mobile === 'yes', // elementor return null sometimes for default value - a bug might be
					loop: loop_mobile,
				},
				768: {
					items: items_count_tablet || 2,
					slideBy: items_slideby_tablet || items_slideby,
					margin: owce_value_exists( margin_tablet, margin ),
					nav: nav_tablet,
					dots: dots_tablet === null || dots_tablet === 'yes',
					loop: loop_tablet
				},
				1024: {
					items: items_count || 3,
					slideBy: items_slideby,
					margin: margin,
					nav: nav,
					dots: dots,
					loop: loop
				}
			}
		});

		if ( $( '.js-elementor-not-clickable' ).length ) {
			$( '.js-elementor-not-clickable' ).parent( '.owl-thumb' ).addClass( 'js-elementor-not-clickable' );
		}
	};

	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/owl-carousel-elementor.default',
			WidgetOwlCarouselHandler
		);
	} );

	// helpers
	function owce_value_exists( val, defaultVal = '' ) {
		return val !== undefined && val !== null ? val : defaultVal;
	}
} )( jQuery );
