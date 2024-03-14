<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

//Globals
global $settings;
$settings = lbs_settings_obj();

global $lbs_options;
$lbs_options = lbs_get_opts();

global $catalog_settings;
$catalog_settings = lbs_get_catalog_settings();

/**
 * Gets plugin options from database
 *
 * Returns a multidimensional array containing plugin settings.
 * Queries the WP database for saved catalog and slick settings.
 * If no catalog or slick settings are stored in the database, default settings are used.
 *
 * @since 1.0
 *
 * @global object $wpdb Wordpress database object.
 *
 * @return array $options Plugin catalog and slick settings saved in database.
*/
function lbs_get_opts() {
	// Query WP options table for plugin settings
	global $wpdb;
	$table  = $wpdb->prefix . 'options';

	$cat_like   = $wpdb->esc_like( 'lbs_cat_' ) . '%';
	$cat_query  = $wpdb->prepare( 'SELECT * FROM %1s WHERE option_name like %s', array( $table, $cat_like ) );
	$cat_result = $wpdb->get_results( $cat_query, ARRAY_A );

	$slick_like   = $wpdb->esc_like( 'lbs_slick_' ) . '%';
	$slick_query  = $wpdb->prepare( 'SELECT * FROM %1s WHERE option_name like %s', array( $table, $slick_like ) );
	$slick_result = $wpdb->get_results( $slick_query, ARRAY_A );

	$css_like   = $wpdb->esc_like( 'lbs_css_' ) . '%';
	$css_query  = $wpdb->prepare( 'SELECT * FROM %1s WHERE option_name like %s', array( $table, $css_like ) );
	$css_result = $wpdb->get_results( $css_query, ARRAY_A );

	// Convert query results to associative arrays
	if ( $cat_result ) {
		foreach ( $cat_result as $c ) {
			$cat_options[ $c['option_name'] ] = $c['option_value'];
		}
	} else {
		$cat_options = lbs_set_defaults( 'catalog' );
	}

	if ( $slick_result ) {
		foreach ( $slick_result as $s ) {
			$slick_options[ $s['option_name'] ] = $s['option_value'];
		}
	} else {
		$slick_options = lbs_set_defaults( 'slick' );
	}

	if ( $css_result ) {
		foreach ( $css_result as $css ) {
			$css_options[ $css['option_name'] ] = $css['option_value'];
		}
	} else {
		$css_options = lbs_set_defaults( 'css' );
	}

	$options = array_merge( $cat_options, $slick_options, $css_options );
	return $options;
}

/**
 * Gets default options if none are stored
 *
 * Gets default settings from the settings array.
 * 
 * @since 2.0
 *
 * @global array $settings Settings array which contains plugin defaults.
 * @param string $option_set The desired option set to be returned: 'catalog', 'slick', or 'css'.
 *
 * @return array $defaults Plugin default settings.
*/
function lbs_set_defaults( $option_set ) {
	global $settings;
	$options = $settings;
	$init_options = $options[ $option_set ]['fields'];
	$defaults = array();

	// Prepend option names with 'lbs_'
	foreach ( $init_options as $k => $v ) {
		$id = 'lbs_' . $init_options[ $k ]['id'];
		$val = $init_options[ $k ]['default'];
		$defaults[ $id ] = $val;
	}

	return $defaults;
}

/**
 * Gets catalog settings
 *
 * Builds catalog settings array from the stored/default plugin options.
 * 
 * @since 4.17
 *
 * @global array $lbs_options Plugin settings.
 *
 * @return array $catalog_settings Plugin catalog settings.
*/
function lbs_get_catalog_settings() {
	global $lbs_options;
	$options = $lbs_options;
	$cat = array();

	// Get catalog protocol
	if ( isset( $options['lbs_cat_Protocol'] ) ) {
		$cat['protocol'] = $options['lbs_cat_Protocol'];
	} else {
		$cat['protocol'] = 'https://';
	}

	// Get catalog protocol and domain name
	$cat['domain'] = $cat['protocol'] . $options['lbs_cat_DomainName'];

	// Get catalog system
	$cat['sys'] = $options['lbs_cat_System'];

	// Get example URL used for Other catalog system
	if ( isset( $options['lbs_cat_URL'] ) ) {
		$cat['URL'] = $options['lbs_cat_URL'];
	} else {
		$cat['URL'] = '';
	}

	// Get catalog profile
	$cat['profile'] = $options['lbs_cat_Profile'];

	// Get link target
	if ( isset( $options['lbs_cat_LinkTarget'] ) ) {
		( empty( $options['lbs_cat_LinkTarget'] ) ) ? $cat['target'] = '' : $cat['target'] = "target='_blank'";
	} else {
		$cat['target'] = "target='_blank'";
	}

	// Get catalog image CDN
	$cat['cdn'] = $options['lbs_cat_CDN'];

	// Get example URL used for Other image server
	if ( isset( $options['lbs_cat_CDN_URL'] ) ) {
		$cat['CDN_URL'] = $options['lbs_cat_CDN_URL'];
	} else {
		$cat['CDN_URL'] = '';
	}

	// Get item placeholder image
	if ( isset( $options['lbs_cat_placeholder'] ) ) {
		$cat['placeholder'] = $options['lbs_cat_placeholder'];
	} else {
		$cat['placeholder'] = '';
	}

	// Get customer ID for image CDN
	if ( isset( $options['lbs_cat_CDN_id'] ) ) {
		$cat['CDN_id'] = $options['lbs_cat_CDN_id'];
	} else {
		$cat['CDN_id'] = '';
	}

	// Get customer password for image CDN
	if ( isset( $options['lbs_cat_CDN_pass'] ) ) {
		$cat['CDN_pass'] = $options['lbs_cat_CDN_pass'];
	} else {
		$cat['CDN_pass'] = '';
	}

	// Get cloudLibrary catalog domain
	if ( ! empty( $options['lbs_cat_cloudlibrary'] ) ) {
		$cat['cloudlibrary'] = 'https://' . $options['lbs_cat_cloudlibrary'];
	} else {
		$cat['cloudlibrary'] = '';
	}

	// Get Overdrive catalog domain
	if ( ! empty( $options['lbs_cat_overdrive'] ) ) {
		$cat['overdrive'] = 'https://' . $options['lbs_cat_overdrive'];
	} else {
		$cat['overdrive'] = '';
	}

	return $cat;
}

/**
 * Parse options for Slick slider initialization
 *
 * Assembles plugin and post-specific Slick settings into an array.
 * The returned data is used to initialize a Slick slider.
 * Slick options from post meta override plugin Slick settings.
 *
 * @since 1.4
 * @global array $lbs_options Plugin settings.
 *
 * @param array $options_post Slick settings from post meta.
 *
 * @return array $slick Slick settings.
*/
function lbs_get_slick_opts( $options_post ) {
	global $lbs_options;

	$slick_opts = array();      // Script initialization options
	$slick_data_atts = array(); // Data attribute options to override script options
	$slick_misc = array();      // Other slider options

	foreach ( $lbs_options as $opt => $val ) {
		switch ( $opt ) {
			case 'lbs_slick_accessibility':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'accessibility: false';
				// If the option exists, check if it has a value
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"accessibility": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"accessibility": false' : '';
				}
				break;
			case 'lbs_slick_adaptiveHeight':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'adaptiveHeight: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"adaptiveHeight": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"adaptiveHeight": false' : '';
				}
				break;
			case 'lbs_slick_arrows':
				// defaults: slick: true, plugin: false
				( $val ) ? '' : $slick_opts[] = 'arrows: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"arrows": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"arrows": false' : '';
				}
				break;
			case 'lbs_slick_autoplay':
				// defaults: slick: false, plugin: true
				( ! $val ) ? '' : $slick_opts[] = 'autoplay: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"autoplay": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"autoplay": false' : '';
				}
				break;
			case 'lbs_slick_autoplaySpeed':
				// defaults: slick: 3000, plugin: 3000
				( '3000' === $val ) ? '' : $slick_opts[] = 'autoplaySpeed: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"autoplaySpeed": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_captions':
				// default: off
				( ! $val ) ? $slick_misc['captions'] = false : $slick_misc['captions'] = true;
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_misc['captions'] = true : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_misc['captions'] = false : '';
				}
				break;
			case 'lbs_slick_captions_overlay':
				// default: off
				( ! $val ) ? $slick_misc['captions_overlay'] = false : $slick_misc['captions_overlay'] = true;
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_misc['captions_overlay'] = true : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_misc['captions_overlay'] = false : '';
				}
				break;
			case 'lbs_slick_centerMode':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'centerMode: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"centerMode": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"centerMode": false' : '';
				}
				break;
			case 'lbs_slick_centerPadding':
				// defaults: slick: 50px, plugin: 50px
				( $val ) ? $val = strtolower( preg_replace( '/\s*/', '', $val ) ) : '';
				( '50px' === $val ) ? '' : $slick_opts[] = 'centerPadding: "' . $val . '"';
				if ( ! empty( $options_post[ $opt ] ) ) {
					$options_post[ $opt ] = strtolower( preg_replace( '/\s*/', '', $options_post[ $opt ] ) );
					( $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"centerPadding": "' . $options_post[ $opt ] . '"' : '';
				}
				break;
			case 'lbs_slick_dots':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'dots: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"dots": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"dots": false' : '';
				}
				break;
			case 'lbs_slick_draggable':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'draggable: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"draggable": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"draggable": false' : '';
				}
				break;
			case 'lbs_slick_fade':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'fade: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"fade": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"fade": false' : '';
				}
				break;
			case 'lbs_slick_focusOnChange':
				// defaults: slick: false, plugin: true
				( ! $val ) ? '' : $slick_opts[] = 'focusOnChange: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"focusOnChange": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"focusOnChange": false' : '';
				}
				break;
			case 'lbs_slick_focusOnSelect':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'focusOnSelect: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"focusOnSelect": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"focusOnSelect": false' : '';
				}
				break;
			case 'lbs_slick_infinite':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'infinite: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"infinite": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"infinite": false' : '';
				}
				break;
			case 'lbs_slick_lazyLoad':
				// defaults: slick: ondemand, plugin: ondemand
				if ( ! empty( $options_post[ $opt ] ) ) {
					( 'ondemand' === $val ) ? '' : $slick_opts[] = 'lazyLoad: "' . $val . '"';
					( $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"lazyLoad": "' . $options_post[ $opt ] . '"' : '';
				}
				break;
			case 'lbs_slick_mobileFirst':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'mobileFirst: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"mobileFirst": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"mobileFirst": false' : '';
				}
				break;
			case 'lbs_slick_pauseOnFocus':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'pauseOnFocus: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnFocus": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnFocus": false' : '';
				}
				break;
			case 'lbs_slick_pauseOnHover':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'pauseOnHover: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnHover": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnHover": false' : '';
				}
				break;
			case 'lbs_slick_pauseOnDotsHover':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'pauseOnDotsHover: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnDotsHover": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"pauseOnDotsHover": false' : '';
				}
				break;
			case 'lbs_slick_respondTo':
				// defaults: slick: window, plugin: slider
				( 'window' === $val ) ? '' : $slick_opts[] = 'respondTo: "' . $val . '"';
				if ( ! empty( $options_post[ $opt ] ) ) {
					( $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"respondTo": "' . $options_post[ $opt ] . '"' : '';
				}
				break;
			case 'lbs_slick_responsive':
				// defaults: slick: none, plugin: breakpoints defined
				if ( $val ) {
					$slick_opts[] = 'responsive: ' . $val;
				}
				if ( ! empty( $options_post[ $opt ] ) ) {
					( $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"responsive": "' . $options_post[ $opt ] . '"' : '';
				}
				break;
			case 'lbs_slick_rows':
				// defaults: slick: 1, plugin: 1
				( '1' === $val ) ? '' : $slick_opts[] = 'rows: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"rows": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_slidesPerRow':
				// defaults: slick: 1, plugin: 1
				( '1' === $val ) ? '' : $slick_opts[] = 'slidesPerRow: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"slidesPerRow": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_slidesToShow':
				// defaults: slick: 1, plugin: 6
				( '1' === $val ) ? '' : $slick_opts[] = 'slidesToShow: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"slidesToShow": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_slidesToScroll':
				// defaults: slick: 1, plugin: 1
				( '1' === $val ) ? '' : $slick_opts[] = 'slidesToScroll: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"slidesToScroll": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_speed':
				// defaults: slick: 300, plugin: 300
				( '300' === $val ) ? '' : $slick_opts[] = 'speed: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"speed": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_swipe':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'swipe: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"swipe": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"swipe": false' : '';
				}
				break;
			case 'lbs_slick_swipeToSlide':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'swipeToSlide: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"swipeToSlide": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"swipeToSlide": false' : '';
				}
				break;
			case 'lbs_slick_touchMove':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'touchMove: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"touchMove": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"touchMove": false' : '';
				}
				break;
			case 'lbs_slick_touchThreshold':
				// defaults: slick: 5, plugin: 5
				( '5' === $val ) ? '' : $slick_opts[] = 'touchThreshold: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"touchThreshold": ' . $options_post[ $opt ] : '';
				break;
			case 'lbs_slick_useCSS':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'useCSS: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"useCSS": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"useCSS": false' : '';
				}
				break;
			case 'lbs_slick_useTransform':
				// defaults: slick: true, plugin: false
				( $val ) ? '' : $slick_opts[] = 'useTransform: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"useTransform": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"useTransform": false' : '';
				}
				break;
			case 'lbs_slick_variableWidth':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'variableWidth: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"variableWidth": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"variableWidth": false' : '';
				}
				break;
			case 'lbs_slick_vertical':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'vertical: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"vertical": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"vertical": false' : '';
				}
				break;
			case 'lbs_slick_verticalSwiping':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'verticalSwiping: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"verticalSwiping": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"verticalSwiping": false' : '';
				}
				break;
			case 'lbs_slick_rtl':
				// defaults: slick: false, plugin: false
				( ! $val ) ? '' : $slick_opts[] = 'rtl: true';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"rtl": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"rtl": false' : '';
				}
				break;
			case 'lbs_slick_waitForAnimate':
				// defaults: slick: true, plugin: true
				( $val ) ? '' : $slick_opts[] = 'waitForAnimate: false';
				if ( isset( $options_post[ $opt ] ) ) {
					( ! $val && $options_post[ $opt ] ) ? $slick_data_atts[] = '"waitForAnimate": true' : '';
					( $val && ! $options_post[ $opt ] ) ? $slick_data_atts[] = '"waitForAnimate": false' : '';
				}
				break;
			case 'lbs_slick_zindex':
				// defaults: slick: 1000, plugin: 1000
				( '1000' === $val ) ? '' : $slick_opts[] = 'zIndex: ' . $val;
				( ! empty( $options_post[ $opt ] ) && $val !== $options_post[ $opt ] ) ? $slick_data_atts[] = '"zIndex": ' . $options_post[ $opt ] : '';
				break;
		}
	}

	$slick = array();

	// Convert arrays to comma-delimited strings
	$slick['opts'] = implode( ", ", $slick_opts );
	$slick['atts'] = implode( ", ", $slick_data_atts );
	$slick['misc'] = $slick_misc;

	return $slick;
}

/**
 * Creates CSS rules
 *
 * Gets CSS options from global plugin options array.
 * Assembles and returns CSS rules to be queued.
 *
 * @since 5.4
 * @global object $lbs_options Plugin settings.
 *
 * @return string $css User-modifiable Bookshelf CSS rules.
*/
function lbs_get_css_opts() {
	global $lbs_options;

	if ( isset( $lbs_options['lbs_css_caption_overlay_background_color'] ) && isset( $lbs_options['lbs_css_caption_overlay_background_opacity'] ) ) {
		// Convert hex color to RGB
		list( $r, $g, $b ) = sscanf( $lbs_options['lbs_css_caption_overlay_background_color'], "#%02x%02x%02x" );
	}

	$css = ".bookshelf {
	margin-top: {$lbs_options['lbs_css_bookshelf_margin_top']};
	margin-left: {$lbs_options['lbs_css_bookshelf_margin_side']};
	margin-right: {$lbs_options['lbs_css_bookshelf_margin_side']};
	margin-bottom: {$lbs_options['lbs_css_bookshelf_margin_bottom']};
}

.bookshelf .slick-slide img {
	border-radius: {$lbs_options['lbs_css_bookshelf_image_border_radius']};
	vertical-align: {$lbs_options['lbs_css_bookshelf_image_alignment']};	
	box-shadow: {$lbs_options['lbs_css_bookshelf_image_box_shadow_offset_h']} {$lbs_options['lbs_css_bookshelf_image_box_shadow_offset_v']} {$lbs_options['lbs_css_bookshelf_image_box_shadow_blur']} {$lbs_options['lbs_css_bookshelf_image_box_shadow_spread']} {$lbs_options['lbs_css_bookshelf_image_box_shadow_color']};
}
.bookshelf .overlay, .bookshelf .overlay-grid {
	color: {$lbs_options['lbs_css_caption_overlay_font_color']};
	background-color: rgba( {$r}, {$g}, {$b}, {$lbs_options['lbs_css_caption_overlay_background_opacity']});
	border-radius: {$lbs_options['lbs_css_bookshelf_image_border_radius']};
}

.bookshelf .slick-slide p {
	color: {$lbs_options['lbs_css_caption_font_color']};
	font-size: {$lbs_options['lbs_css_caption_font_size']};
	-webkit-line-clamp: {$lbs_options['lbs_css_caption_max_lines']};
}

.bookshelf .slick-arrow::before {
	color: {$lbs_options['lbs_css_arrow_color']};
	font-size: {$lbs_options['lbs_css_arrow_size']};
}

.bookshelf .slick-prev {
	left: {$lbs_options['lbs_css_arrow_distance']} !important;
}

.bookshelf .slick-next {
	right: {$lbs_options['lbs_css_arrow_distance']} !important;
}

.bookshelf .slick-dots {
	bottom: {$lbs_options['lbs_css_dots_bottom_offset']} !important;
}

.bookshelf .slick-dots li button::before, .bookshelf .slick-dots li.slick-active button::before {
	color: {$lbs_options['lbs_css_dot_color']}
}";

	return $css;
}

/**
 * Generates Bookshelf HTML & JS
 *
 * Uses plugin catalog & slick settings and post meta.
 * Enqueues a Slick initialization script
 *
 * @since 1.0
 * @global array $catalog_settings Plugin catalog settings.
 *
 * @param string $post_id Bookshelf post ID.
 *
 * @return string $html Bookshelf HTML elements.
*/

function lbs_shelveBooks( $post_id ) {
	global $catalog_settings;
	$cat = $catalog_settings;

	// Get the item ID type from the database
	$item_id_type = get_post_meta( $post_id, 'item_id_type', true );
	if ( empty( $item_id_type ) ) {
		$item_id_type = 'isbn';
	}

	// Get item meta from database
	$itemID_meta = get_post_meta( $post_id, 'isbn', true );
	$alt_meta = get_post_meta( $post_id, 'alt', true );

	// Get placeholders
	$placeholders = get_post_meta( $post_id, 'placeholders', true );

	// Combine item meta arrays into a multidimensional array. Cast variables to array in case a post meta field does not exist.
	$items_meta = array_map( null, (array)$itemID_meta, (array)$alt_meta, (array)$placeholders );

	// Get the shuffle_items option from the database
	$shuffle_items = get_post_meta( $post_id, 'shuffle_items', true );

	// Shuffle if true
	if( true == $shuffle_items ) {
		shuffle( $items_meta );
	}

	// Get ebook options from post meta
	$ebooks_meta = get_post_meta( $post_id, 'ebooks', true );

	// Get the link activation status from post meta
	$disable_links = get_post_meta( $post_id, 'disable_links', true );

	// Get input mode
	$list_input = esc_html( get_post_meta( $post_id, 'list_input', true ) );

	// Get the post API.
	$wsapi = esc_html( get_post_meta( $post_id, 'wsapi', true ) );

	// Get post override Slick options
	$post_slick_opts = get_post_meta( $post_id, 'settings', true );

	// Parse Slick options
	$slick = lbs_get_slick_opts( $post_slick_opts );

	// Extract script initialization options
	$slick_opts = $slick['opts'];

	// Extract data attribute options
	$slick_atts = $slick['atts'];

	// Extract other slider options
	$slick_misc = $slick['misc'];

	// If the ebook option is set in the post editor, modify the catalog settings accordingly.
	switch ( $ebooks_meta ) {
		case 'cloudlibrary':
			// Skip if the catalog system is already set to cloudLibrary
			if ( $cat['sys'] === 'cloudlibrary' ) { break; }
			$cat['sys'] = 'cloudlibrary';
			break;
		case 'overdrive':
			// Skip if the catalog system is already set to Overdrive
			if ( $cat['sys'] === 'overdrive' ) { break; }
			$cat['sys'] = 'overdrive';
			break;
		case 'hoopla':
			$cat['sys'] = 'hoopla';
			break;
	}

	// Set unique bookshelf class
	$shelf_class = 'bookshelf-' . $post_id;

	// Assemble Bookshelf HTML
	$html = "<div class='bookshelf " . $shelf_class . "' id='bookshelf-" . $post_id . "' " . ( $slick_atts ? "data-slick='{" . $slick['atts'] . "}'" : "" ) . ">\n";

	if ( empty( $itemID_meta ) ) {
		$html .= "<p>This Bookshelf is empty.</p>";
	} else {
		// Build HTML for each item
		foreach( $items_meta as $item ) {
			$itemID = $item[0];

			// Get alt text for item if set and trim white space, otherwise make it blank
			( isset( $item[1] ) ? $alt = trim( $item[1] ) : $alt = '' );

			// Use no placeholder by default
			$placeholder = false;

			// See if item is marked to use a placeholder image
			if ( isset( $item[2] ) ) {
				$placeholder = $item[2];
			}

			// Make image URL
			$cat_img_url = lbs_get_img_url( $itemID, $list_input, $wsapi, $placeholder );
			$cat_img = "<img src='". $cat_img_url . "' alt='" . $alt . "'>";

			// Make catalog link, or not
			if ( empty( $disable_links ) ) {
				switch ( $cat['sys'] ) {
					case 'alexandria':
						$cat_url = $cat['domain'] . "/search?site=" . $cat['profile'] . "&search=((smart::" . $itemID . "))";
						break;
					case 'atriuum':
						//$cat_url = $cat['domain'] . "/opac/" . $cat['profile'] . "/index.html?mode=main#search:ExpertSearch?view=Bibliographic&SortDescend=0&ST0=I&SF0=" . $itemID;
						$cat_url = $cat['domain'] . "/opac/" . $cat['profile'] . "/#/search/criteria/srd0ic" . $itemID . "UUXc1a0d0r0p0s0";
						break;
					case 'bibliocommons':
						$cat_url = $cat['domain'] . "/search?t=smart&search_category=keyword&q=" . $itemID . "&commit=Search";
						break;
					case 'calibre':
						$cat_url = $cat['domain'] . "/#book_id=" . $itemID . "&library_id=" . $cat['profile'] . "&panel=book_details";
						break;
					case 'cloudlibrary':
						$cat_url = $cat['cloudlibrary'] . "/Search/" . $itemID;
						break;
					case 'cops':
						if ( $cat['profile'] ) {
							$cat_url = $cat['domain'] . "/" . $cat['profile'] . "/index.php?page=13&id=" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/index.php?page=13&id=" . $itemID;
						}
						break;
					case 'dbtextworks':
						$cat_url = $cat['domain'] . "/list?q=identifier_free%3A(" . $itemID . ")";
						break;
					case 'ebsco_eds':
						$cat_url = $cat['protocol'] . "search.ebscohost.com/login.aspx?direct=true&scope=site&authtype=ip,guest&custid=" . $cat['profile'] . "&profile=eds&groupid=main&AN=" . $itemID;
						break;
					case 'encore':
						$cat_url = $cat['domain'] . "/iii/encore/search/C__S" . $itemID . "__Orightresult__U?lang=eng";
						break;
					case 'evergreen':
						$cat_url = $cat['domain'] . "/eg/opac/results?query=" . $itemID . "&qtype=identifier";

						if ( $cat['profile'] ) {
							$cat_url .= "&locg=" .  $cat['profile'];
						}
						break;
					case 'evergreen-record':
						$cat_url = $cat['domain'] . "/eg/opac/record/" . $itemID;

						if ( $cat['profile'] ) {
							$cat_url .= "?locg=" .  $cat['profile'];
						}
						break;
					case 'hoopla':
						$cat_url = "https://hoopladigital.com/search?isbn=" . $itemID;
						break;
					case 'insignia':
						if ( $cat['profile'] ) {
							$cat_url = $cat['domain'] . "/Library/DoSearch?l=" . $cat['profile'] . "&t_input=ISBN&t=ISBN&k=" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/Library/DoSearch?l=All&t_input=ISBN&t=ISBN&k=" . $itemID;
						}
						$cat_url .= "&action=simple";
						break;
					case 'koha':
						$cat_url = $cat['domain'] . "/cgi-bin/koha/opac-search.pl?q=" . $itemID;

						// If itemID is a Bibliobnumber link to the item record.
						if ( strlen( $itemID ) < 10 ) {
							$cat_url = $cat['domain'] . "/cgi-bin/koha/opac-detail.pl?biblionumber=" . $itemID;
						}
						break;
					case 'opac_sbn':
						$cat_url = "https://opac.sbn.it/it/risultati-ricerca-avanzata/-/opac-adv/index/1/?struct%3A3008=ricerca.frase%3A4%3D1&extended_query=%28isbn%3A%28%22" . $itemID . "%22%29%29";
						break;
					case 'openlibrary':
						$cat_url = "https://openlibrary.org/isbn/" . $itemID;
						break;
					case 'overdrive':
						if ( $cat['overdrive'] ) {
							$cat_url = $cat['overdrive'] . "/search/title?isbn=" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/search/title?isbn=" . $itemID;
						}
						break;
					case 'aspen':
					case 'pika':
						if ( ( $wsapi === 'pika-api' || $wsapi === 'aspen-api' ) && 'false' === $list_input ) {
							$cat_url = $cat['domain'] . "/GroupedWork/" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/Search/Results?lookfor0[]=" . $itemID . "&type0[]=ISN";
						}
						break;
					case 'polaris':
						if ( $cat['profile'] ) {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/polaris/view.aspx?ctx=" . $cat['profile'] . "&UPC=" . $itemID;
							} else {
								$cat_url = $cat['domain'] . "/polaris/view.aspx?ctx=" . $cat['profile'] . "&ISBN=" . $itemID;
							}
						} else {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/polaris/view.aspx?UPC=" . $itemID;
							} else {
								$cat_url = $cat['domain'] . "/polaris/view.aspx?ISBN=" . $itemID;
							}
						}
						break;
					case 'polaris63':
						if ( $cat['profile'] ) {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/view.aspx?ctx=" . $cat['profile'] . "&UPC=" . $itemID;
							} else {
								$cat_url = $cat['domain'] . "/view.aspx?ctx=" . $cat['profile'] . "&ISBN=" . $itemID;
							}
						} else {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/view.aspx?UPC=" . $itemID;
							} else {
								$cat_url = $cat['domain'] . "/view.aspx?ISBN=" . $itemID;
							}
						}
						break;
					case 'primo':
						// Change URL subdirectory based on whether the catalog is hosted on exlibrisgroup.com or is self-hosted by the institution.
						if ( false === strpos( $cat['domain'], 'exlibrisgroup' ) ) {
							$cat_url = $cat['domain'] . '/discovery';
						} else {
							$cat_url = $cat['domain'] . '/primo-explore';
						}

						$cat_url .= '/search?query=any,contains,' . $itemID . '&tab=default_tab&search_scope=default_scope&vid=' . $cat['profile'];
						break;
					case 'sirsi_ent':
						if ( $cat['profile'] ) {
							$cat_url = $cat['domain'] . "/client/" . $cat['profile'] . "/search/results?qu=" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/client/default/search/results?qu=" . $itemID;
						}
						break;
					case 'sirsi_horizon':
						$cat_url = $cat['domain'] . "/ipac20/ipac.jsp?index=ISBNEX&term=" . $itemID . "&te=ILS&rt=ISBN|||ISBN|||false";
						break;
					case 'spydus':
						$cat_url = $cat['domain'] . "/cgi-bin/spydus.exe/ENQ/WPAC/BIBENQ?SETLVL=&SBN=" . $itemID;
						break;
					case 'surpass':
						$cat_url = $cat['domain'] . "/searchlist?type=Marc&keyword=" . $itemID . "&searchin=020&exact=false";
						break;
					case 'tlc':
						$cat_url = $cat['domain'] . "/?section=search&term=" . $itemID;
						break;
					case 'tlc_ls1':
						if ( $cat['profile'] ) {
							$cat_url = $cat['domain'] . "/TLCScripts/interpac.dll?Search&Config=" . $cat['profile'] . "&SearchType=1&SearchField=4096&SearchData=" . $itemID;
						} else {
							$cat_url = $cat['domain'] . "/TLCScripts/interpac.dll?Search&Config=pac&SearchType=1&SearchField=4096&SearchData=" . $itemID;
						}
						break;
					case 'vega':
						$cat_url = $cat['domain'] . "/search?query=" . $itemID;
						break;
					case 'webpac':
						if ( $cat['profile'] ) {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/search/?searchtype=X&searcharg=" . $itemID . "+&searchscope=" . $cat['profile'];
							} else {
								$cat_url = $cat['domain'] . "/search~S" . $cat['profile'] . "/i" . $itemID;
							}
						} else {
							if ( strlen( $itemID ) === 12 ) {
								$cat_url = $cat['domain'] . "/search/?searchtype=X&searcharg=" . $itemID;
							} else {
								$cat_url = $cat['domain'] . "/search/i" . $itemID;
							}
						}
						break;
					case 'worldcat':
						$cat_url = "https://www.worldcat.org/search?q=" . $itemID;
						break;
					case 'worldcatds':
						$cat_url = $cat['domain'] . "/search?queryString=" . $itemID;
						break;
					case 'other':
						$cat_url = str_ireplace( "{ID}", $itemID, $cat['URL'] );
						break;
				}

				$html .= "<div><a href='" . $cat_url . "' ". $cat['target'] .">";

				// Add caption element if setting is enabled
				$overlay_class = 'overlay';

				// Check if grid layout is used and modify CSS class
				if ( false === ( strpos( $slick['opts'], '"rows"' ) || strpos( $slick['atts'], '"rows"' ) ) ) {
					$overlay_class .= '-grid';
				}
				if ( true === $slick_misc['captions_overlay'] ) {
					$html .= "<div class='" . $overlay_class . "'><span>" . $alt . "</span></div>";
				}

				$html .= $cat_img . "</a>";

				// Add caption element if setting is enabled
				if ( true === $slick_misc['captions'] ) {
					$html .= "<div class='caption'><p>" . $alt . "</p></div>";
				}

				$html .= "</div>\n";
			} else {
				// Create item images without links
				$html .= "<div>" . $cat_img . "</div>\n";
			}
		}
	}

	$html .= "</div>\n";

	// Make slick initialization script
	$js = "jQuery(document).ready(function($){ $('." . $shelf_class . "').slick({" . $slick_opts . "}); });";

	// Register a dummy handle for Slick initilizaion script
	wp_register_script( 'bookshelf-init', '' );
	wp_enqueue_script( 'bookshelf-init' );

	// Insert Slick initilizaion script
	wp_add_inline_script( 'bookshelf-init', $js );

	return $html;
}

/**
 * Generates Bookshelf image URLs
 *
 * Uses plugin catalog & slick settings and post meta.
 * Enqueues a Slick initialization script
 *
 * @since 4.17
 * @global array $catalog_settings Plugin catalog settings.
 *
 * @param string $itemID      Item identifier
 * @param bool   $list_input  List or API input mode indicator
 * @param string $wsapi       The API selected in the post editor.
 * @param bool   $placeholder Indicates if this item will use a custom placeholder image.
 *
 * @return string $cat_img_url Item cover art image URL
*/
function lbs_get_img_url( $itemID, $list_input, $wsapi = false, $placeholder = false ) {
	global $catalog_settings;
	$cat = $catalog_settings;

	// Check for Koha bibliobnumbers and change image URL
	if ( $cat['sys'] === 'koha' && strlen( $itemID ) < 10 ) {
		$cat['cdn'] = 'koha';
	}

	// If item is marked to use placeholder image override the image CDN and use the placeholder
	if ( ! empty( $cat['placeholder'] ) ) {
		if ( $placeholder ) {
			$cat['cdn'] = 'placeholder';
		}
	}

	switch ( $cat['cdn'] ) {
		case 'amazon':
			$cat_img_url = "https://images-na.ssl-images-amazon.com/images/P/" . $itemID . ".jpg";
			break;
		case 'calibre':
			$cat_img_url = $cat['domain'] . "/get/thumb/" . $itemID . "/" . $cat['profile'] . "?sz=300x400";
			break;
		case 'cops':
			if ( $cat['profile'] ) {
				$cat_img_url = $cat['domain'] . "/" . $cat['profile'] .  "/fetch.php?id=" . $itemID;
			} else {
				$cat_img_url = $cat['domain'] . "/fetch.php?id=" . $itemID;
			}
			break;
		case 'chilifresh':
			$cat_img_url = "https://content.chilifresh.com/?isbn=" . $itemID . "&size=L";
			break;
		case 'contentcafe':
			if ( $cat['CDN_id'] ) {
				$cat_img_url = "https://contentcafe2.btol.com/ContentCafe/Jacket.aspx?UserID=" . $cat['CDN_id'] . "&Password=" . $cat['CDN_pass'] . "&Return=T&Type=M&Value=" . $itemID;
			} else {
				$cat_img_url = "https://contentcafe2.btol.com/ContentCafe/Jacket.aspx?UserID=ContentCafeClient&Password=Client&Return=T&Type=M&Value=" . $itemID;
			}
			break;
		case 'ebsco':
			$cat_img_url = "https://rps2images.ebscohost.com/rpsweb/othumb?id=NL\$" . $itemID . "\$EPUB&s=l";
			break;
		case 'encore':
			if ( strlen( $itemID ) === 12 ) {
				$cat_img_url = $cat['domain'] . "/iii/encore/home?image_size=full&isxn=&lang=eng&service=BibImage&upc=" . $itemID;
			} else {
				$cat_img_url = $cat['domain'] . "/iii/encore/home?image_size=full&isxn=" . $itemID . "&lang=eng&service=BibImage&upc=";
			}
			break;
		case 'evergreen':
			$cat_img_url = $cat['domain'] . "/opac/extras/ac/jacket/medium/" . $itemID;
			break;
		case 'evergreen-record':
			$cat_img_url = $cat['domain'] . "/opac/extras/ac/jacket/medium/r/" . $itemID;
			break;
		case 'koha':
			$cat_img_url = $cat['domain'] . "/cgi-bin/koha/opac-image.pl?biblionumber=" . $itemID;
			break;
		case 'opac_sbn':
			$cat_img_url = "https://opac.sbn.it/o/alphabetica-api/detail-image?id=" . $itemID . "&format=preview";
			break;
		case 'openlibrary':
			// Check for OpenLibrary OLID, otherwise look up ISBN
			if ( false !== stripos( $itemID, 'OL' ) ) {
				$cat_img_url = "https://covers.openlibrary.org/b/olid/" . $itemID . "-M.jpg";
			} else {
				$cat_img_url = "https://covers.openlibrary.org/b/isbn/" . $itemID . "-M.jpg";
			}
			break;
		case 'aspen':
		case 'pika':
			if ( ( $wsapi === 'pika-api' || $wsapi === 'aspen-api' ) && 'false' === $list_input ) {
				$cat_img_url = $cat['domain'] . "/bookcover.php?id=" . $itemID . "&size=medium&type=grouped_work";
			} else {
				$cat_img_url = $cat['domain'] . "/bookcover.php?size=large&isn=" . $itemID . "&upc=" . $itemID;
			}
			break;
		case 'syndetics':
			if ( strlen( $itemID ) === 12 ) {
				$item_id_type = 'upc';
			} else {
				$item_id_type = 'isbn';
			}

			$cat_img_url = "https://syndetics.com/index.aspx?" . $item_id_type . "=" . $itemID . "/LC.GIF";

			if ( $cat['CDN_id'] ) {
				$cat_img_url = $cat_img_url . "&client=" . $cat['CDN_id'];
			}
			break;
		case 'tlc':
			$cat_img_url = "https://ls2content.tlcdelivers.com/tlccontent?customerid=" . $cat['CDN_id'] . "&appid=ls2pac&requesttype=BOOKJACKET-MD&Isbn=" . $itemID;
			break;
		case 'other':
			$cat_img_url = str_ireplace( "{ID}", $itemID, $cat['CDN_URL'] );
			break;
		case 'placeholder':
			$cat_img_url = $cat['placeholder'];
			break;
	}

	return $cat_img_url;
}

/**
 * Checks for valid cover art images
 *
 * If the selected image server uses placeholder images for missing cover art a placeholder image is downloaded and compared to images at item image URLs.
 * Comparison is done using the fastest hash algorithm available in pre-8.0 PHP. If hashes can't be compared pixel dimensions are checked against a known
 * size of the image server placeholders. If hashes match or if image width is <= placeholder known width the item is marked to use a user-selected
 * placeholder image. Not all image server placeholder images can be checked for.
 *
 * @since 5.0
 * @global array $catalog_settings Plugin catalog settings.
 *
 * @param array $itemIDs      Item identifiers.
 * @param bool  $list_input   List or API input mode indicator.
 * @param array $placeholders Array marking which items in a Bookshelf will use custom placeholder images.
 *
 * @return string $cat_img_url Item cover art image URL.
*/
function lbs_check_images( $itemIDs, $list_input, $wsapi = false ) {
	global $catalog_settings;
	$cat = $catalog_settings;

	// Don't bother checking images if the user hasn't selected a custom placeholder image.
	if ( empty( $cat['placeholder'] ) ) {
		return '';
	}

	$placeholders = array();

	// Set exclusion parameters for image CDNs
	switch( $cat['cdn'] ) {
		case 'encore':
		// Don't check. Encore uses user agent filtering, so we can't check the images without pretending to be a web browser.
		case 'contentcafe':
		// Don't check. ContentCafe has a nicer looking placeholder now, and so far comparing two seemingly identical placeholders by hash hasn't worked.
			return '';
			break;
		case 'amazon':
		case 'tlc':
			$x_min = 1;
			// Grab and hash a default placeholder image from the image server
			$placeholder_img_url = lbs_get_img_url( '0', $list_input, $wsapi );
			file_put_contents( 'placeholder.img', file_get_contents( $placeholder_img_url ) );
			$placeholder_hash = hash_file( 'crc32', 'placeholder.img' );
			break;
		case 'aspen':
		case 'opac_sbn':
		case 'openlibrary':
		case 'pika':
			$x_min = 60;
			// Grab and hash a default placeholder image from the image server
			$placeholder_img_url = lbs_get_img_url( '00', $list_input, $wsapi );
			file_put_contents( 'placeholder.img', file_get_contents( $placeholder_img_url ) );
			$placeholder_hash = hash_file( 'crc32', 'placeholder.img' );
			break;
		default:
			$x_min = 1;
			break;
	}

	// Check for valid images
	foreach ( $itemIDs as $k => $v ) {
		$cat_img_url = lbs_get_img_url( $v, $list_input, $wsapi );

		if ( file_is_valid_image( $cat_img_url ) ) {
			// If we can compare image hashes...
			if( isset( $placeholder_hash ) ) {
				// Save the image associated with the item ID
				file_put_contents( 'temp.img', file_get_contents( $cat_img_url ) );
				
				// Get the md5 hashes of the image
				$hash = hash_file( 'crc32', 'temp.img');

				// Compare the hashes and mark if custom placeholder will replace the default.
				if ($hash === $placeholder_hash) {
					$placeholders[$k] = true;
				} else {
					$placeholders[$k] = false;
				}
			} else {
				// If we can't check file hashes, check image sizes
				$size = getimagesize( $cat_img_url );

				if ( $size ) {
					// Compare image width to the min width
					if( $size[0] <= $x_min ) {
						$placeholders[$k] = true;
					} else {
						$placeholders[$k] = false;
					}
				} else {
					$placeholders[$k] = true;
				}
			}
		} else {
			$placeholders[$k] = true;
		}
	}
	return $placeholders;
}

/**
 * Updates Bookshelf items from API
 *
 * Gets post meta and sends to lbs_get_items_from_api().
 * 
 * @since 4.6
 * @param string $post_id Bookshelf post ID.
*/
function lbs_update_items_from_api( $post_id ) {
	$meta = get_post_meta( $post_id );

	foreach ( $meta as $k => $v ) {
		$meta[ $k ] = $v[0];
	}

	lbs_get_items_from_api( $post_id, $meta );
}

/**
 * Gets items from API and (re)schedules post updates
 *
 * Updates post API settings metadata with user-submitted data.
 * Processes API response data for use in Bookshelves.
 * Schedules next automated post update.
 * 
 * @since 4.6
 * @global array $catalog_settings Plugin catalog settings.
 * 
 * @param string $post_id  Bookshelf post ID.
 * @param array  $api_meta Post metadata related to catalog API
*/
function lbs_get_items_from_api( $post_id, $api_meta ) {
	global $catalog_settings;
	$cat = $catalog_settings;

	update_post_meta( $post_id, 'wsapi', $api_meta['wsapi'] );
	update_post_meta( $post_id, 'ws-key', $api_meta['ws-key'] );
	update_post_meta( $post_id, 'ws-secret', $api_meta['ws-secret'] );
	update_post_meta( $post_id, 'ws-token-url', $api_meta['ws-token-url'] );
	update_post_meta( $post_id, 'ws-request', $api_meta['ws-request'] );
	update_post_meta( $post_id, 'ws-json', $api_meta['ws-json'] );
	update_post_meta( $post_id, 'item_id_type', $api_meta['item_id_type'] );
	update_post_meta( $post_id, 'schedule', $api_meta['schedule'] );

	// wp_remote_get() args
	$wprg_args = array( 'timeout' => 30, 'sslverify' => true );
	$err_msg = '';
	$itemIDs = array();
	$alts = array();

	// Process web service request result into meta
	switch ( $api_meta['wsapi'] ) {
		case 'cops-api':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}
			$response = json_decode( $response['body'], true );

			// Stop on 0 results
			if ( ! $response['entries'] || $response['containsBook'] === 0 ) {
				break;
			}

			foreach ( $response['entries'] as $title ) {
				$itemIDs[] = $title['book']['id'];
				$alt = $title['title'];

				if ( ! empty ( $title['book']['authorsName'] ) ) {
					$alt .= ' by ' . $title['book']['authorsName'];
				}

				$alts[] = $alt;
			}

			if ( $response['multipleDatabase'] ) {
				foreach ( $itemIDs as &$itemID ) {
					$itemID .= "&db=" . $response['databaseId'];
				}
				unset( $itemID );
			}
			break;

		case 'eg-supercat':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			// Convert XML into SimpleXML object
			$xml = simplexml_load_string( $response['body'] );

			foreach ( $xml->mods as $title ) {
				// Get record numbers if catalog is set to evergreen-record, otherwise get ISBNs
				if ( $cat['sys'] === 'evergreen-record' ) {
					$item_id = lbs_trim_item_id( (string)$title->recordInfo->recordIdentifier[0] );
					$itemIDs[] = $item_id;
				} else {
					// Look for a UPC, which is preferable for items with UPCs, e.g. DVDs
					$identifiers = (array)$title->identifier;

					// Throw out the @attributes element. We only want identifiers.
					unset( $identifiers['@attributes'] );

					// Find a 12-digit UPC number among the identifiers
					$item_id = preg_grep( "/^\d{12}$/", $identifiers );
					if ( ! $item_id ) {
						// If no UPC find the 1st ISBN10 or ISBN13 that's not all 0s
						$item_id = preg_grep( "/^(\d{10}|\d{13})$(?<!0000000000)/", $identifiers );
					}

					if ( ! empty( $item_id ) ) {
						// Reindex array
						$item_id = array_merge( $item_id );
						$itemIDs[] = (string)$item_id[0];
					}
				}

				// If there's a valid item ID, get the item title
				if ( ! empty( $item_id ) ) {
						// Get item title
						$alt = lbs_trim_item_title( (string)$title->titleInfo->title );

						// Prepend a nonsorted title word (e.g. "The") if it exists
						if ( isset( $title->titleInfo->nonSort ) ) {
							$alt = (string)$title->titleInfo->nonSort . $alt;
						}

						// Append the author/producer from the first note field if it exists
						if ( ! empty ( $title->note[0] ) ) {
							$alt .= ' by ' . lbs_trim_author( (string)$title->note[0] );
						}

						$alts[] = $alt;
				}
			}
			break;

		case 'json':
		case 'koha-rws':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			foreach ( $response as $title ) {
				// If CDN is Amazon, look for 10-digit ISBNs. Otherwise use first ISBN or Biblionumber.
				if ( $cat['cdn'] == 'amazon' ) {
					// Look for ISBN10s
					preg_match( "/\b\S{10}\b/", $title[0], $IDs );

					// No ISBN10s? Get whatever identifier is there. It might be a Biblionumber.
					if ( empty( $IDs ) ) {
						$IDs = explode ( " | ", $title[0] );
					}
				} else {
					// Grab the first identifier
					$IDs = explode ( " | ", $title[0] );
				}

				// If an identifier is found, get the other item data.
				if ( ! empty( $IDs ) ) {
					// Get the first identifier in case there are multiple
					$itemIDs[] = $IDs[0];

					if ( isset ( $title[1] ) ) {
						$alt = $title[1];

						if ( isset ( $title[2] ) ) {
							$alt .= ' by ' . lbs_trim_author( $title[2] );
						}	
					} else {
						$alt = '';
					}

					$alts[] = $alt;
				}
			}
			break;

		case 'koha-rss':
			// Build SimplePie object from RSS feeed
			$feedURL = urldecode( $api_meta['ws-request'] );
			$feed = fetch_feed( $feedURL );

			// Stop on error & set error message
			if ( is_wp_error( $feed ) ) {
				$err_msg = 'Error: ' . $feed->get_error_message();
				break;
			}
			if ( $feed->get_item_quantity() == 0 ) {
				$err_msg = 'No items in this feed.';
				break;
			}

			$items = $feed->get_items();

			foreach( $items as $item ) {
				// Get the description field from the feed item
				$description = $item->get_description();

				// Get the dc:identifier tag for the feed item
				$dcidentifier = $item->get_item_tags( 'http://purl.org/dc/elements/1.1/', 'identifier' );

				$item_id = array();

				// Look for item ID in image URL
				switch( $cat['cdn'] ) {
					case 'amazon':
						preg_match( "/(?<=[pP]\/)(\d+)/", $description, $item_id );
						break;
					case 'contentcafe':
						preg_match( "/(?<=[vV]alue=)(\d+[xX]?)/", $description, $item_id );
						break;
					case 'syndetics':
						preg_match( "/(?<=isbn=)(\d+[xX]?)/", $description, $item_id );

						if ( empty( $item_id ) ) {
							preg_match( "/(?<=upc=)(\d+)/", $description, $item_id );
						}
						break;
				}

				// If no item ID is found in a feed item image URL...
				if ( empty( lbs_trim_item_id( $item_id ) ) ) {
					// ...look in the dc:identifier XML tag
					if ( $dcidentifier ) {
						$item_id = explode( '|', $dcidentifier[0]['data'] );
						// Trim each identifier
						foreach( $item_id as &$id ) {
							$id = lbs_trim_item_id( $id );
						}
					}

					// If no identifier is in the dc:identifier XML tag, get the biblionumber
					if ( empty( $item_id[0] ) ) {
						$item_link = $item->get_link();
						preg_match( "/(?<=biblionumber=)\d+/i", $item_link, $item_id );
					}
				}

				$itemIDs[] = $item_id[0];
				$alts[] = lbs_trim_item_title( $item->get_title() );
			}
			break;

		case 'nytbooks':
			$response = wp_remote_get( $api_meta['ws-request'] . '?api-key=' . $api_meta['ws-key'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			// Stop on error status
			if ( $response['status'] !== "OK" ) {
				$err_msg = 'Request URL error: Unknown';
				break;
			}

			foreach ( $response['results']['books'] as $title ) {
				if ( ! empty ( $title['primary_isbn13'] ) ) {
					$itemIDs[] = $title['primary_isbn13'];
				} else {
					$itemIDs[] = $title['primary_isbn10'];
				}

				$alt = $title['title'];

				if ( ! empty ( $title['author'] ) ) {
					$alt .= ' by ' . lbs_trim_author( $title['author'] );
				}

				$alts[] = $alt;
			}
			break;

		case 'openlibrary':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			// Check if returned list has no items
			if ( empty( $response['work_count'] ) && empty( $response['size'] ) && empty( $response['numFound'] ) ) {
				break;
			}

			// Look for works (subject lists), entries (user-created lists), or docs (author.
			if ( isset( $response['works'] ) ) {
				$items = $response['works'];
			} else if ( isset( $response['entries'] ) ) {
				$items = $response['entries'];
			} else if ( isset( $response['docs'] ) ) {
				$items = $response['docs'];
			}

			foreach ( $items as $item ) {
				// If the work has a cover edition key, use that instead of the work key.
				if ( isset( $item['cover_edition_key'] ) ) {
					$OLID = $item['cover_edition_key'];
				} else {
					if ( isset( $item['key'] ) ) {
						$OLID = ltrim( $item['key'], '/works/' );
					} else {
						// If no key, use the url
						$OLID = ltrim( $item['url'], '/books/' );
					}
				}

				$itemIDs[] = $OLID;
				$alt = $item['title'];

				if ( isset( $item['authors'] ) ) {
					$alt .= ' by ' . lbs_trim_author( $item['authors'][0]['name'] );
				} else if ( isset( $item['author_name'] ) ) {
					$alt .= ' by ' . lbs_trim_author( $item['author_name'][0] );
				}

				$alts[] = $alt;
			}
			break;

		case 'aspen-api':
		case 'pika-api':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			// Check which API we're using
			if ( str_contains( strtolower( $api_meta['ws-request'] ), 'listapi' ) ) {
				// Stop if list ID is invalid
				if ( ! $response['result']['success'] ) {
					break;
				}

				// Process results from the List API
				foreach ( $response['result']['titles'] as $title ) {
					$itemIDs[] = $title['id'];
					$alt = $title['title'];

					if ( ! empty ( $title['author'] ) ) {
						$alt .= ' by ' . lbs_trim_author( $title['author'] );
					}

					$alts[] = $alt;
				}
			} else {
				// Stop if no results
				if ( $response['result']['recordCount'] == 0 ) {
					break;
				}
				
				// Process results from the Search API
				foreach ( $response['result']['recordSet'] as $title ) {
					$itemIDs[] = $title['id'];
					$alt = $title['title_display'];

					if ( ! empty ( $title['author_display'] ) ) {
						$alt .= ' by ' . lbs_trim_author( $title['author_display'] );
					}

					$alts[] = $alt;
				}
			}
			break;

		case 'sirsi-rss':
			// Build SimplePie object from RSS feeed
			$feedURL = urldecode( $api_meta['ws-request'] );
			$feed = fetch_feed( $feedURL );

			// Stop on error & set error message
			if ( is_wp_error( $feed ) ) {
				$err_msg = 'Error: ' . $feed->get_error_message();
				break;
			}
			if ( $feed->get_item_quantity() == 0 ) {
				$err_msg = 'No items in this feed.';
				break;
			}

			// Get feed items
			$items = $feed->get_items();

			// Roll through items to find item IDs and titles
			foreach( $items as $item ) {
				$title = $item->get_title();
				$content = $item->get_content();

				$item_id = array();

				// Look for ISBN in item content
				preg_match( "/(?<=ISBN&#160;)(\d+[xX]?)/", $content, $item_id );

				// If no ISBN look for UPC
				if ( empty( $item_id ) ) {
					preg_match( "/(?<=UPC&#160;)(\d+)/", $content, $item_id );
				}

				// If no item ID is found, skip the item
				if ( $item_id ) {
					$itemIDs[] = $item_id[0];
					$alts[] = lbs_trim_item_title( $item->get_title() );
				}
			}
			break;

		case 'symws':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			// Get SYMWS error
			if ( $response['faultResponse'] ) {
				$err_msg = $response['faultResponse']['string'];
				break;
			}

			switch ( $api_meta['item_id_type'] ) {
				case 'upc':
					foreach ( $response['HitlistTitleInfo'] as $item ) {
						if ( strlen( $item['UPC'][ 0 ] ) !== 0 ) {
							$itemIDs[] = $item['UPC'][ 0 ];
							$alt = lbs_trim_item_title( $item['title'] );

							if ( ! empty( $item['author'] ) ) {
								$alt .= ' by ' . lbs_trim_author( $item['author'] );
							}

							$alts[] = $alt;
						}
					}
					break;
				case 'isbn':
				default:
					foreach ( $response['HitlistTitleInfo'] as $item ) {
						if ( strlen( $item['ISBN'][ 0 ] ) !== 0 ) {
							$itemIDs[] = $item['ISBN'][ 0 ];
							$alt = lbs_trim_item_title( $item['title'] );
							if ( ! empty( $item['author'] ) ) {
								$alt .= ' by ' . lbs_trim_author( $item['author'] );
							}
							$alts[] = $alt;
						}
					}
					break;
			}
			break;

		case 'sierra-api':
			$data = lbs_get_sierra_api_data( $api_meta['ws-token-url'], $api_meta['ws-request'], $api_meta['ws-json'], $api_meta['ws-key'], $api_meta['ws-secret'] );
			
			// If we get an error message pass it along to be stored in post meta
			if ( is_string( $data ) ) {
				$err_msg = $data;
				break;
			}
			
			foreach ( $data as $item ) {
				$itemIDs[] = $item['id'];
				$alts[] = $item['alt'];
			}
			break;

		case 'tlcls2pac':
			$response = wp_remote_get( $api_meta['ws-request'], $wprg_args );

			// Stop on error & set error message
			if ( is_wp_error( $response ) ) {
				$err_msg = 'Error: ' . $response->get_error_message();
				break;
			}
			if ( $response['response']['code'] !== 200 ) {
				$err_msg = 'Request URL error: ' . $response['response']['message'];
				break;
			}

			$response = json_decode( $response['body'], true );

			// Get the first identifier from the list of standard numbers
			foreach ( $response['resources'] as $item ) {
				// Skip the item it it has no identifier
				if ( ! empty( $item['standardNumbers'] ) ) {
					foreach ( $item['standardNumbers'] as $standardNumber ) {
						// Get the first item ID
						$itemIDs[] = $standardNumber['data'];
						$alts[] = $item['shortTitle'];
						break;
					}
				}
			}
			break;
	}

	if ( $itemIDs ) {
		// Remove duplicates
		// Find uniques
		$items_unique = array_unique( $itemIDs );
		$alts_unique = array_unique( $alts );

		// Find duplicates
		$item_dupes = array_diff_assoc( $itemIDs, $items_unique );
		$alt_dupes = array_diff_assoc( $alts, $alts_unique );

		// Get duplicates keys
		$item_dupe_keys = array_keys( $item_dupes );
		$alt_dupe_keys = array_keys( $alt_dupes );

		// Merge duplicate key arrays
		$dupe_keys = array_merge( $item_dupe_keys, $alt_dupe_keys );

		// If there are duplicate keys, remove those keys from item and alt arrays
		if ( ! empty( $dupe_keys ) ) {
			foreach( $dupe_keys as $key ) {
				unset( $itemIDs[$key] );
				unset( $alts[$key] );
			}
		}

		// Fix capitalization of alt text
		$alts = array_map( 'lbs_capitalize', $alts );
		
		// Escape alt text for use in HTML
		$alts = array_map( 'esc_attr', $alts );

		// Process items into post meta
		update_post_meta( $post_id, 'isbn', $itemIDs );
		update_post_meta( $post_id, 'alt', $alts );
		
		// Clear existing error message
		$err_msg = '';
		update_post_meta( $post_id, 'error_message', $err_msg );
	} else {
		// Save error message to post meta
		update_post_meta( $post_id, 'error_message', $err_msg );
	}

	// Schedule Bookshelf updates
	// If update is scheduled, unschedule
	lbs_unschedule_event( $post_id );

	// Schedule if user selection is not 'none'
	$schedule = $api_meta['schedule'];

	if ( 'none' !== $schedule ) {
		$hook = 'update_bookshelf';
		$args = array( strval( $post_id ) );
		$schedules = wp_get_schedules();
		$next_run = time() + $schedules[ $schedule ]['interval'];
		wp_schedule_event( $next_run, $schedule, $hook, $args );
	}
}

/**
 * Processes Sierra API OAuth 2.0 requests
 *
 * Make Sierra Items and/or Bibs API requests and process responses.
 * Extracts item identifiers (ISBN or UPC), titles, and authors from response data.
 * Removes items with duplicate or missing identifiers.
 *
 * @since 4.5
 *
 * @param string $token_url 	API URL for retrieving access token.
 * @param string $request_url 	URL to send API request.
 * @param string $json_query 	JSON query to send to API.
 * @param string $key 			API key.
 * @param string $secret		API secret.
 *
 * @return array $items 		Array containing item identifiers and titles.
*/
function lbs_get_sierra_api_data( $token_url, $request_url, $json_query, $key, $secret ) {
	// Generate authorization key
	$authkey = base64_encode( "$key:$secret" );

	// Request access token
	$header = array( 'Authorization' => 'Basic ' . $authkey );
	$body = array( 'grant_type' => 'client_credentials' );
	$response = wp_remote_post(
		$token_url,
		array(
			'headers' => $header,
			'body'    => $body,
		)
	);

	$items = array();

	// Catch token URL errors
	if ( is_wp_error( $response ) ) {
		return 'Token URL error: ' . $response->get_error_message();
	}
	if ( $response['response']['code'] !== 200 ) {
		return 'Token URL error: ' . $response['response']['message'];
	}

	$access_token = json_decode( $response['body'] )->access_token;

	// Send request URL query
	$header = array(
		'Authorization' => 'Bearer ' . $access_token,
		'Content-Type'  => 'application/json;charset=UTF-8',
	);

	$body = stripslashes( $json_query );

	$response = wp_remote_post(
		$request_url,
		array(
			'headers' => $header,
			'body'    => $body,
		)
	);

	// Catch request URL errors
	if ( is_wp_error( $response ) ) {
		return 'Request URL error: ' . $response->get_error_message();
	}
	if ( $response['response']['code'] !== 200 ) {
		return 'Request URL error: ' . $response['response']['message'] . " " . print_r( $response['body'], true );
	}

	// Get the list of results
	$list_json = json_decode( $response['body'] );
	
	if ( empty( $list_json ) ) {
		return 'Query returned no results.';
	}
	
	// If the request URL points to the Items API, 
	if ( strpos($request_url, '/items/') ) {
		// Set header to request data in JSON from Items API
		$header['Accept'] = 'application/json';

		foreach ( $list_json->entries as &$item ) {
			// Get Bib IDs from item records
			$response = wp_remote_get(
				$item->link . '?fields=bibIds',
				array( 'headers' => $header )
			);

			// Overwrite item links with bib links
			if ( ! is_wp_error( $response ) ) {
				$item_json = json_decode( $response['body'], true );
				$item->link = str_replace( '/items/' . $item_json['id'], '/bibs/' . $item_json['bibIds'][0], $item->link );
			}
		}
		unset( $item );
	}

	// Set header to request JSON MARC data from Bibs API
	$header['Accept'] = 'application/marc-in-json';

	// Roll through each item in the Bibs list
	foreach ( $list_json->entries as $item ) {
		$itemID = '';
		$alt = '';

		$item_url = $item->link . '/marc';

		$response = wp_remote_get(
			$item_url,
			array( 'headers' => $header )
		);
		
		if ( ! is_wp_error( $response ) ) {
			// Decode JSON as array.
			$json = json_decode( $response['body'], true );

			if ( isset( $json['fields'] ) ) {
				// Get ISBNs in record.
				$marc_isbn = array_column( $json['fields'], '020' );

				// Get UPCs in record.
				$marc_upc = array_column( $json['fields'], '024' );

				// Get MARC field with item title
				$marc_title = array_column( $json['fields'], '245' );
			}

			// Get item title from MARC
			if ( ! empty( $marc_title ) ) {
				$alt = lbs_trim_item_title( $marc_title[0]['subfields'][0]['a'] );
			}

			//Get the first ISBN in the record, stop when found
			foreach ( $marc_isbn as $isbn ) {
				if ( ! empty( $isbn['subfields'][0]['a'] ) ) {
					$itemID = lbs_trim_item_id( $isbn['subfields'][0]['a'] );
					break;
				}
			}

			// Make sure we have an ISBN.
			if ( ! empty( $itemID ) ) {
				// Look for author in MARC field 245
				if ( isset( $marc_title[0]['subfields'][2]['c'] ) ) {
					$alt .= ' by ' . lbs_trim_author( $marc_title[0]['subfields'][2]['c'] );
				} elseif ( is_array( $json['fields'] ) ) {
					// Look for author in MARC field 100
					$marc_author = array_column( $json['fields'], '100' );
					if ( ! empty( $marc_author ) ) {
						$alt .= ' by ' . lbs_trim_author( $marc_author[0]['subfields'][0]['a'] );
					}
				}
			}

			// If no ISBN is found in record get the first UPC
			if ( empty( $itemID ) ) {
				foreach ( $marc_upc as $upc ) {
					if ( ! empty( $upc['subfields'][0]['a'] ) ) {
						$itemID = lbs_trim_item_id( $upc['subfields'][0]['a'] );
						break;
					}
				}
			}

			// Skip duplicate and empty IDs
			if ( ! empty( $itemID ) || in_array( $itemID, array_column( $items, 'id' ), true ) ) {
				$items[] = array(
					'id'  => $itemID,
					'alt' => $alt,
				);
			}
		}
	}
	return $items;
}

/**
 * Trims item titles
 *
 * Trims item titles at :, /, comma, or line feed.
 *
 * @since 4.17
 *
 * @param string $title Item title.
 *
 * @return string $trimmed Trimmed title.
*/
function lbs_trim_item_title( $title ) {
	$title = preg_split( '/\r\n|\r|\n/', $title );
	$trimmed = rtrim( $title[0], ' \/\:,.' );
	return $trimmed;
}

/**
 * Trims author name(s)
 *
 * Removes/replaces unwanted characters in author data retrieved from API.
 *
 * @since 5.1
 *
 * @param string $author Author data from API.
 *
 * @return string $trimmed Trimmed author name(s).
*/
function lbs_trim_author( $author ) {
	// Remove stuff in parentheses
	$author = preg_replace( '/(\([a-z]+\))/i', '', $author );
	
	// Trim string from ;
	$author = preg_split( '/\;|\[/', $author );
	$author = $author[0];
	
	// Split author list
	$author = explode( '/', $author );
	
	// Trim extra
	$author = array_map( 'trim', $author, [' ,.'] );
	
	// Put the author list back together with a sensible character
	$trimmed = implode( ' & ', $author );
	
	return $trimmed;
}

/**
 * Trims item identifiers
 *
 * Removes extraneous characters from item identifiers.
 *
 * @since 4.30
 *
 * @param string $item_id Item identifier.
 *
 * @return string $item_id Trimmed item identifier.
*/
function lbs_trim_item_id( $item_id, $type = 'isbn' ) {
	if( $type === 'isbn' ) {
		// Catch all leading non-ISBN characters
		$regex = '/[^0-9Xx]/';
	} else {
		// Catch all leading non-numeric characters
		$regex = '/[^0-9]/';
	}

	$item_id = preg_replace( $regex, '', $item_id );
	return $item_id;
}

/**
 * Unschedules Bookshelf post update cron job
 *
 * Removes a post's 'update_bookshelf' event from the cron schedule.
 *
 * @since 4.6
 *
 * @param string $post_id Bookshelf post ID.
*/
function lbs_unschedule_event( $post_id ) {
	$hook = 'update_bookshelf';
	$args = array( strval( $post_id ) );
	$timestamp = wp_next_scheduled( $hook, $args );

	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, $hook, $args );
	}
}

/**
 * Adds custom cron intervals
 *
 * Adds weekly and monthly cron intervals
 *
 * @since 4.6
 *
 * @param array $schedules The array of cron schedules keyed by the schedule name.
 *
 * @return array $schedules Cron schedules
*/
function lbs_add_cron_intervals( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 7 * DAY_IN_SECONDS,
		'display'  => esc_html__( 'Once Weekly' ),
	);

	$schedules['monthly'] = array(
		'interval' => 30 * DAY_IN_SECONDS,
		'display'  => esc_html__( 'Once Monthly' ),
	);

	return $schedules;
}

/**
 * Creates settings array
 *
 * Defines user-configurable settings.
 *
 * @since 2.0
 *
 * @return array $settings Bookshelf HTML elements.
*/
function lbs_settings_obj() {
	$settings['catalog'] = array(
		'title'       => __( 'Catalog Settings', 'library-bookshelves' ),
		'description' => '',
		'fields'      => array(
			array(
				'id'          => 'cat_Protocol',
				'label'       => __( 'Catalog Protocol', 'library-bookshelves' ),
				'description' => '',
				'type'        => 'select',
				'options'     => array(
					'https://' => 'HTTPS',
					'http://'  => 'HTTP',
				),
				'default'     => 'https://',
			),
			array(
				'id'          => 'cat_System',
				'label'       => __( 'Catalog System', 'library-bookshelves' ),
				'description' => __( 'Select your ILS.<br><b>If you are using Polaris 6.3 or higher you may need to choose Polaris 6.3+ for your item links to work.</b>', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'alexandria'       => 'Alexandria',
					'aspen'            => 'Aspen Discovery',
					'atriuum'          => 'Atriuum',
					'bibliocommons'    => 'BiblioCommons',
					'calibre'          => 'Calibre',
					'cloudlibrary'     => 'cloudLibrary',
					'cops'             => 'COPS: Calibre OPDS (and HTML) PHP Server',
					'dbtextworks'      => 'DB/Textworks',
					'ebsco_eds'        => 'EBSCOHost Discovery Service',
					'encore'           => 'Encore',
					'evergreen'        => 'Evergreen (ISBN/UPC)',
					'evergreen-record' => 'Evergreen (Record)',
					'primo'            => 'Ex Libris Primo',
					'hoopla'           => 'Hoopla',
					'insignia'         => 'Insignia',
					'koha'             => 'Koha',
					'opac_sbn'          => 'OPAC SBN',
					'openlibrary'      => 'OpenLibrary.org',
					'overdrive'        => 'Overdrive',
					'pika'             => 'Pika',
					'polaris'          => 'Polaris',
					'polaris63'        => 'Polaris 6.3+',
					'sirsi_ent'        => 'SirsiDynix Enterprise',
					'sirsi_horizon'    => 'SirsiDynix Horizon',
					'spydus'           => 'Spydus',
					'surpass'          => 'Surpass Cloud OPAC',
					'tlc_ls1'          => 'TLC Library System',
					'tlc'              => 'TLC LS2',
					'vega'             => 'Vega',
					'webpac'           => 'WebPAC PRO',
					'worldcat'         => 'WorldCat.org',
					'worldcatds'       => 'WorldCat Discovery Service',
					'other'            => 'Other',
				),
				'default'     => 'openlibrary',
			),
			array(
				'id'          => 'cat_URL',
				'label'       => __( 'Catalog URL Example', 'library-bookshelves' ),
				'description' => 'Enter the URL of an item in your catalog. Replace the item identifier with {ID}.',
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
				'size'        => '80',
			),
			array(
				'id'          => 'cat_DomainName',
				'label'       => __( 'Catalog Domain Name', 'library-bookshelves' ),
				'description' => '',
				'type'        => 'url',
				'default'     => 'openlibrary.org',
				'placeholder' => '',
				'callback'    => 'lbs_trim_url',
				'size'        => '40',
			),
			array(
				'id'          => 'cat_Profile',
				'label'       => __( 'Catalog Profile/Config', 'library-bookshelves' ),
				'description' => __( '<br>Enter your profile ID.<br>If your catalog is in a subdirectory of your catalog domain, enter the directory name here (e.g. library.system.domain/your_library).<br>If your catalog uses an OPAC config value or location ID to customize the interface, enter it here.<br>This is also where to enter your Calibre library ID.', 'library-bookshelves' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
			),
			array(
				'id'          => 'cat_LinkTarget',
				'label'       => __( 'Link Target', 'library-bookshelves' ),
				'description' => __( 'Open links in new tab/window.', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'cat_CDN',
				'label'       => __( 'Image Server', 'library-bookshelves' ),
				'description' => __( 'Select the image server used by your catalog.', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'amazon'           => 'Amazon',
					'aspen'            => 'Aspen Discovery',
					'contentcafe'      => 'Baker & Taylor',
					'calibre'          => 'Calibre',
					'cops'             => 'COPS: Calibre OPDS (and HTML) PHP Server',
					'chilifresh'       => 'ChiliFresh',
					'ebsco'            => 'EBSCO',
					'encore'           => 'Encore',
					'evergreen'        => 'Evergreen (ISBN/UPC)',
					'opac_sbn'          => 'OPAC SBN',
					'evergreen-record' => 'Evergreen (Record)',
					'openlibrary'      => 'OpenLibrary.org',
					'pika'             => 'Pika',
					'syndetics'        => 'Syndetics',
					'tlc'              => 'TLC',
					'other'            => 'Other',
				),
				'default'     => 'openlibrary',
			),
			array(
				'id'          => 'cat_CDN_URL',
				'label'       => __( 'Image Server URL Example', 'library-bookshelves' ),
				'description' => 'Enter the URL of an item image from your catalog. Replace the item identifier with {ID}.',
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
				'size'        => '80',
			),
			array(
				'id'          => 'cat_CDN_id',
				'label'       => __( 'Image Server ID/Username', 'library-bookshelves' ),
				'description' => __( '<br>Enter the credentials for your image server, if required.<br>Syndetics and TLC may require a customer ID.<br>Baker & Taylor may require username and password.', 'library-bookshelves' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
			),
			array(
				'id'          => 'cat_CDN_pass',
				'label'       => __( 'Image Server Password', 'library-bookshelves' ),
				'description' => '',
				'type'        => 'text',
				'default'     => '',
				'placeholder' => '',
			),
			array(
				'id'          => 'cat_overdrive',
				'label'       => __( 'Overdrive Catalog', 'library-bookshelves' ),
				'description' => __( '<br>Enter your Overdrive catalog URL If you want the option to make some Bookshelves link directly to Overdrive instead of your main catalog.', 'library-bookshelves' ),
				'type'        => 'url',
				'default'     => '',
				'callback'    => 'lbs_trim_url',
				'placeholder' => '',
			),
			array(
				'id'          => 'cat_cloudlibrary',
				'label'       => __( 'cloudLibrary Catalog', 'library-bookshelves' ),
				'description' => __( '<br>Enter your cloudLibrary catalog URL If you want the option to make some Bookshelves link directly to cloudLibrary instead of your main catalog.', 'library-bookshelves' ),
				'type'        => 'url',
				'default'     => '',
				'callback'    => 'lbs_trim_url',
				'placeholder' => '',
			),
			array(
				'id'          => 'cat_placeholder',
				'label'       => __( 'Placeholder Image', 'library-bookshelves' ),
				'description' => __( 'Select a custom placeholder image to use in place of the default image provided by your image server. Recommended image size is 236px x 360px.<br>If you created Bookshelves before choosing a placeholder image you will need to resave them for your custom placeholder to appear.', 'library-bookshelves' ),
				'type'        => 'media',
				'default'     => '',
			),
		),
	);
	$settings['slick'] = array(
		'title'       => __( 'Slider Settings', 'library-bookshelves' ),
		'description' => __( 'This plugin uses <a href="https://kenwheeler.github.io/slick/" target="_blank">slick carousel</a>.', 'library-bookshelves' ),
		'fields'      => array(
			array(
				'id'          => 'slick_accessibility',
				'label'       => __( 'Accessibility', 'library-bookshelves' ),
				'description' => __( 'Enables tabbing and arrow key navigation. Unless autoplay: true, sets browser focus to current slide (or first of current slide set, if multiple slidesToShow) after slide change. For full a11y compliance enable focusOnChange in addition to this.', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_adaptiveHeight',
				'label'       => __( 'Adaptive Height', 'library-bookshelves' ),
				'description' => __( 'Adapts slider height to the current slide', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_autoplay',
				'label'       => __( 'Autoplay', 'library-bookshelves' ),
				'description' => __( 'Enables auto play of slides', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_autoplaySpeed',
				'label'       => __( 'Autoplay Speed', 'library-bookshelves' ),
				'description' => __( 'Auto play change interval in milliseconds', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '3000',
				'placeholder' => '3000',
				'min'         => '1',
				'size'        => '5',
			),
			array(
				'id'          => 'slick_arrows',
				'label'       => __( 'Arrows', 'library-bookshelves' ),
				'description' => __( 'Enable Next/Prev Arrows', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_captions',
				'label'       => __( 'Captions', 'library-bookshelves' ),
				'description' => __( 'Enables captions below carousel images. Image alt text is used.', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_captions_overlay',
				'label'       => __( 'Captions Overlay', 'library-bookshelves' ),
				'description' => __( 'Shows image alt text over item images on hover.', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_centerMode',
				'label'       => __( 'Center Mode', 'library-bookshelves' ),
				'description' => __( 'Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_centerPadding',
				'label'       => __( 'Center Padding', 'library-bookshelves' ),
				'description' => __( 'Side padding when in center mode (px or %)', 'library-bookshelves' ),
				'type'        => 'text',
				'default'     => '50px',
				'placeholder' => '50px',
				'size'        => '4',
				'callback'    => 'sanitize_text_field',
			),
			array(
				'id'          => 'slick_dots',
				'label'       => __( 'Dots', 'library-bookshelves' ),
				'description' => __( 'Current slide indicator dots', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_draggable',
				'label'       => __( 'Draggable', 'library-bookshelves' ),
				'description' => __( 'Enables desktop dragging', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_fade',
				'label'       => __( 'Fade', 'library-bookshelves' ),
				'description' => __( 'Enables fade', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_focusOnChange',
				'label'       => __( 'Focus on Change', 'library-bookshelves' ),
				'description' => __( 'Puts focus on slide after change', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_focusOnSelect',
				'label'       => __( 'Focus on Select', 'library-bookshelves' ),
				'description' => __( 'Enable focus on selected element (click)', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_infinite',
				'label'       => __( 'Infinite', 'library-bookshelves' ),
				'description' => __( 'Infinite looping', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_lazyLoad',
				'label'       => __( 'Lazy Load', 'library-bookshelves' ),
				'description' => __( 'Accepts "ondemand" or "progressive" for lazy load technique. "ondemand" will load the image as soon as you slide to it, "progressive" loads one image after the other when the page loads.', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'ondemand'    => __( 'On demand', 'library-bookshelves' ),
					'progressive' => __( 'Progressive', 'library-bookshelves' ),
				),
				'default'     => 'ondemand',
			),
			array(
				'id'          => 'slick_mobileFirst',
				'label'       => __( 'Mobile First', 'library-bookshelves' ),
				'description' => __( 'Responsive settings use mobile first calculation', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_pauseOnFocus',
				'label'       => __( 'Pause on Focus', 'library-bookshelves' ),
				'description' => __( 'Pauses autoplay when slider is focused', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_pauseOnHover',
				'label'       => __( 'Pause on Hover', 'library-bookshelves' ),
				'description' => __( 'Pauses autoplay on hover', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_pauseOnDotsHover',
				'label'       => __( 'Pause on Dots Hover', 'library-bookshelves' ),
				'description' => __( 'Pauses autoplay when a dot is hovered', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_respondTo',
				'label'       => __( 'Respond To', 'library-bookshelves' ),
				'description' => __( 'Width that responsive object responds to. Can be "window", "slider" or "min" (the smaller of the two)', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'window' => __( 'Window', 'library-bookshelves' ),
					'slider' => __( 'Slider', 'library-bookshelves' ),
					'min'    => __( 'Min', 'library-bookshelves' ),
				),
				'default'     => 'slider',
			),
			array(
				'id'          => 'slick_responsive',
				'label'       => __( 'Responsive', 'library-bookshelves' ),
				'description' => __( 'Array of objects containing breakpoints and settings objects. Enables settings at given breakpoint. Set settings to "unslick" instead of an object to disable slick at a given breakpoint.', 'library-bookshelves' ),
				'type'        => 'textarea',
				'callback'    => 'sanitize_textarea_field',
				'default'     => '[ { breakpoint: 1025, settings: { slidesToShow: 6, slidesToScroll: 1 } }, { breakpoint: 769, settings: { slidesToShow: 4, slidesToScroll: 1 } }, { breakpoint: 481, settings: { slidesToShow: 2, slidesToScroll: 2 } } ]',
				'placeholder' => '[ { breakpoint: , settings: { } } ]',
			),
			array(
				'id'          => 'slick_rows',
				'label'       => __( 'Rows', 'library-bookshelves' ),
				'description' => __( 'Setting this to more than 1 initializes grid mode. Use slidesPerRow to set how many slides should be in each row.', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '1',
				'placeholder' => '1',
				'min'         => '1',
				'size'        => '4',
			),
			array(
				'id'          => 'slick_slidesPerRow',
				'label'       => __( 'Slides per Row', 'library-bookshelves' ),
				'description' => __( 'With grid mode initialized via the rows option, this sets how many slides are in each grid row.', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '1',
				'placeholder' => '1',
				'min'         => '1',
				'size'        => '4',
			),
			array(
				'id'          => 'slick_slidesToShow',
				'label'       => __( 'Slides to Show', 'library-bookshelves' ),
				'description' => __( '# of slides to show / # of pages of slides to show with grid mode enabled', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '6',
				'placeholder' => '6',
				'min'         => '1',
				'size'        => '4',
			),
			array(
				'id'          => 'slick_slidesToScroll',
				'label'       => __( 'Slides to Scroll', 'library-bookshelves' ),
				'description' => __( '# of slides to scroll (Has no effect if Swipe to Slide is enabled).', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '1',
				'placeholder' => '1',
				'min'         => '1',
				'size'        => '4',
			),
			array(
				'id'          => 'slick_speed',
				'label'       => __( 'Speed', 'library-bookshelves' ),
				'description' => __( 'Transition speed', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '300',
				'placeholder' => '300',
				'min'         => '1',
				'size'        => '5',
			),
			array(
				'id'          => 'slick_swipe',
				'label'       => __( 'Swipe', 'library-bookshelves' ),
				'description' => __( 'Enables touch swipe', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_swipeToSlide',
				'label'       => __( 'Swipe to Slide', 'library-bookshelves' ),
				'description' => __( 'Swipe to slide irrespective of slidesToScroll. (If Autoplay is on this setting will behave as if Slides to Scroll = 1).', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_touchMove',
				'label'       => __( 'Touch Move', 'library-bookshelves' ),
				'description' => __( 'Enables slide moving with touch', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_touchThreshold',
				'label'       => __( 'Touch Threshold', 'library-bookshelves' ),
				'description' => __( 'To advance slides, the user must swipe a length of (1/touchThreshold) * the width of the slider.', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '5',
				'placeholder' => '5',
				'min'         => '1',
				'size'        => '4',
			),
			array(
				'id'          => 'slick_useCSS',
				'label'       => __( 'Use CSS', 'library-bookshelves' ),
				'description' => __( 'Enable/Disable CSS Transitions', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_useTransform',
				'label'       => __( 'Use Transform', 'library-bookshelves' ),
				'description' => __( 'Enable/Disable CSS Transforms', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_variableWidth',
				'label'       => __( 'Variable Width', 'library-bookshelves' ),
				'description' => __( 'Automatic slide width calculation', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_vertical',
				'label'       => __( 'Vertical', 'library-bookshelves' ),
				'description' => __( 'Vertical slide mode', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_verticalSwiping',
				'label'       => __( 'Vertical Swiping', 'library-bookshelves' ),
				'description' => __( 'Vertical swipe mode', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_rtl',
				'label'       => __( 'RTL', 'library-bookshelves' ),
				'description' => __( 'Change the slider\'s direction to become right-to-left', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => '',
			),
			array(
				'id'          => 'slick_waitForAnimate',
				'label'       => __( 'Wait for Animate', 'library-bookshelves' ),
				'description' => __( 'Ignores requests to advance the slide while animating', 'library-bookshelves' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
			array(
				'id'          => 'slick_zIndex',
				'label'       => __( 'Z-Index', 'library-bookshelves' ),
				'description' => __( 'Set the zIndex values for slides, useful for IE9 and lower', 'library-bookshelves' ),
				'type'        => 'number',
				'default'     => '1000',
				'placeholder' => '1000',
				'size'        => '5',
			),
		),
	);

	$settings['css'] = array(
		'title'       => __( 'Customize CSS', 'library-bookshelves' ),
		'description' => __( 'Edit CSS Styles for plugin elements.', 'library-bookshelves' ),
		'fields'      => array(
			array(
				'id'          => 'css_bookshelf_margin_top',
				'label'       => __( 'Bookshelf Top Margin', 'library-bookshelves' ),
				'description' => __( 'Bookshelf container top margin (px, %, auto)', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_margin_side',
				'label'       => __( 'Bookshelf Side Margin', 'library-bookshelves' ),
				'description' => __( 'Bookshelf container left and right margins (px, %, auto)', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => 'auto',
				'size'        => '2',
				'default'     => 'auto',
			),
			array(
				'id'          => 'css_bookshelf_margin_bottom',
				'label'       => __( 'Bookshelf Bottom Margin', 'library-bookshelves' ),
				'description' => __( 'Bookshelf container bottom margin (px, %, auto)', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '30px',
				'size'        => '2',
				'default'     => '30px',
			),
			array(
				'id'          => 'css_bookshelf_image_border_radius',
				'label'       => __( 'Image Border Radius', 'library-bookshelves' ),
				'description' => __( 'Round the corners of cover art images (px).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_image_box_shadow_offset_h',
				'label'       => __( 'Image Shadow Horizontal Offset', 'library-bookshelves' ),
				'description' => __( 'Horizontal offset of shadow beneath cover art images (px).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_image_box_shadow_offset_v',
				'label'       => __( 'Image Shadow Vertical Offset', 'library-bookshelves' ),
				'description' => __( 'Vertical offset of shadow beneath cover art images (px).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_image_box_shadow_blur',
				'label'       => __( 'Image Shadow Blur', 'library-bookshelves' ),
				'description' => __( 'Blur radius of shadow under cover art images (px).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_image_box_shadow_spread',
				'label'       => __( 'Image Shadow Spread', 'library-bookshelves' ),
				'description' => __( 'Spread radius of shadow under cover art images (px).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0px',
				'size'        => '2',
				'default'     => '0px',
			),
			array(
				'id'          => 'css_bookshelf_image_box_shadow_color',
				'label'       => __( 'Image Shadow Color', 'library-bookshelves' ),
				'description' => __( 'Color of the shadow beneath cover art images.', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#000000',
			),
			array(
				'id'          => 'css_bookshelf_image_alignment',
				'label'       => __( 'Image Vertical Alignment.', 'library-bookshelves' ),
				'description' => __( 'Change the vertical alignment of cover art images.', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'top'    => __( 'top', 'library-bookshelves' ),
					'middle' => __( 'middle', 'library-bookshelves' ),
					'bottom' => __( 'bottom', 'library-bookshelves' ),
				),
				'default'     => 'bottom',
			),
			array(
				'id'          => 'css_caption_font_color',
				'label'       => __( 'Caption Font Color', 'library-bookshelves' ),
				'description' => __( 'Change the font color for item captions.', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#000000',
			),
			array(
				'id'          => 'css_caption_font_size',
				'label'       => __( 'Caption Font Size', 'library-bookshelves' ),
				'description' => __( 'Change the font size for item captions (px, em, %).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '100%',
				'size'        => '2',
				'default'     => '100%',
			),
			array(
				'id'          => 'css_caption_max_lines',
				'label'       => __( 'Caption Line Limit', 'library-bookshelves' ),
				'description' => __( 'Limit the number of lines the caption text can wrap.', 'library-bookshelves' ),
				'type'        => 'select',
				'options'     => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
				),
				'default'     => '2',
			),
			array(
				'id'          => 'css_caption_overlay_font_color',
				'label'       => __( 'Caption Overlay Font Color', 'library-bookshelves' ),
				'description' => __( 'Change the font color for item caption overlays.', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#FFFFFF',
			),
			array(
				'id'          => 'css_caption_overlay_background_color',
				'label'       => __( 'Caption Overlay Background Color', 'library-bookshelves' ),
				'description' => __( 'Change the background color for item caption overlays.', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#000000',
			),
			array(
				'id'          => 'css_caption_overlay_background_opacity',
				'label'       => __( 'Caption Overlay Background Opacity', 'library-bookshelves' ),
				'description' => __( 'Change the background opacity for item caption overlays (range: 0.0 - 1.0).', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '0.8',
				'size'        => '2',
				'default'     => '0.8',
			),
			array(
				'id'          => 'css_arrow_color',
				'label'       => __( 'Navigation Arrow Color', 'library-bookshelves' ),
				'description' => __( 'Slick navigation arrow color', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#000000',
			),
			array(
				'id'          => 'css_arrow_size',
				'label'       => __( 'Navigation Arrow Size', 'library-bookshelves' ),
				'description' => __( 'Slick navigation arrow size', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '20px',
				'size'        => '2',
				'default'     => '20px',
			),
			array(
				'id'          => 'css_arrow_distance',
				'label'       => __( 'Navigation Arrow Distance', 'library-bookshelves' ),
				'description' => __( 'Slick navigation arrow distance from left and right edges of the Bookshelf', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '-25px',
				'size'        => '2',
				'default'     => '-25px',
			),
			array(
				'id'          => 'css_dots_bottom_offset',
				'label'       => __( 'Navigation Dots Bottom Offset', 'library-bookshelves' ),
				'description' => __( 'Slick navigation dots offset from the bottom of the Bookshelf container (px, %, auto)', 'library-bookshelves' ),
				'type'        => 'text',
				'placeholder' => '-25px',
				'size'        => '2',
				'default'     => '-25px',
			),
			array(
				'id'          => 'css_dot_color',
				'label'       => __( 'Navigation Dots Color', 'library-bookshelves' ),
				'description' => __( 'Slick navigation dots color', 'library-bookshelves' ),
				'type'        => 'color',
				'default'     => '#000000',
			),
		),
	);
	return $settings;
}

/**
 * Generate HTML for settings fields
 *
 * Creates input fields based on setting type. Fills fields with previously saved values.
 *
 * @since 2.0
 *
 * @param array $data Array containing settings field data
 * @param bool $post Indicates that this setting field is going to be placed in the post editor or in the plugin settings page.
*/
function lbs_display_setting_field( $data = array(), $post = false ) {
	// Get field info from plugin settings page or from post editor.
	isset( $data['field'] ) ? $field = $data['field'] : $field = $data;

	// Check for and add prefix to option name.
	isset( $data['prefix'] ) ? $option_name = $data['prefix'] : $option_name = 'lbs_';

	// Get saved field data.
	$option_name .= $field['id'];

	// Get saved settings data, if any, else write defaults.
	if ( $post ) {
		// Get saved settings from post meta.
		$settings = get_post_meta( $post, 'settings', true );

		// If no settings exist in post meta, use the global setting.
		$settings ? $data = $settings[ $option_name ] : $data = get_option( $option_name, $field['default'] );

		// Layout post settings like the plugin settings page
		echo "<tr><th scope='row'>".esc_html( $field['label'] ) . "</th><td>";
	} else {
		// Get saved option
		$data = get_option( $option_name );
		if ( false === $data && isset( $field['default'] ) ) {
			$data = $field['default'];
		}
	}

	// Clear html
	$html = '';

	// Assemble setting fields
	switch ( $field['type'] ) {
		case 'text':
			$size = '';
			if ( isset( $field['size'] ) ) {
				$size = ' size="' . esc_attr( $field['size'] ) . '"';
			}
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $size . ' />' . "\n";
			break;

		case 'url':
			$size = '';
			if ( isset( $field['size'] ) ) {
				$size = ' size="' . esc_attr( $field['size'] ) . '"';
			}
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $size . ' />' . "\n";
			break;

		case 'number':
		case 'hidden':
			$min = '';
			if ( isset( $field['min'] ) ) {
				$min = ' min="' . esc_attr( $field['min'] ) . '"';
			}

			$max = '';
			if ( isset( $field['max'] ) ) {
				$max = ' max="' . esc_attr( $field['max'] ) . '"';
			}
			$size = '';
			if ( isset( $field['size'] ) ) {
				$size = ' style="width: ' . esc_attr( $field['size'] ) . 'em;"';
			}
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $min . '' . $max . '' . $size . '/>' . "\n";
			break;

		case 'textarea':
			$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="25" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>' . "\n";
			break;

		case 'checkbox':
			$checked = '';
			if ( $data && 'on' === $data ) {
				$checked = 'checked="checked"';
			}
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;

		case 'select':
			$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
			foreach ( $field['options'] as $k => $v ) {
				$selected = false;
				if ( $k == $data ) {
					$selected = true;
				}
				$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
			}
			$html .= '</select> ';
			break;

		case 'color':
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $data ) . '" onchange="javascript: jQuery(&quot;#' . esc_attr( $field['id'] ) . '2&quot;).val(this.value);" />' . "\n";
			$html .= '<input id="' . esc_attr( $field['id'] ) . '2" name="' . esc_attr( $option_name ) . '" type="text" maxlength="7" size="6" value="' . esc_attr( $data ) . '" onchange="javascript: jQuery(&quot;#' . esc_attr( $field['id'] ) . '&quot;).val(this.value);" />';
			break;

		case 'media':
			$html .= '<div class="lbs_placeholder_uploader"><button class="lbs_placeholder button">Select image</button>&nbsp;<button class="lbs_placeholder_reset button">Use default</button><br><br>';
			$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="hidden" name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $data ) . '" >';
			$html .= '<img src="' . esc_attr( $data ) . '" style="width: 100px;' . ( ( ! $data )? ' display: none;' : '' ) . '" /></div>';
			break;
	}

	// Assemble setting field descriptions
	switch ( $field['type'] ) {
		default:
			if ( ! $post ) {
				$html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
			}

			$html .= '<span class="description">' . $field['description'] . '</span>' . "\n";

			if ( ! $post ) {
				$html .= '</label>' . "\n";
			}
			break;
	}

	echo $html;

	// Close post settings table rows
	if ( $post ) {
		echo "</td></tr>";
	}
}


/**
 * Case-corrects text from retrieved from APIs
 *
 * Applies a series of rules to properly capitalize titles and names.
 *
 * @since 5.4
 *
 * @param string $str Title & author text from API.
 * 
 * @return string $str Case-corrected text.
*/
function lbs_capitalize( $str ) {
	// Add space after periods
	$str = preg_replace( '/\./', '. ', $str);
	// Trim excess whitespace
	$str = preg_replace( '/\s+/', ' ', $str);
	// Set all lower case then capitalize each word
	$str = ucwords( strtolower( $str ), " -\t\r\n\f\v'" );

	// Properly cased parts
	$all_lowercase = 'and|by|d\'|da|dal|dalla|das|de|der|del|dello|della|di|dos|em|for|het|in|of|l\'|na|nas|nos|the|to|und|van|von|y';
	$prefixes = 'Mc';
	$suffixes = '\'d|\'ll|\'m|\'re|\'s|\'t';

	// Odd bits which don't follow the rules
	$oddments = array();
	$oddments['search'] = ['/phd/i','/psyd/i','/md\b/i'];
	$oddments['replace'] = ['PhD','PsyD','MD'];

	// Decapitalize short words, except first word
	$str = preg_replace_callback(
		"/(?<=\W)(" . $all_lowercase . ")\W/i",
		function( $matches ) {
			return strtolower( $matches[0] );
		},
		$str
	);

	// capitalize letter after name prefixes
	$str = preg_replace_callback(
		"/\b(" . $prefixes . ")(\w)/i",
		function( $matches ) {
			return $matches[1] . strtoupper( $matches[2] );
		},
		$str
	);

	// Capitalize common Roman numerals
	$str = preg_replace_callback(
		"/\b(X{0,3})(IX|IV|V?I{0,3})/i",
		function( $matches ) {
			return strtoupper( $matches[0] );
		},
		$str
	);

	// decapitalize certain word suffixes e.g. 's
	$str = preg_replace_callback(
		"/(" . $suffixes . ")\b/i",
		function( $matches ) {
			return strtolower( $matches[0] );
		},
		$str
	);

	// Replace oddments
	$str = preg_replace( $oddments['search'], $oddments['replace'], $str );

	return $str;
}

/**
 * Trims catalog domain name
 *
 * Sanitizes and removes http(s) protocol from user input catalog domain name.
 * 
 * @since 1.0
 * @param string $data Text from Catalog Domain Name input field.
 *
 * @return array $data Sanitized catalog URL.
*/
function lbs_trim_url( $data ) {
	$data = sanitize_text_field( $data );
	$regex = '/^(http:\/\/|https:\/\/)|\/$/';
	$replacement = '';
	$data = preg_replace( $regex, $replacement, $data );
	return $data;
}

/**
 * Support PHP 5.3 which does not include array_column()
 *
 * Include PHP array_column function if it's not available.
 * 
 * @since 1.4
 * @param array $array Array containing the column to be returned.
 * @param string $column_name The column of values to return.
 *
 * @return array $array Array of values representing a single column from the input array.
*/
if ( ! function_exists( 'array_column' ) ) {
	function array_column( $array, $column_name ) {
		return array_map( function( $element ) use( $column_name ){ return $element[ $column_name ]; }, $array );
	}
}
