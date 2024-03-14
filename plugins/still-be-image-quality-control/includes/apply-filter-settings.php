<?php


/**
 * 設定値を各フィルターに適応する
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




add_action( 'plugins_loaded', function() {


	// Load Settings
	$settings = get_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME, null );


	// No Setting is Loaded
	if( empty( $settings ) ) {

		// Optimize srcset Attribute; true
		add_filter( 'max_srcset_image_width', function( $max_width, $size_array ) {
			return STILLBE_IQ_OPTIMIZE_SRCSET ? $size_array[0] : $max_width;
		}, 1, 2 );

		return;

	}


	// Image Qualitie Levels
	add_filter( 'stillbe_image_quality_default_list', function( $default ) use( $settings ) {

		if( empty( $settings['quality'] ) ) {
			return $default;
		}

		return $settings['quality'];

	}, 1 );


	// WebP Quality of Original Image
	add_filter( 'stillbe_image_quality_original_webp_settings', function( $original_webp ) use( $settings ) {

		if( empty( $settings['original-webp'] ) ) {
			$_defaults = _stillbe_get_quality_level_array();
			return array(
				array(
					'lossy'    => $_defaults['original_webp'],
					'lossless' => 9,
				),
			);

		}

		return $settings['original-webp'];

	}, 1 );


	// Add Image Size
	$add_sizes = empty( $settings['image-size'] ) ? array() : $settings['image-size'];
	foreach( $add_sizes as $add_size ) {
		add_image_size( $add_size['name'], $add_size['width'], $add_size['height'], $add_size['crop'] );
	}


	// Change Big Image Threshold
	add_filter( 'big_image_size_threshold', function( $threshold ) use( $settings ) {

		if( ! isset( $settings['big-threshold'] ) ) {
			return $threshold;
		}

		$_threshold = absint( $settings['big-threshold'] );
		if( 0 === $_threshold ) {
			return false;
		}

		return $_threshold;

	}, 1 );


	// Toogle Options
	$toggle = isset( $settings['toggle'] ) ? $settings['toggle'] : null;

	// No Toogle Settings
	if( empty( $toggle ) ) {
		return;
	}


	// Guarantee a Secure Filename; true
	add_filter( 'stillbe_image_quality_control_convert_safename', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['safe-name'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['safe-name'] );

	}, 1 );


	// Stripe EXIF Data; true
	add_filter( 'stillbe_image_quality_control_enable_strip_exif', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['strip-exif'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['strip-exif'] );

	}, 1 );


	// Autoset Alt from Exif; false
	add_filter( 'stillbe_image_quality_autoset_alt_uploaded_jpeg_exif', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['autoset-alt'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['autoset-alt'] );

	}, 1 );


	// Optimize srcset Attribute; true
	add_filter( 'max_srcset_image_width', function( $max_width, $size_array ) use( $toggle ) {

		if( isset( $toggle['optimize-srcset'] ) ) {
			return $toggle['optimize-srcset'] ? $size_array[0] : $max_width;
		}

		return STILLBE_IQ_OPTIMIZE_SRCSET ? $size_array[0] : $max_width;

	}, 1, 2 );


	// Add a Suffix to Indicate Quality Level; false
	add_filter( 'stillbe_image_quality_control_suffix_q_value', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['level-suffix'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['level-suffix'] );

	}, 1 );


	// Enable Interlace; true
	add_filter( 'stillbe_image_quality_control_enable_interlace', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-interlace'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-interlace'] );

	}, 1 );

	// Enable Interlace for JPEG; true
	add_filter( 'stillbe_image_quality_control_enable_interlace_jpeg', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-progressive-jpeg'] ) ) {
			if( isset( $toggle['enable-interlace'] ) ) {
				return ! empty( $toggle['enable-interlace'] );
			}
			return $is_enable;
		}

		return ! empty( $toggle['enable-interlace-jpeg'] );

	}, 1 );

	// Enable Interlace for PNG; false
	add_filter( 'stillbe_image_quality_control_enable_interlace_png', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-interlace-png'] ) ) {
			if( isset( $toggle['enable-interlace'] ) ) {
				return ! empty( $toggle['enable-interlace'] );
			}
			return $is_enable;
		}

		return ! empty( $toggle['enable-interlace-png'] );

	}, 1 );


	// Force Adding the Query String for Image Cache Clear; true
	add_filter( 'stillbe_image_quality_control_force_adding_cache_clear_query', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['force-cache-clear'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['force-cache-clear'] );

	}, 1 );


	// Enable PNG8; true
	add_filter( 'stillbe_image_quality_control_enable_png_index_color', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-png8'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-png8'] );

	}, 1 );


	// Force PNG8; false
	add_filter( 'stillbe_image_quality_control_enable_png_index_color_force', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-png8-force'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-png8-force'] );

	}, 1 );


	// Enable WebP; true
	add_filter( 'stillbe_image_quality_control_enable_webp', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-webp'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-webp'] );

	}, 1 );


	// Enable cwep Liberary; true
	add_filter( 'stillbe_image_quality_control_enable_cwebp_lib', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-cwebp'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-cwebp'] );

	}, 1 );


	// Enable Lossless Compression; true
	add_filter( 'stillbe_image_quality_control_enable_webp_lossless_for_png_gif', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-webp-lossless'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-webp-lossless'] );

	}, 1 );


	// Enable Near Lossless Compression; false
	add_filter( 'stillbe_image_quality_control_enable_webp_near_lossless', function( $is_enable ) use( $toggle ) {

		if( ! isset( $toggle['enable-webp-near-lossless'] ) ) {
			return $is_enable;
		}

		return ! empty( $toggle['enable-webp-near-lossless'] );

	}, 1 );


	// Enable decimal values timeout in WP-Cron
	add_action( 'requests-requests.before_request', function( &$url, &$headers, &$data, &$type, &$options )  use( $toggle ) {

		if( empty( $toggle['enable-decimal-timeout-wpcron'] ) ) {
			return;
		}

		// Check if URL Param contains 'doing_wp_cron'
		$parse_url = parse_url( $url );
		parse_str( empty( $parse_url['query'] ) ? '' : $parse_url['query'], $parse_query );
		if( empty( $parse_query['doing_wp_cron'] ) ) {
			// If not doing_wp_cron, do not change
			return;
		}

		// @since 1.6.0  for WP 6.2
		if( interface_exists( '\WpOrg\Requests\Capability' ) && class_exists( '\WpOrg\Requests\Transport\Fsockopen' ) ) {

			// Is need SSL?
			$need_ssl     = stripos( $url, 'https://' ) === 0;
			$capabilities = [ \WpOrg\Requests\Capability::SSL => $need_ssl ];

			// Check exist 'Requests_Transport_fsockopen' class
			$fsockopen_class = '\WpOrg\Requests\Transport\Fsockopen';

			if( ! $fsockopen_class::test( $capabilities ) ) {
				return;
			}

		} elseif( class_exists( 'Requests_Transport_fsockopen' ) ) {

			// for Older WP Version
			$fsockopen_class = 'Requests_Transport_fsockopen';

		} else {

			return;

		}

		// Check the Version of cURL
		$curl_info = function_exists( 'curl_version' ) ? curl_version() : null;
		if( empty( $curl_info['version'] ) || version_compare( $curl_info['version'], '7.32.0', '>=' ) ) {
			// Note; In version 7.32.0 or later, decimal values can be used for timeout
			return;
		}

		// Use 'Requests_Transport_fsockopen' Class which can handle microtime
		$options['transport'] = $fsockopen_class;

	}, 1, 5 );


} );
