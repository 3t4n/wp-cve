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

return array(
	// Attach Lightbox to
	'mediaSelector'        => '.mobx',
	'wp-image'             => 1,
	'wp-gallery'           => 1,
	'woo-gallery'          => 0,
	'visual-composer'      => 0,
	'jetpack-gallery'      => 0,
	'nextgen-gallery'      => 0,
	'envira-gallery'       => 0,
	'justified-image-grid' => 0,
	'essential-grid'       => 0,
	'the-grid'             => 0,

	// Main Layout
	'spacing'              => 0.1,
	'loop'                 => 3,
	'rightToLeft'          => 0,
	'smartResize'          => 1,
	'overflow'             => 0,
	'overlayBackground'    => '#000000',
	'loaderColor'          => '#ffffff',
	'loaderBackground'     => 'rgba(255,255,255,0.2)',

	// Physical Behaviour
	'threshold'            => 5,
	'sliderAttraction'     => 0.055,
	'slideAttraction'      => 0.018,
	'thumbsAttraction'     => 0.016,
	'sliderFriction'       => 0.620,
	'slideFriction'        => 0.180,
	'thumbsFriction'       => 0.220,

	// Browser Behaviour
	'preload'              => 1,
	'timeToIdle'           => 4000,
	'history'              => 0,
	'mouseWheel'           => 1,
	'contextMenu'          => 1,
	'scrollBar'            => 1,
	'mobileDevice'         => 1,

	// Accesssibility
	'buttonsTitle'         => 0,
	'closeLabel'           => __( 'Close lightbox', 'modulobox' ),
	'downloadLabel'        => __( 'Download media', 'modulobox' ),
	'fullScreenLabel'      => __( 'Toggle fullscreen mode', 'modulobox' ),
	'nextLabel'            => __( 'Go to next slide', 'modulobox' ),
	'prevLabel'            => __( 'Go to previous slide', 'modulobox' ),
	'shareLabel'           => __( 'Share this media', 'modulobox' ),
	'playLabel'            => __( 'Toggle slideshow mode', 'modulobox' ),
	'zoomLabel'            => __( 'Toggle zoom', 'modulobox' ),

	// Error Messages
	'loadError'            => __( 'Sorry, an error occured while loading the content...', 'modulobox' ),
	'noContent'            => __( 'Sorry, no content was found!', 'modulobox' ),

	// Click & Tap
	'pinchToClose'         => 1,
	'tapToClose'           => 1,
	'dragToClose'          => 1,
	'doubleTapToZoom'      => 1,
	'pinchToZoom'          => 1,

	// Keyboard
	'prevNextKey'          => 1,
	'escapeToClose'        => 1,

	// Mouse Wheel
	'scrollSensitivity'    => 15,
	'scrollToNav'          => 0,
	'scrollToZoom'         => 0,
	'scrollToClose'        => 0,

	// Main controls
	'controls'             => array( 'close' ),
	'controlsSize'         => 16,
	'controlsColor'        => '#ffffff',
	'topBarBackground'     => 'rgba(0,0,0,0.4)',

	// Prev/Next buttons
	'prevNext'             => 1,
	'prevNextTouch'        => 0,
	'prevNextSize'         => 22,
	'prevNextColor'        => '#ffffff',
	'prevNextBackground'   => 'rgba(0,0,0,0.4)',

	// Caption Behaviour
	'caption'              => 1,
	'autoCaption'          => 0,
	'captionSmallDevice'   => 1,

	// Caption Appearance
	'captionTitleFont'     => '',
	'captionDescFont'      => '',
	'captionMaxWidth'      => 420,
	'captionBackground'    => 'rgba(0,0,0,0.4)',

	// Thumbnails Behaviour
	'thumbnails'           => 1,
	'thumbnailsNav'        => 'basic',

	// Thumbnails Appearance
	'thumbnailSizes'       => array(
		'browser' => array( 1920, 1280, 680, 480 ),
		'width'   => array( 110, 90, 70, 60 ),
		'height'  => array( 80, 65, 50, 44 ),
		'gutter'  => array( 10, 10, 8, 5 ),
	),
	'thumbnailOpacity'       => 0.50,
	'thumbnailActiveOpacity' => 1,
	'thumbnailBorderColor'   => '#ffffff',

	// Social Sharing Behaviour
	'shareButtons'          => array( 'facebook', 'googleplus', 'twitter', 'pinterest', 'linkedin', 'reddit' ),
	'shareText'             => __( 'Share on', 'modulobox' ),
	'sharedUrl'             => 'deeplink',

	// Social Sharing Appearance
	'tooltipWidth'          => 120,
	'tooltipIconSize'       => 16,
	'tooltipIconColor'      => '#444444',
	'tooltipBackground'     => '#ffffff',

	// SlideShow Behaviour
	'slideShowInterval'     => 4000,
	'slideShowAutoPlay'     => 0,
	'slideShowAutoStop'     => 0,

	// SlideShow Count Timer
	'countTimer'            => 1,
	'countTimerBg'          => 'rgba(255,255,255,0.25)',
	'countTimerColor'       => 'rgba(255,255,255,0.75)',

	// SlideShow Counter Message
	'counterMessage'        => '[index] / [total]',
	'counterMessageFont'    => '',

	// Zoom
	'zoomTo'                => 'auto',
	'zoomToValue'           => 2,
	'minZoom'               => 1.2,
	'maxZoom'               => 4,

	// Video
	'videoRatio'            => '16/9',
	'videoMaxWidth'         => 1180,
	'videoAutoPlay'         => 0,
	'videoThumbnail'        => 0,
	'mediaelement'          => 0,

	// Gallery Shortcode
	'gallery'               => '',
	'galleryShortcode'      => 0,
	'galleryThumbnailSize'  => 'thumbnail',
	'galleryRowHeight'      => 220,
	'gallerySpacing'        => 4,
	'galleryCaption'        => 1,
	'galleryCaptionFont'    => '',
	'galleryCaptionOverlay' => 'rgba(0,0,0,0.6)',

	// Customization
	'minifyCSS'             => 1,
	'minifyJS'              => 1,
	'customCSS'             => array(
		'original' => '',
		'minified' => '',
		'error'    => '',
	),
	'customJSAfter'         => array(
		'original' => '',
		'minified' => '',
		'error'    => '',
	),
	'customJSBefore'        => array(
		'original'   => '',
		'minified'   => '',
		'error'      => '',
	),

	// System Status
	'debugMode'            => 0,
);
