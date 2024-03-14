<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'modulobox_register_settings_field' ) ) {

	// Register all settings field
	function modulobox_register_settings_field( $api ) {

		$api->default = include( MOBX_INCLUDES_PATH . 'default.php' );

		$api->add_settings_field( 'selector', array(
			// mediaSelector
			array(
				'premium'      => 1,
				'ID'           => 'mediaSelector',
				'type'         => 'text',
				'title'        => __( 'Custom Query Selector(s)', 'modulobox' ),
				'description'  => __( 'Enable ModuloBox on specific elements from CSS selector(s). Query selector(s) is useful to match media in the document with a custom markup.', 'modulobox' ),
			),
			// wp-image
			array(
				'ID'           => 'wp-image',
				'type'         => 'checkbox',
				'title'        => __( 'WordPress Single Image', 'modulobox' ),
			),
			// wp-gallery
			array(
				'ID'           => 'wp-gallery',
				'type'         => 'checkbox',
				'title'        => __( 'WordPress Galleries', 'modulobox' ),
				'description'  => __( 'If your theme or a 3rd party plugin modifies the native WordPress gallery shortcode, it may not work.', 'modulobox' ),
			),
			// woo-gallery
			array(
				'premium'      => 1,
				'ID'           => 'woo-gallery',
				'type'         => 'checkbox',
				'title'        => __( 'Woocommerce Galleries', 'modulobox' ),
				'description'  => __( 'If your theme or a 3rd party plugin modifies the native Woocommerce product gallery, it may not work.', 'modulobox' ),
			),
			// visual-composer
			array(
				'premium'      => 1,
				'ID'           => 'visual-composer',
				'type'         => 'checkbox',
				'title'        => __( 'Visual Composer', 'modulobox' ),
				'description'  => __( 'Visual Composer galleries, will not work with Deeplink (to open the lightbox on page load) because items are not present on page load.', 'modulobox' ),
			),
			// jetpack-gallery
			array(
				'premium'      => 1,
				'ID'           => 'jetpack-gallery',
				'type'         => 'checkbox',
				'title'        => __( 'JetPack Tiled Gallery', 'modulobox' ),
			),
			// nextgen-gallery
			array(
				'premium'      => 1,
				'ID'           => 'nextgen-gallery',
				'type'         => 'checkbox',
				'title'        => __( 'NextGen Gallery', 'modulobox' ),
			),
			// envira-gallery
			array(
				'premium'      => 1,
				'ID'           => 'envira-gallery',
				'type'         => 'checkbox',
				'title'        => __( 'Envira Gallery', 'modulobox' ),
			),
			// justified-image-grid
			array(
				'premium'      => 1,
				'ID'           => 'justified-image-grid',
				'type'         => 'checkbox',
				'title'        => __( 'Justified Image Grid', 'modulobox' ),
			),
			// essential-grid
			array(
				'premium'      => 1,
				'ID'           => 'essential-grid',
				'type'         => 'checkbox',
				'title'        => __( 'Essential Grid', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'physical-behaviour', array(
			// Threshold
			array(
				'ID'           => 'threshold',
				'type'         => 'slider',
				'title'        => __( 'Threshold', 'modulobox' ),
				'description'  => __( 'The threshold corresponds to the number of pixels a pointer has to move before dragging begins. It allows to better detect the gesture type.', 'modulobox' ),
				'min'          => 0,
				'max'          => 50,
				'step'         => 1,
				'unit'         => 'px',
			),
			// Attraction
			array(
				'ID'           => 'sliderAttraction',
				'type'         => 'slider',
				'title'        => __( 'Slider Attraction', 'modulobox' ),
				'description'  => __( 'Attracts the position of the slider to the selected cell. Higher value makes the slider move faster. Lower value makes it move slower.', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
			// slideAttraction
			array(
				'ID'           => 'slideAttraction',
				'type'         => 'slider',
				'title'        => __( 'Slide Attraction', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
			// thumbsAttraction
			array(
				'ID'           => 'thumbsAttraction',
				'type'         => 'slider',
				'title'        => __( 'Thumbnails Attraction', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
			// sliderFriction
			array(
				'ID'           => 'sliderFriction',
				'type'         => 'slider',
				'title'        => __( 'Slider Friction', 'modulobox' ),
				'description'  => __( 'Friction slows the movement of slider. Higher value makes the slider feel stickier &amp; less bouncy. Lower value makes the slider feel looser &amp; more wobbly.', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
			// slideFriction
			array(
				'ID'           => 'slideFriction',
				'type'         => 'slider',
				'title'        => __( 'Slide Friction', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
			// thumbsFriction
			array(
				'ID'           => 'thumbsFriction',
				'type'         => 'slider',
				'title'        => __( 'Thumbnails Friction', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.001,
			),
		));

		$api->add_settings_field( 'layout', array(
			// spacing
			array(
				'ID'           => 'spacing',
				'type'         => 'slider',
				'title'        => __( 'Slide Spacing', 'modulobox' ),
				'description'  => __( 'Space, in percentage factor, between each slide. For example, \'0.1\' will render as a 10% of sliding viewport width.', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.01,
			),
			// loop
			array(
				'ID'           => 'loop',
				'type'         => 'number',
				'title'        => __( 'Infinite Loop', 'modulobox' ),
				'description'  => __( 'Defined from how much items in a gallery infinite loop is enabled. \'0\' &#61; no loop; \'1\' &#61; always loop.', 'modulobox' ),
				'min'          => 0,
				'max'          => 999999,
			),
			// rightToLeft
			array(
				'ID'           => 'rightToLeft',
				'type'         => 'checkbox',
				'title'        => __( 'Right To Left (RTL)', 'modulobox' ),
			),
			// smartResize
			array(
				'ID'           => 'smartResize',
				'type'         => 'checkbox',
				'title'        => __( 'Smart Resize', 'modulobox' ),
				'description'  => __( 'Allow images to overflow on top bar and/or caption only on small devices (in width/height) only if image can entirely fill the full screen height.', 'modulobox' ),
			),
			// overflow
			array(
				'ID'           => 'overflow',
				'type'         => 'checkbox',
				'title'        => __( 'Media Overflow', 'modulobox' ),
				'description'  => __( 'Allow images to overflow on top bar and/or caption if image can entirely fill the full screen height. It will be applied whatever the device size contrary to Smart Resize option.', 'modulobox' ),
			),
			// overlayBackground
			array(
				'premium'      => 1,
				'ID'           => 'overlayBackground',
				'type'         => 'color',
				'title'        => __( 'Overlay Color', 'modulobox' ),
				'description'  => __( 'Overlay background color displayed under the whole lightbox.', 'modulobox' ),
				'alpha'        => true,
			),
			// preloaderColor
			array(
				'premium'      => 1,
				'ID'           => 'loaderColor',
				'type'         => 'color',
				'title'        => __( 'Loader Color', 'modulobox' ),
				'description'  => __( 'Main color of the animated loader.', 'modulobox' ),
				'alpha'        => true,
			),
			// preloaderBackground
			array(
				'premium'      => 1,
				'ID'           => 'loaderBackground',
				'type'         => 'color',
				'title'        => __( 'Loader Background', 'modulobox' ),
				'description'  => __( 'Background color of the animated preloader.', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'browser', array(
			// Preload
			array(
				'ID'           => 'preload',
				'type'         => 'slider',
				'title'        => __( 'Preload (nb of items)', 'modulobox' ),
				'description'  => __( 'Number of media to preload on opening. If set to 1, it will load the currently media opened and once loaded it will preload the 2 closest media.', 'modulobox' ),
				'min'          => 1,
				'max'          => 5,
				'step'         => 2,
			),
			// timeToIdle
			array(
				'ID'           => 'timeToIdle',
				'type'         => 'number',
				'title'        => __( 'Time To Idle (ms)', 'modulobox' ),
				'description'  => __( 'Hide controls (top navigation bar) when an idle state exceed X milliseconds. \'0\' will always keep controls visible.', 'modulobox' ),
				'min'          => 0,
				'max'          => 99999,
			),
			// history
			array(
				'premium'      => 1,
				'ID'           => 'history',
				'type'         => 'checkbox',
				'title'        => __( 'History', 'modulobox' ),
				'description'  => __( 'Enable/disable history in browser (deeplink) when opening/navigating/closing a gallery. This options is not required to open a gallery from a deeplink or to share a media.', 'modulobox' ),
			),
			// mouseWheel
			array(
				'premium'      => 1,
				'ID'           => 'mouseWheel',
				'type'         => 'checkbox',
				'title'        => __( 'Mouse Wheel', 'modulobox' ),
				'description'  => __( 'Enable/disable mouse wheel and up/down/space keys to scroll page.', 'modulobox' ),
			),
			// contextMenu
			array(
				'premium'      => 1,
				'ID'           => 'contextMenu',
				'type'         => 'checkbox',
				'title'        => __( 'Context Menu', 'modulobox' ),
				'description'  => __( 'Enable/disable context menu on right click (on image, video poster, thumbnails). Useful if you want to prevent an user to download an image.', 'modulobox' ),
			),
			// scrollBar
			array(
				'premium'      => 1,
				'ID'           => 'scrollBar',
				'type'         => 'checkbox',
				'title'        => __( 'ScrollBar', 'modulobox' ),
				'description'  => __( 'Show/Hide browser scrollbar when opening and closing lightbox.', 'modulobox' ),
			),
			// mobileDevice
			array(
				'premium'      => 1,
				'ID'           => 'mobileDevice',
				'type'         => 'checkbox',
				'title'        => __( 'Mobile Devices', 'modulobox' ),
				'description'  => __( 'Enable/disable the lightbox on mobile devices. No lightbox will be open on mobile devices if deactivated.', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'accessibility', array(
			// showTitleButton
			array(
				'ID'           => 'buttonsTitle',
				'type'         => 'checkbox',
				'title'        => __( 'Show label on mouse over', 'modulobox' ),
				'description'  => __( 'Show label when the mouse moves over buttons. Adds a title attribute to buttons.', 'modulobox' ),
			),
			// closeLabel
			array(
				'ID'           => 'closeLabel',
				'type'         => 'text',
				'title'        => __( 'Close Button Label', 'modulobox' ),
				'description'  => __( 'Message used to provide the label to any assistive technologies.', 'modulobox' ),
			),
			// downloadLabel
			array(
				'ID'           => 'downloadLabel',
				'type'         => 'text',
				'title'        => __( 'Download Button Label', 'modulobox' ),
			),
			// fullScreenLabel
			array(
				'ID'           => 'fullScreenLabel',
				'type'         => 'text',
				'title'        => __( 'Full Screen Button Label', 'modulobox' ),
			),
			// nextLabel
			array(
				'ID'           => 'nextLabel',
				'type'         => 'text',
				'title'        => __( 'Next Button Label', 'modulobox' ),
			),
			// prevLabel
			array(
				'ID'           => 'prevLabel',
				'type'         => 'text',
				'title'        => __( 'Prev Button Label', 'modulobox' ),
			),
			// shareLabel
			array(
				'ID'           => 'shareLabel',
				'type'         => 'text',
				'title'        => __( 'Share Button Label', 'modulobox' ),
			),
			// playLabel
			array(
				'ID'           => 'playLabel',
				'type'         => 'text',
				'title'        => __( 'SlideShow Button Label', 'modulobox' ),
			),
			// zoomLabel
			array(
				'ID'           => 'zoomLabel',
				'type'         => 'text',
				'title'        => __( 'Zoom Button Label', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'error-messages', array(
			// loadError
			array(
				'ID'           => 'loadError',
				'type'         => 'text',
				'title'        => __( 'Load Error Message', 'modulobox' ),
				'description'  => __( 'Message displayed when a media fails to load.', 'modulobox' ),
				'width'        => 360,
			),
			// noContent
			array(
				'ID'           => 'noContent',
				'type'         => 'text',
				'title'        => __( 'No Content Message', 'modulobox' ),
				'description'  => __( 'Message displayed when no media/content was found.', 'modulobox' ),
				'width'        => 360,
			),
		));

		$api->add_settings_field( 'gestures', array(
			// pinchToClose
			array(
				'premium'      => 1,
				'ID'           => 'pinchToClose',
				'type'         => 'checkbox',
				'title'        => __( 'Pinch to Close', 'modulobox' ),
			),
			// tapToClose
			array(
				'ID'           => 'tapToClose',
				'type'         => 'checkbox',
				'title'        => __( 'Click/Tap to Close', 'modulobox' ),
				'description'  => __( 'Tap or click anywhere outside the image/media to close the lightbox.', 'modulobox' ),
			),
			// dragToClose
			array(
				'ID'           => 'dragToClose',
				'type'         => 'checkbox',
				'title'        => __( 'Drag to Close', 'modulobox' ),
			),
			// doubleTapToZoom
			array(
				'premium'      => 1,
				'ID'           => 'doubleTapToZoom',
				'type'         => 'checkbox',
				'title'        => __( 'Double Tap/Click to Zoom', 'modulobox' ),
			),
			// pinchToZoom
			array(
				'premium'      => 1,
				'ID'           => 'pinchToZoom',
				'type'         => 'checkbox',
				'title'        => __( 'Pinch to Zoom', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'keyboard', array(
			// prevNextKey
			array(
				'ID'           => 'prevNextKey',
				'type'         => 'checkbox',
				'title'        => __( 'Prev/Next to Slide', 'modulobox' ),
			),
			// escapeToClose
			array(
				'ID'           => 'escapeToClose',
				'type'         => 'checkbox',
				'title'        => __( 'Escape to Close', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'mousewheel', array(
			// scrollSensitivity
			array(
				'premium'      => 1,
				'ID'           => 'scrollSensitivity',
				'type'         => 'slider',
				'title'        => __( 'Scroll Sensitivity', 'modulobox' ),
				'description'  => __( 'Threshold in px from when a method is called (to zoom, to navigate for example). Useful to fine tune TouchPad sensitivity.', 'modulobox' ),
				'min'          => 0,
				'max'          => 100,
				'step'         => 1,
				'unit'         => 'px',
			),
			// scrollToNav
			array(
				'premium'      => 1,
				'ID'           => 'scrollToNav',
				'type'         => 'checkbox',
				'title'        => __( 'Scroll to Slide', 'modulobox' ),
			),
			// scrollToZoom
			array(
				'premium'      => 1,
				'ID'           => 'scrollToZoom',
				'type'         => 'checkbox',
				'title'        => __( 'Scroll to Zoom', 'modulobox' ),
			),
			// scrollToClose
			array(
				'premium'      => 1,
				'ID'           => 'scrollToClose',
				'type'         => 'checkbox',
				'title'        => __( 'Scroll to Close', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'controls', array(
			// controls
			array(
				'premium'      => 1,
				'ID'           => 'controls',
				'type'         => 'sorter',
				'title'        => __( 'Controls', 'modulobox' ),
				'description'  => __( 'Drag &amp; Drop and sort control buttons in the highlighted area to display them in the top bar area.', 'modulobox' ),
				'enable_msg'   => __( 'Drag &amp; drop control(s) here', 'modulobox' ),
				'disable_msg'  => __( 'All controls have been added!', 'modulobox' ),
				'options'      => array(
					'zoom'       => array(
						'title' => __( 'Zoom', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-zoom"></use></svg>',
					),
					'play'       => array(
						'title' => __( 'Play', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-play"></use></svg>',
					),
					'fullScreen' => array(
						'title' => __( 'Full Screen', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-fullscreen"></use></svg>',
					),
					'download'   => array(
						'title' => __( 'Download', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-download"></use></svg>',
					),
					'share'      => array(
						'title' => __( 'Share', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-share"></use></svg>',
					),
					'close'      => array(
						'title' => __( 'Close', 'modulobox' ),
						'html'  => '<svg><use xlink:href="#mobx-svg-close"></use></svg>',
					),
				),
			),
			// controlsSize
			array(
				'premium'      => 1,
				'ID'           => 'controlsSize',
				'type'         => 'slider',
				'title'        => __( 'Icons Size', 'modulobox' ),
				'min'          => 8,
				'max'          => 32,
				'step'         => 1,
				'unit'         => 'px',
			),
			// controlsColor
			array(
				'premium'      => 1,
				'ID'           => 'controlsColor',
				'type'         => 'color',
				'title'        => __( 'Icons Color', 'modulobox' ),
			),
			// topBarBackground
			array(
				'premium'      => 1,
				'ID'           => 'topBarBackground',
				'type'         => 'color',
				'title'        => __( 'Background Color', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'prev-next-buttons', array(
			// prevNext
			array(
				'ID'           => 'prevNext',
				'type'         => 'checkbox',
				'title'        => __( 'Show Prev/Next', 'modulobox' ),
			),
			// prevNextTouch
			array(
				'ID'           => 'prevNextTouch',
				'type'         => 'checkbox',
				'title'        => __( 'Show on Mobile device', 'modulobox' ),
				'description'  => __( 'Only work if Prev/Next option is enabled.', 'modulobox' ),
			),
			// prevNextSize
			array(
				'premium'      => 1,
				'ID'           => 'prevNextSize',
				'type'         => 'slider',
				'title'        => __( 'Icons Size', 'modulobox' ),
				'min'          => 8,
				'max'          => 50,
				'step'         => 1,
				'unit'         => 'px',
			),
			// prevNextColor
			array(
				'premium'      => 1,
				'ID'           => 'prevNextColor',
				'type'         => 'color',
				'title'        => __( 'Icons Color', 'modulobox' ),
			),
			// prevNextBackground
			array(
				'premium'      => 1,
				'ID'           => 'prevNextBackground',
				'type'         => 'color',
				'title'        => __( 'Background Color', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'caption', array(
			// caption
			array(
				'ID'           => 'caption',
				'type'         => 'checkbox',
				'title'        => __( 'Show Caption', 'modulobox' ),
			),
			// autoCaption
			array(
				'ID'           => 'autoCaption',
				'type'         => 'checkbox',
				'title'        => __( 'Auto Caption', 'modulobox' ),
				'description'  => __( 'Auto generate captions from title and/or alt attributes if ModuloBox attributes (\'data-title\' &amp; \'data-desc\') are missing in the markup.', 'modulobox' ),
			),
			// captionSmallDevice
			array(
				'ID'           => 'captionSmallDevice',
				'type'         => 'checkbox',
				'title'        => __( 'Show on small device', 'modulobox' ),
				'description'  => __( 'Show/hide caption on small browser sizes (in width/height). Works on desktop and mobile devices.', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'caption-styles', array(
			// captionTitleFont
			array(
				'premium'      => 1,
				'ID'           => 'captionTitleFont',
				'type'         => 'typography',
				'title'        => __( 'Caption Title Font', 'modulobox' ),
				'default'      => array(
					'-1' => array(
						'device'          => __( 'Desktop', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
						'color'           => '#eeeeee',
					),
					'1024' => array(
						'device'          => __( 'Tablet', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
					),
					'480' => array(
						'device'          => __( 'Mobile', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
					),
				),
			),
			// captionDescFont
			array(
				'premium'      => 1,
				'ID'           => 'captionDescFont',
				'type'         => 'typography',
				'title'        => __( 'Caption Sub-Title Font', 'modulobox' ),
				'default'      => array(
					'-1' => array(
						'device'          => __( 'Desktop', 'modulobox' ),
						'line-height'     => '16',
						'font-weight'     => '400',
						'font-size'       => '12',
						'text-align'      => 'center',
						'color'           => '#bbbbbb',
					),
					'1024' => array(
						'device'          => __( 'Tablet', 'modulobox' ),
						'line-height'     => '16',
						'font-weight'     => '400',
						'font-size'       => '12',
						'text-align'      => 'center',
					),
					'480' => array(
						'device'          => __( 'Mobile', 'modulobox' ),
						'line-height'     => '16',
						'font-weight'     => '400',
						'font-size'       => '12',
						'text-align'      => 'center',
					),
				),
			),
			array(
				'premium'      => 1,
				'ID'           => 'captionMaxWidth',
				'type'         => 'slider',
				'title'        => __( 'Caption Max Width', 'modulobox' ),
				'min'          => 120,
				'max'          => 2560,
				'step'         => 1,
				'unit'         => 'px',
			),
			// captionBackground
			array(
				'premium'      => 1,
				'ID'           => 'captionBackground',
				'type'         => 'color',
				'title'        => __( 'Background Color', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'thumbnails', array(
			// thumbnails
			array(
				'premium'      => 1,
				'ID'           => 'thumbnails',
				'type'         => 'checkbox',
				'title'        => __( 'Thumbnails', 'modulobox' ),
			),
			// thumbnailsNav
			array(
				'premium'      => 1,
				'ID'           => 'thumbnailsNav',
				'type'         => 'radio',
				'title'        => __( 'Navigation Type', 'modulobox' ),
				'description'  => __( 'Navigation type used for the thumbnails slider.', 'modulobox' ),
				'options'      => array(
					'basic'    => __( 'Basic', 'modulobox' ),
					'centered' => __( 'Centered', 'modulobox' ),
				),
			),
		));

		$api->add_settings_field( 'thumbnails-styles', array(
			// thumbnailSizes
			array(
				'premium'      => 1,
				'ID'           => 'thumbnailSizes',
				'type'         => 'sizes',
				'title'        => __( 'Thumbnail Sizes', 'modulobox' ),
				'description'  => __( 'You can set several thumbnail sizes for different browser widths. If the width and/or the height is set to 0, no thumbnail will be displayed for the corresponding browser width.', 'modulobox' ),
			),
			// thumbnailOpacity
			array(
				'premium'      => 1,
				'ID'           => 'thumbnailOpacity',
				'type'         => 'slider',
				'title'        => __( 'Thumbnail Opacity', 'modulobox' ),
				'description'  => __( 'Opacity used for inactive thumbnails.', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.01,
			),
			// thumbnailActiveOpacity
			array(
				'premium'      => 1,
				'ID'           => 'thumbnailActiveOpacity',
				'type'         => 'slider',
				'title'        => __( 'Active Thumbnail Opacity', 'modulobox' ),
				'description'  => __( 'Opacity used for active thumbnail and on mouse over.', 'modulobox' ),
				'min'          => 0,
				'max'          => 1,
				'step'         => 0.01,
			),
			// thumbnailBorderColor
			array(
				'premium'      => 1,
				'ID'           => 'thumbnailBorderColor',
				'type'         => 'color',
				'title'        => __( 'Active Thumbnail Border', 'modulobox' ),
				'description'  => __( 'Border color used for active thumbnail. You can set an opacity of \'0\' if you don\'t want any border.', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'social-sharing', array(
			// shareButtons
			array(
				'premium'      => 1,
				'ID'           => 'shareButtons',
				'type'         => 'sorter',
				'title'        => __( 'Share Buttons', 'modulobox' ),
				'description'  => __( 'Drag &amp; Drop and sort social buttons in the highlighted area.', 'modulobox' ),
				'enable_msg'   => __( 'Drag &amp; drop social button(s) here', 'modulobox' ),
				'disable_msg'  => __( 'All social buttons have been added!', 'modulobox' ),
				'options'      => array(
					'facebook'  => array(
						'title' => 'Facebook',
						'html'  => '<svg><use xlink:href="#mobx-svg-facebook"></use></svg>',
					),
					'googleplus'       => array(
						'title' => 'Google+',
						'html'  => '<svg><use xlink:href="#mobx-svg-googleplus"></use></svg>',
					),
					'twitter' => array(
						'title' => 'Twitter',
						'html'  => '<svg><use xlink:href="#mobx-svg-twitter"></use></svg>',
					),
					'pinterest' => array(
						'title' => 'Pinterest',
						'html'  => '<svg><use xlink:href="#mobx-svg-pinterest"></use></svg>',
					),
					'linkedin'  => array(
						'title' => 'Linkedin',
						'html'  => '<svg><use xlink:href="#mobx-svg-linkedin"></use></svg>',
					),
					'reddit' => array(
						'title' => 'Reddit',
						'html'  => '<svg><use xlink:href="#mobx-svg-reddit"></use></svg>',
					),
					'stumbleupon' => array(
						'title' => 'Stumbleupon',
						'html'  => '<svg><use xlink:href="#mobx-svg-stumbleupon"></use></svg>',
					),
					'tumblr'    => array(
						'title' => 'Tumblr',
						'html'  => '<svg><use xlink:href="#mobx-svg-tumblr"></use></svg>',
					),
					'blogger'      => array(
						'title' => 'Blogger',
						'html'  => '<svg><use xlink:href="#mobx-svg-blogger"></use></svg>',
					),
					'buffer'    => array(
						'title' => 'Buffer',
						'html'  => '<svg><use xlink:href="#mobx-svg-buffer"></use></svg>',
					),
					'digg'      => array(
						'title' => 'Digg',
						'html'  => '<svg><use xlink:href="#mobx-svg-digg"></use></svg>',
					),
					'evernote'  => array(
						'title' => 'Evernote',
						'html'  => '<svg><use xlink:href="#mobx-svg-evernote"></use></svg>',
					),
				),
			),
			// shareText
			array(
				'premium'      => 1,
				'ID'           => 'shareText',
				'type'         => 'text',
				'title'        => __( 'Tooltip Share Text', 'modulobox' ),
				'description'  => __( 'Text displayed above social sharing buttons inside tooltip. Empty value will completely remove text from tooltip.', 'modulobox' ),
			),
			// sharedUrl
			array(
				'premium'      => 1,
				'ID'           => 'sharedUrl',
				'type'         => 'radio',
				'title'        => __( 'Shared URL type', 'modulobox' ),
				'options'      => array(
					'deeplink' => __( 'Deep Link', 'modulobox' ),
					'page'     => __( 'Page URL', 'modulobox' ),
					'media'    => __( 'Media URL', 'modulobox' ),
				),
			),
		));

		$api->add_settings_field( 'social-sharing-tooltip', array(
			// tooltipIconSize
			array(
				'premium'      => 1,
				'ID'           => 'tooltipWidth',
				'type'         => 'slider',
				'title'        => __( 'Tooltip Width', 'modulobox' ),
				'min'          => 40,
				'max'          => 320,
				'step'         => 1,
				'unit'         => 'px',
			),
			// tooltipIconSize
			array(
				'premium'      => 1,
				'ID'           => 'tooltipIconSize',
				'type'         => 'slider',
				'title'        => __( 'Icons Size', 'modulobox' ),
				'min'          => 8,
				'max'          => 32,
				'step'         => 1,
				'unit'         => 'px',
			),
			// tooltipIconColor
			array(
				'premium'      => 1,
				'ID'           => 'tooltipIconColor',
				'type'         => 'color',
				'title'        => __( 'Icons Color', 'modulobox' ),
			),
			// tooltipBackground
			array(
				'premium'      => 1,
				'ID'           => 'tooltipBackground',
				'type'         => 'color',
				'title'        => __( 'Background Color', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'slideshow', array(
			// slideShowInterval
			array(
				'premium'      => 1,
				'ID'           => 'slideShowInterval',
				'type'         => 'slider',
				'title'        => __( 'SlideShow Interval', 'modulobox' ),
				'description'  => __( 'Time interval, in milliseconds, between slide changes in slideshow mode. Requires play button in the controls option.', 'modulobox' ),
				'min'          => 500,
				'max'          => 20000,
				'step'         => 1,
				'unit'         => 'ms',
			),
			// slideShowAutoPlay
			array(
				'premium'      => 1,
				'ID'           => 'slideShowAutoPlay',
				'type'         => 'checkbox',
				'title'        => __( 'SlideShow Auto Play', 'modulobox' ),
				'description'  => __( 'Automatically start slideshow mode on opening. If Video Auto Play is enabled, the slideshow will not start if the slide contains a video.', 'modulobox' ),
			),
			// slideShowAutoStop
			array(
				'premium'      => 1,
				'ID'           => 'slideShowAutoStop',
				'type'         => 'checkbox',
				'title'        => __( 'SlideShow Auto Stop', 'modulobox' ),
				'description'  => __( 'Stop slideshow mode when the last item is reached (only if slider loop).', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'count-timer', array(
			// countTimer
			array(
				'premium'      => 1,
				'ID'           => 'countTimer',
				'type'         => 'checkbox',
				'title'        => __( 'Show CountDown Timer', 'modulobox' ),
				'description'  => __( 'Show a circular countdown timer next to the counter message when slideshow is playing.', 'modulobox' ),
			),
			// countTimerColor
			array(
				'premium'      => 1,
				'ID'           => 'countTimerColor',
				'type'         => 'color',
				'title'        => __( 'Count Timer Color', 'modulobox' ),
				'alpha'        => true,
			),
			// countTimerBg
			array(
				'premium'      => 1,
				'ID'           => 'countTimerBg',
				'type'         => 'color',
				'title'        => __( 'Count Timer Background', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'counter-message', array(
			// counterMessage
			array(
				'ID'           => 'counterMessage',
				'type'         => 'text',
				'title'        => __( 'Counter Message', 'modulobox' ),
				'description'  => __( 'Message used in the item counter. If empty, no counter will be displayed. ([index] : Current item; [total] : Total number of items in gallery)', 'modulobox' ),
			),
			// counterMessageFont
			array(
				'premium'      => 1,
				'ID'           => 'counterMessageFont',
				'type'         => 'typography',
				'title'        => __( 'Counter Message Font', 'modulobox' ),
				'default'      => array(
					'-1' => array(
						'device'          => __( 'Desktop', 'modulobox' ),
						'line-height'     => '44',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
						'color'           => '#ffffff',
					),
					'1024' => array(
						'device'          => __( 'Tablet', 'modulobox' ),
						'line-height'     => '44',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
					),
					'480' => array(
						'device'          => __( 'Mobile', 'modulobox' ),
						'line-height'     => '44',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'center',
					),
				),
			),
		));

		$api->add_settings_field( 'zoom', array(
			// zoomTo
			array(
				'premium'      => 1,
				'ID'           => 'zoomTo',
				'type'         => 'radio',
				'title'        => __( 'Zoom To', 'modulobox' ),
				'description'  => __( '\'Auto\' value will scale up to the natural image size on button click or on double click/tap.', 'modulobox' ),
				'options'      => array(
					'auto'   => __( 'Auto', 'modulobox' ),
					'custom' => __( 'Custom', 'modulobox' ),
				),
			),
			// zoomToValue
			array(
				'premium'      => 1,
				'ID'           => 'zoomToValue',
				'type'         => 'slider',
				'title'        => __( 'Zoom To Value', 'modulobox' ),
				'description'  => __( 'Zoom factor applied on button click or on double click/tap.', 'modulobox' ),
				'min'          => 1,
				'max'          => 10,
				'step'         => 0.01,
				'unit'         => 'X',
			),
			// minZoom
			array(
				'premium'      => 1,
				'ID'           => 'minZoom',
				'type'         => 'slider',
				'title'        => __( 'Min Zoom', 'modulobox' ),
				'description'  => __( 'Minimum zoom factor required to zoom an image. The user will be able to zoom only if the image size displayed on screen can be scaled up by the Min Zoom factor in order to reach, at minimum, its original size.', 'modulobox' ),
				'min'          => 1,
				'max'          => 10,
				'step'         => 0.01,
				'unit'         => 'X',
			),
			// maxZoom
			array(
				'premium'      => 1,
				'ID'           => 'maxZoom',
				'type'         => 'slider',
				'title'        => __( 'Max Zoom', 'modulobox' ),
				'description'  => __( 'Maximum zoom factor allowed when pinching/scrolling. This value should be superior or equal to Min Zoom value.', 'modulobox' ),
				'min'          => 1,
				'max'          => 10,
				'step'         => 0.01,
				'unit'         => 'X',
			),
		));

		$api->add_settings_field( 'videos', array(
			// videoRatio
			array(
				'premium'      => 1,
				'ID'           => 'videoRatio',
				'type'         => 'radio',
				'title'        => __( 'Video Aspect Ratio', 'modulobox' ),
				'options'      => array(
					'21/9'  => '21:9',
					'16/10' => '16:10',
					'16/9'  => '16:9',
					'4/4'   => '4:4',
					'4/3'   => '4:3',
					'3/2'   => '3:2',
				),
			),
			// videoMaxWidth
			array(
				'premium'      => 1,
				'ID'           => 'videoMaxWidth',
				'type'         => 'slider',
				'title'        => __( 'Video Max Width', 'modulobox' ),
				'description'  => __( 'Videos are responsive and will be resized according to this value and the aspect ratio set for Video Aspect Ratio.', 'modulobox' ),
				'min'          => 320,
				'max'          => 3840,
				'step'         => 1,
				'unit'         => 'px',
			),
			// videoAutoPlay
			array(
				'premium'      => 1,
				'ID'           => 'videoAutoPlay',
				'type'         => 'checkbox',
				'title'        => __( 'Video Auto Play', 'modulobox' ),
				'description'  => __( 'Autoplay video on opening.', 'modulobox' ),
			),
			// mediaelement
			array(
				'premium'      => 1,
				'ID'           => 'mediaelement',
				'type'         => 'checkbox',
				'title'        => __( 'Mediaelement.js Player', 'modulobox' ),
				'description'  => __( 'Play HTML5 videos with mediaelement.js jQuery player.', 'modulobox' ),
			),
			// videoThumbnail
			array(
				'premium'      => 1,
				'ID'           => 'videoThumbnail',
				'type'         => 'checkbox',
				'title'        => __( 'Video Thumbnail', 'modulobox' ),
				'description'  => __( 'Automatically retrieve poster/thumbnails of embed videos if missing.', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'gallery', array(
			// galleryShortcode
			array(
				'ID'           => 'galleryShortcode',
				'type'         => 'checkbox',
				'title'        => __( 'Replace Gallery', 'modulobox' ),
				'description'  => __( 'When enabled, ModuloBox will replace all WordPress Gallery shortcodes/blocks by its own gallery.', 'modulobox' )
			),
			// galleryThumbnailSize
			array(
				'premium'      => 1,
				'ID'           => 'galleryThumbnailSize',
				'type'         => 'select',
				'title'        => __( 'Thumbnail Size', 'modulobox' ),
				'description'  => __( 'Image size displayed in the thumbnail slider of the lightbox. Smaller image size allows to preserve performances.', 'modulobox' ),
				'options'      => ModuloBox_Base::get_images_sizes(),
			),
		));

		$api->add_settings_field( 'gallery-styles', array(
			// galleryRowHeight
			array(
				'ID'           => 'galleryRowHeight',
				'type'         => 'slider',
				'title'        => __( 'Row Height', 'modulobox' ),
				'description'  => __( 'Set default row height for your galleries. This value can be overridden in each single gallery shortcode settings.', 'modulobox' ),
				'min'          => 10,
				'max'          => 1000,
				'step'         => 1,
				'unit'         => 'px',
			),
			// gallerySpacing
			array(
				'ID'           => 'gallerySpacing',
				'type'         => 'slider',
				'title'        => __( 'Item spacing', 'modulobox' ),
				'description'  => __( 'Set default item spacing for your galleries. This value can be overridden in each single gallery shortcode settings.', 'modulobox' ),
				'min'          => 0,
				'max'          => 100,
				'step'         => 1,
				'unit'         => 'px',
			),
			// galleryCaption
			array(
				'ID'           => 'galleryCaption',
				'type'         => 'checkbox',
				'title'        => __( 'Show Caption', 'modulobox' ),
			),
			// galleryCaptionFont
			array(
				'premium'      => 1,
				'ID'           => 'galleryCaptionFont',
				'type'         => 'typography',
				'title'        => __( 'Caption Font', 'modulobox' ),
				'default'      => array(
					'-1' => array(
						'device'          => __( 'Desktop', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'left',
						'color'           => '#ffffff',
					),
					'1024' => array(
						'device'          => __( 'Tablet', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'left',
					),
					'480' => array(
						'device'          => __( 'Mobile', 'modulobox' ),
						'line-height'     => '18',
						'font-weight'     => '400',
						'font-size'       => '13',
						'text-align'      => 'left',
					),
				),
			),
			// galleryCaptionOverlay
			array(
				'premium'      => 1,
				'ID'           => 'galleryCaptionOverlay',
				'type'         => 'color',
				'title'        => __( 'Caption Overlay', 'modulobox' ),
				'alpha'        => true,
			),
		));

		$api->add_settings_field( 'minify-scripts', array(
			// minifyCSS
			array(
				'premium'      => 1,
				'ID'           => 'minifyCSS',
				'type'         => 'checkbox',
				'title'        => __( 'Minify Custom CSS', 'modulobox' ),
			),
			// minifyJS
			array(
				'premium'      => 1,
				'ID'           => 'minifyJS',
				'type'         => 'checkbox',
				'title'        => __( 'Minify Custom JS', 'modulobox' ),
			),
		));

		$api->add_settings_field( 'custom-css', array(
			// customCSS
			array(
				'premium'      => 1,
				'ID'           => 'customCSS',
				'type'         => 'code',
				'mode'         => 'css',
			),
		));

		$api->add_settings_field( 'custom-js', array(
			// customJS
			array(
				'premium'      => 1,
				'ID'           => 'customJSAfter',
				'type'         => 'code',
				'mode'         => 'javascript',
			),
		));

		$api->add_settings_field( 'custom-js-advanced', array(
			// customJSAdvanced
			array(
				'premium'      => 1,
				'ID'           => 'customJSBefore',
				'type'         => 'code',
				'mode'         => 'javascript',
			),
		));

		$api->add_settings_field( 'debug', array(
			// debugMode
			array(
				'ID'           => 'debugMode',
				'type'         => 'checkbox',
				'title'        => __( 'Debug Mode', 'modulobox' ),
				'description'  => __( 'When debug mode is enabled, scripts used on front-end will be un-minified. It allows to better debug JavaScript issues.', 'modulobox' ),
			),
		));

	};

	add_action( MOBX_NAME . '_register_settings_field', MOBX_NAME . '_register_settings_field' );

}
