<?php


/**
 * メタデータ生成時に alt を exif から自動設定する
 * 
 * @since 1.4.0  元画像から EXIF を削除する処理を追加
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// メタデータ生成時に alt を exif から自動設定する
add_filter( 'wp_generate_attachment_metadata', function( $metadata, $attachment_id, $context ) {

	// The Flag to Set EXIF data if ALT attribute is empty
	// Default is "false"
	$is_autoset_alt = apply_filters( 'stillbe_image_quality_autoset_alt_uploaded_jpeg_exif', STILLBE_IQ_AUTOSET_ALT_FROM_EXIF, $metadata );
	if( ! $is_autoset_alt || empty( $metadata['image_meta'] ) ) {
		return $metadata;
	}

	// 
	$post = get_post( $attachment_id );
	if( empty( $post ) || empty( $post->post_mime_type ) || 'image/jpeg' !== $post->post_mime_type ) {
		return $metadata;
	}

	// Get the Alt Value
	$current_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

	// Set Alt Value from Exif
	if( empty( $current_alt ) ) {

		// Set an Alt Value from JPEG Exif
		$exif_data = array();

		if( ! empty( $metadata['image_meta']['camera'] ) ) {
			$exif_data[] = $metadata['image_meta']['camera'];
		}

		if( ! empty( $metadata['image_meta']['focal_length'] ) ) {
			$exif_data[] = $metadata['image_meta']['focal_length']. 'mm';
		}

		if( ! empty( $metadata['image_meta']['aperture'] ) ) {
			$exif_data[] = 'f/'. $metadata['image_meta']['aperture'];
		}

		if( ! empty( $metadata['image_meta']['shutter_speed'] ) ) {
			if( $metadata['image_meta']['shutter_speed'] < 1 ) {
				$exif_data[] = '1/'. round( 1 / $metadata['image_meta']['shutter_speed'] ). 'sec';
			} else{
				$exif_data[] = $metadata['image_meta']['shutter_speed']. 'sec';
			}
		}

		if( ! empty( $metadata['image_meta']['iso'] ) ) {
			$exif_data[] = 'ISO'. $metadata['image_meta']['iso'];
		}

		$exif_data = implode( ', ', $exif_data );

		// Add the Credit
		if( ! empty( $metadata['image_meta']['credit'] ) ) {
			$credit_prefix = apply_filters( 'stillbe_image_quality_autoset_alt_credit_prefix', ' | Photo by ' );
			$exif_data    .= $credit_prefix. $metadata['image_meta']['credit'];
		}

		$exif_data = apply_filters( 'stillbe_image_quality_autoset_alt_pre_save', $exif_data, $metadata, $attachment_id, $context );

		// Resister the Alt Value
		if( ! empty( $exif_data ) ) {
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', $exif_data );
		}

	}

	// Close
	return $metadata;

}, 10, 3 );




// 元画像から EXIF 情報を削除する
add_filter( 'wp_generate_attachment_metadata', function( $metadata, $attachment_id, $context ) {

	if( empty( $metadata['file'] ) ) {
		return $metadata;
	}

	// Upload Dir
	$uploads = wp_get_upload_dir();

	// Default is "true"
	$is_strip_exif = apply_filters( 'stillbe_image_quality_control_enable_strip_exif', STILLBE_IQ_ENABLE_STRIP_EXIF );
	$filename      = path_join( $uploads['basedir'], $metadata['file'] );
	if( ! $is_strip_exif || ! file_exists( $filename ) ) {
		return $metadata;
	}

	// Only JPEG
	$post = get_post( $attachment_id );
	if( empty( $post ) || empty( $post->post_mime_type ) || 'image/jpeg' !== $post->post_mime_type ) {
		return $metadata;
	}

	// Strip EXIF Data
	$is_striped_exif = stillbe_iqc_strip_exif( $filename );

	if( ! $is_striped_exif ) {
		return $metadata;
	}

	// Update Filesize
	$metadata['filesize'] = wp_filesize( $filename );

	return $metadata;

}, 20, 3 );






// END of the File



