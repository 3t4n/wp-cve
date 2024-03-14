(function ( $ ) {
	'use strict';

	window.softTemplateAddonsCore     = {};
	softTemplateAddonsCore.shortcodes = {};

	softTemplateAddonsCore.body         = $( 'body' );
	softTemplateAddonsCore.html         = $( 'html' );
	softTemplateAddonsCore.windowWidth  = $( window ).width();
	softTemplateAddonsCore.windowHeight = $( window ).height();

	$( document ).ready(
		function () {
			softTemplateAddonsCore.scroll = $( window ).scrollTop();
			
		}
	);

	$( window ).resize(
		function () {
			softTemplateAddonsCore.windowWidth  = $( window ).width();
			softTemplateAddonsCore.windowHeight = $( window ).height();
		}
	);


	if ( typeof Object.assign !== 'function' ) {
		Object.assign = function ( target ) {

			if ( target === null || typeof target === 'undefined' ) {
				throw new TypeError( 'Cannot convert undefined or null to object' );
			}

			target = Object( target );
			for ( var index = 1; index < arguments.length; index++ ) {
				var source = arguments[index];

				if ( source !== null ) {
					for ( var key in source ) {
						if ( Object.prototype.hasOwnProperty.call(
							source,
							key
						) ) {
							target[key] = source[key];
						}
					}
				}
			}

			return target;
		};
	}


	var qodefIsInViewport             = {
		check: function ( $element, callback, onlyOnce ) {
			if ( $element.length ) {
				var offset   = typeof $element.data( 'viewport-offset' ) !== 'undefined' ? $element.data( 'viewport-offset' ) : 0.15; // When item is 15% in the viewport
				var observer = new IntersectionObserver(
					function ( entries ) {
						// isIntersecting is true when element and viewport are overlapping
						// isIntersecting is false when element and viewport don't overlap
						if ( entries[0].isIntersecting === true ) {
							callback.call( $element );
							// Stop watching the element when it's initialize
							if ( onlyOnce !== false ) {
								observer.disconnect();
							}
						}
					},
					{ threshold: [offset] }
				);
				observer.observe( $element[0] );
			}
		},
	};
	softTemplateAddonsCore.qodefIsInViewport = qodefIsInViewport;

	/**
	 * Check element images to loaded
	 */
	var qodefWaitForImages             = {
		check: function ( $element, callback ) {
			if ( $element.length ) {
				var images = $element.find( 'img' );

				if ( images.length ) {
					var counter = 0;

					for ( var index = 0; index < images.length; index++ ) {
						var img = images[index];

						if ( img.complete ) {
							counter++;
							if ( counter === images.length ) {
								callback.call( $element );
							}
						} else {
							var image = new Image();

							image.addEventListener(
								'load',
								function () {
									counter++;
									if ( counter === images.length ) {
										callback.call( $element );
										return false;
									}
								},
								false
							);
							image.src = img.src;
						}
					}
				} else {
					callback.call( $element );
				}
			}
		},
	};
	softTemplateAddonsCore.qodefWaitForImages = qodefWaitForImages;

})( jQuery );

(function ( $ ) {
	'use strict';

	$( document ).ready(
		function () {
			softTemplateMasonryLayout.init();
		}
	);

	$( window ).resize(
		function () {
			softTemplateMasonryLayout.reInit();
		}
	);

	$( window ).on(
		'elementor/frontend/init',
		function () {
			if ( elementorFrontend.isEditMode() ) {
				elementor.channels.editor.on(
					'change',
					function () {
						softTemplateMasonryLayout.reInit();
					}
				);
			}
		}
	);

	/**
	 * Init masonry layout
	 */
	var softTemplateMasonryLayout = {
		init: function ( settings ) {
			this.holder = $( '.qodef-layout--qi-masonry' );

			// Allow overriding the default config
			$.extend(
				this.holder,
				settings
			);

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						softTemplateMasonryLayout.createMasonry( $( this ) );
					}
				);
			}
		},
		reInit: function ( settings ) {
			this.holder = $( '.qodef-layout--qi-masonry' );

			// Allow overriding the default config
			$.extend(
				this.holder,
				settings
			);

			if ( this.holder.length ) {
				this.holder.each(
					function () {
						var $masonry            = $( this ).find( '.qodef-grid-inner' ),
							$masonryItem        = $masonry.find( '.qodef-grid-item' ),
							$masonryItemSize    = $masonry.find( '.qodef-qi-grid-masonry-sizer' ).width(),
							$masonryItemSizeGap = parseInt( $masonry.css( 'column-gap' ) );

						$masonryItem.css(
							'width',
							$masonryItemSize
						);

						if ( typeof $masonry.isotope === 'function' && undefined !== $masonry.data( 'isotope' ) ) {

							if ( $( this ).hasClass( 'qodef-items--fixed' ) ) {

								softTemplateMasonryLayout.setFixedImageProportionSize(
									$masonry,
									$masonryItem,
									$masonryItemSize,
									$masonryItemSizeGap
								);
							}

							$masonry.isotope(
								{
									layoutMode: 'packery',
									itemSelector: '.qodef-grid-item',
									percentPosition: true,
									packery: {
										columnWidth: '.qodef-qi-grid-masonry-sizer',
										gutter: $masonryItemSizeGap,
									}
								}
							);
						}
					}
				);
			}
		},
		createMasonry: function ( $holder ) {
			var $masonry            = $holder.find( '.qodef-grid-inner' ),
				$masonryItem        = $masonry.find( '.qodef-grid-item' ),
				$masonryItemSize    = $masonry.find( '.qodef-qi-grid-masonry-sizer' ).width(),
				$masonryItemSizeGap = parseInt( $masonry.css( 'column-gap' ) );

			$masonryItem.css(
				'width',
				$masonryItemSize
			);

			softTemplateAddonsCore.qodefWaitForImages.check(
				$masonry,
				function () {
					if ( typeof $masonry.isotope === 'function' && ! $masonry.hasClass( 'qodef--masonry-init' ) ) {

						if ( $holder.hasClass( 'qodef-items--fixed' ) ) {

							softTemplateMasonryLayout.setFixedImageProportionSize(
								$masonry,
								$masonryItem,
								$masonryItemSize,
								$masonryItemSizeGap
							);
						}

						$masonry.isotope(
							{
								layoutMode: 'packery',
								itemSelector: '.qodef-grid-item',
								percentPosition: true,
								packery: {
									columnWidth: '.qodef-qi-grid-masonry-sizer',
									gutter: $masonryItemSizeGap,
								}
							}
						);
					}

					$masonry.addClass( 'qodef--masonry-init' );
				}
			);
		},
		setFixedImageProportionSize: function ( $holder, $item, size, $gap ) {

			var $squareItem     = $holder.find( '.qodef-item--square' ),
				$landscapeItem  = $holder.find( '.qodef-item--landscape' ),
				$portraitItem   = $holder.find( '.qodef-item--portrait' ),
				$hugeSquareItem = $holder.find( '.qodef-item--huge-square' ),
				isMobileScreen  = softTemplateAddonsCore.windowWidth <= 680;

			if ( ! $holder.parent().hasClass( 'qodef-col-num--1' ) ) {

				$item.css(
					{
						'height': size,
					}
				);

				if ( $landscapeItem.length ) {
					$landscapeItem.css(
						{
							'width': Math.round( (2 * size) + $gap ),
						}
					);
				}

				if ( $portraitItem.length ) {
					$portraitItem.css(
						{
							'height': Math.round( (2 * size) + $gap ),
						}
					);
				}

				if ( $hugeSquareItem.length ) {
					$hugeSquareItem.css(
						{
							'height': Math.round( (2 * size) + $gap ),
							'width': Math.round( (2 * size) + $gap ),
						}
					);
				}

				if ( isMobileScreen ) {

					if ( $landscapeItem.length ) {
						$landscapeItem.css(
							{
								'height': Math.round( size / 2 ),
								'width': Math.round( size ),
							}
						);
					}

					if ( $hugeSquareItem.length ) {
						$hugeSquareItem.css(
							{
								'height': Math.round( size ),
								'width': Math.round( size ),
							}
						);
					}
				}
			} else {

				$item.css(
					{
						'height': size,
					}
				);

				if ( $squareItem.length ) {
					$squareItem.css(
						{
							'width': size,
						}
					);
				}

				if ( $landscapeItem.length ) {
					$landscapeItem.css(
						{
							'height': Math.round( size / 2 ),
						}
					);
				}

				if ( $portraitItem.length ) {
					$portraitItem.css(
						{
							'height': Math.round( (2 * size) ),
						}
					);
				}

				if ( $hugeSquareItem.length ) {
					$hugeSquareItem.css(
						{
							'width': size,
						}
					);
				}
			}
		}
	};

	softTemplateAddonsCore.softTemplateMasonryLayout = softTemplateMasonryLayout;

})( jQuery );


(function ( $ ) {
	'use strict';

	var shortcode = 'soft-template-post-archive';

	softTemplateAddonsCore.shortcodes[shortcode] = {};

	$( document ).ready(
		function () {
			softTemplateCoreResizeIframes.init();
		}
	);

	$( window ).resize(
		function () {
			softTemplateCoreResizeIframes.init();
		}
	);

	/**
	 * Resize oembed iframes
	 */
	var softTemplateCoreResizeIframes = {
		init: function () {
			var $holder = $( '.qodef-blog-shortcode' );

			if ( $holder.length ) {
				softTemplateCoreResizeIframes.resize( $holder );
			}
		},
		resize: function ( $holder ) {
			var $iframe = $holder.find( '.qodef-e-media iframe' );

			if ( $iframe.length ) {
				$iframe.each(
					function () {
						var $thisIframe = $( this ),
							width       = $thisIframe.attr( 'width' ),
							height      = $thisIframe.attr( 'height' ),
							newHeight   = $thisIframe.width() / width * height; // rendered width divided by aspect ratio

						$thisIframe.css( 'height', newHeight );
					}
				);
			}
		}
	};


	softTemplateAddonsCore.shortcodes[shortcode].softTemplateMasonryLayout           = softTemplateAddonsCore.softTemplateMasonryLayout;
	softTemplateAddonsCore.shortcodes[shortcode].softTemplateCoreResizeIframes = softTemplateCoreResizeIframes;

})( jQuery );


(function ( $ ) {
	'use strict';
	$( window ).on(
		'elementor/frontend/init',
		function () {
			softTemplateAddonsElementor.init();
		}
	);

	var softTemplateAddonsElementor = {
		init: function () {
			var isEditMode = Boolean( elementorFrontend.isEditMode() );

			if ( isEditMode ) {
				for ( var key in softTemplateAddonsCore.shortcodes ) {
					for ( var keyChild in softTemplateAddonsCore.shortcodes[key] ) {
						softTemplateAddonsElementor.reInitShortcode(
							key,
							keyChild
						);
					}
				}
			}
		},
		reInitShortcode: function ( key, keyChild ) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/' + key + '.default',
				function ( e ) {
					// Check if object doesn't exist and print the module where is the error
					if ( typeof softTemplateAddonsCore.shortcodes[key][keyChild] === 'undefined' ) {
						console.log( keyChild );
					} else if ( typeof softTemplateAddonsCore.shortcodes[key][keyChild].initSlider === 'function' && e.find( '.qodef-qi-swiper-container' ).length ) {
						var $sliders = e.find( '.qodef-qi-swiper-container' );
						if ( $sliders.length ) {
							$sliders.each(
								function () {
									softTemplateAddonsCore.shortcodes[key][keyChild].initSlider( $( this ) );
								}
							);
						}
					} else if ( typeof softTemplateAddonsCore.shortcodes[key][keyChild].initItem === 'function' && e.find( '.qodef-shortcode' ).length ) {
						softTemplateAddonsCore.shortcodes[key][keyChild].initItem( e.find( '.qodef-shortcode' ) );
					} else {
						softTemplateAddonsCore.shortcodes[key][keyChild].init();
					}
				}
			);
		},
	};

})( jQuery );
