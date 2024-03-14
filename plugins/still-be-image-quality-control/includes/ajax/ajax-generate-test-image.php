<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Generate Test Images with Ajax (GET Method only)
add_action( 'wp_ajax_sb_iqc_generate_test_image', function() {

	// Nonce Check
	if( ! wp_verify_nonce( $_GET['_nonce'], 'sb-iqc-generate-test-img' ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode( array(
			'ok'      => false,
			'id'      => null,
			'message' => esc_html__( 'The page has expired. Please reload the page.', 'still-be-image-quality-control' ),
		) ) );
	}

	// Getting the attachment ID
	$attachment_id = filter_input( INPUT_GET, 'attachment_id' );
	$attachment_id = absint( $attachment_id ?: 0 );
	if( empty( $attachment_id ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => null,
				'message' => esc_html__( 'Attachment ID is not accepted....', 'still-be-image-quality-control' ),
			)
		) );
	}

	// Get the Metadata
	$meta = wp_get_attachment_metadata( $attachment_id );
	if( empty( $meta ) || empty( $meta['file'] ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'message' => sprintf( esc_html__( 'Attachment ID = %d is not found....', 'still-be-image-quality-control' ), $attachment_id ),
			)
		) );
	}

	// File Path
	$upload_dir = wp_upload_dir();
	$filename   = $upload_dir['basedir']. '/'. $meta['file'];
	if( ! file_exists( $filename ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'meta'    => $meta,
				'file'    => $filename,
				'message' => sprintf( esc_html__( 'Attachment ID = %d is not exists....', 'still-be-image-quality-control' ), $attachment_id ),
			)
		) );
	}

	// Get the Return Size
	$size  = filter_input( INPUT_GET, 'size' );
	$sizes = wp_get_registered_image_subsizes();
	if( 'Original' !== $size && ( empty( $size ) || empty( $sizes[ $size ] ) ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'size'    => array(
					'request' => $size,
					'sizes'   => $sizes,
				),
				'message' => sprintf( esc_html__( 'Size name = %s is not found....', 'still-be-image-quality-control' ), $size ),
			)
		) );
	}

	// Check Size Dimension
	$img_width  = $meta['width'];
	$img_height = $meta['height'];
	$max_width  = empty( $sizes[ $size ] ) ? 0 : $sizes[ $size ]['width'];
	$max_height = empty( $sizes[ $size ] ) ? 0 : $sizes[ $size ]['height'];
	if( 'Original' !== $size && $max_width >= $img_width && $max_height >= $img_height ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'size'    => array(
					'request' => $size,
					'sizes'   => $sizes,
				),
				'message' => sprintf( esc_html__( 'Size; %s  cannot be generated because it is larger than the original image....', 'still-be-image-quality-control' ), $size ),
			)
		) );
	}

	// Get the Return Mime-Type (Optional)
	$mime = filter_input( INPUT_GET, 'mime' ) ?: 'none';
	$allow_mimes = array( 'jpeg', 'png', 'webp' );
	if( ! in_array( $mime, $allow_mimes, true ) ) {
		$mime = wp_get_image_mime( $filename );
		if( ! $mime || false === strpos( $mime, 'image/' ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode(
				array(
					'ok'      => false,
					'id'      => $attachment_id,
					'mime'    => $mime,
					'message' => esc_html__( 'Could not get the supported Mime-Type....', 'still-be-image-quality-control' ),
				)
			) );
		}
		$mime = str_replace( 'image/', '', $mime );
	}
	$mime = strtolower( $mime );

	// Get the Return Quality (Optional)
	$quality = filter_input( INPUT_GET, 'quality' ) ?: 0;
	$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $quality, $mime );

	// Get the Toggle Filter Values (Optional)
	$filters = filter_input( INPUT_GET, 'filters', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	// Load Image Editor
	require_once( ABSPATH. 'wp-admin/includes/image.php' );
	$editor = wp_get_image_editor( $filename );
	if( is_wp_error( $editor ) ) {
		// This image cannot be edited.
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'message' => esc_html__( 'The WP Image Editor could not be initialized....', 'still-be-image-quality-control' ),
			)
		) );
	}

	// Does the Image Editor Support the Mime-Type
	if( ! $editor::supports_mime_type( 'image/'. $mime ) ) {
		header( 'Content-Type: application/json' );
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => $attachment_id,
				'message' => sprintf( esc_html__( 'Image Editor does not support "%s"', 'still-be-image-quality-control' ), esc_html( 'image/'. $mime ) ),
			)
		) );
	}

	// Set Overwrite Toggle Filters
	$applied_filters = array();
	if( ! empty( $filters ) && is_array( $filters ) ) {
		foreach( $filters as $hook => $boolern ) {
			$_hook  = trim( $hook );
			$toggle = ! empty( $boolern );
			add_filter( $_hook, ( $toggle ? '__return_true' : '__return_false' ), 99999 );
			$applied_filters[ $_hook ] = $toggle;
		}
	}

	// Convert using cwebp
	$is_conv_cwebp = false;
	if( apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
	      && 'webp' === $mime && stillbe_iqc_is_enabled_cwebp() ) {
		$is_conv_cwebp = true;
		// @since 0.9.0 Added
		//   Enable Conversion in "cwebp" if the Extension Plugin is Installed
		$webp_quality = empty( $quality ) ? $editor->get_quality_from_size( $size, 'image/webp' )                 : $quality;
		$quality      = empty( $quality ) ? $editor->get_quality_from_size( $size, $editor->get_original_mime() ) : $quality;
		// Oprions
		$options = array(
			'quality' => array( $webp_quality, $quality ),
			'mime'    => $editor->get_original_mime(),
			'size'    => $editor->get_size(),
		);
		// Original Image Size
		$size_data = 'Original' === $size ? $editor->get_size() : $sizes[ $size ];
	}

	// Start Time
	$start_time = microtime( true );

	// Convert using Embeded Library
	if( ! $is_conv_cwebp ) {

		// Resize Image
		if( 'Original' !== $size ) {
			$editor->resize( $sizes[ $size ]['width'], $sizes[ $size ]['height'], $sizes[ $size ]['crop'] );
		} elseif( 'webp' === $mime ) {
			$editor->conv2truecolor();
		}

		// Set Quality
		if( 'Original' === $size && empty( $quality ) ) {
			switch( $mime ) {
				case 'jpeg':
					$quality = 100;
				break;
				case 'png':
					$quality = 9;
				break;
				case 'webp':
					$quality = $editor->get_quality_from_size( 'original', 'image/webp' );
				break;
			}
		} elseif( empty( $quality ) ) {
			$quality = $editor->get_quality_from_size( $size, 'image/'. $mime );
		}
		$editor->set_quality( $quality );

		// WP 5.8 対応で hook を追加する (@since 0.7.5)
		$editor->_set_mk_size( $size );
		$editor->_set_mk_quality( $quality, $mime );
		add_filter( 'wp_editor_set_quality', array( $editor, '_set_quality_hook' ), 1, 2 );

	}

	// Output Stream
	ob_start();
	$result = $is_conv_cwebp ?
	            stillbe_iqc_extends_conv_cwebp( $filename, '-', $size_data, $options ) :
	            $editor->stream( "image/{$mime}" );
	$content_length = (int) ob_get_length();
	$stream         = ob_get_clean();
	if( false !== $result && ! is_wp_error( $result ) && 0 < $content_length ) {
		$processing = ( intval( ( microtime( true ) - $start_time ) * 1000 * 1 ) / 1 ). 'ms';
		$memory     = ( intval( memory_get_peak_usage() / ( 1024 * 1024 ) * 1 ) / 1 ). 'MiB';
		$cpu        = @ sys_getloadavg();
		$colors     = $editor->get_colors();
		$original   = $editor->get_original_color_num();
		// Headers
		header( 'Content-Length: '.  $content_length );
		header( 'X-Quality-Level: '. ( $is_conv_cwebp ? $result['quality'] : $editor->get_quality() ) );
		header( 'X-Convert-Time: '.  $processing );
		header( 'X-Memory-Peak: '.   $memory );
		header( 'X-Average-CPU: '.   ( is_array( $cpu ) ? (string) $cpu[0] : 'null' ) );
		header( 'X-Using-Colors: '.  (int) $colors );
		header( 'X-Original-Num: '.  (int) $original );
		header( 'X-IQC-Filters: '.   json_encode( $applied_filters ) );
		if( $is_conv_cwebp ) {
			header( 'Content-Type: image/webp' );
			header( 'X-Encode-Mode: '.       $result['method'] );
			header( 'X-Compression-Level: '. ( empty( $result['q'] ) ? '0' : $result['q'] ) );
		}
		// Output
		exit( $stream );
	}

	// Failure
	header( 'Content-Type: application/json' );
	exit( json_encode(
		array(
			'ok'      => false,
			'id'      => $attachment_id,
			'stream'  => array(
				'result' => is_wp_error( $result ) ? 'WP_Error' : false,
				'size'   => $content_length,
			),
			'quality' => array(
				'request'     => $quality,
				'get_quality' => $editor->get_quality(),
			),
			'filters' => $applied_filters,
			'message' => __( 'Failed....', 'still-be-image-quality-control' ),
		)
	) );

} );





// END

?>