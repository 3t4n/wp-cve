<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Regenerate Resized Images with Ajax
add_action( 'wp_ajax_sb_iqc_regenerate_images', function(){

	// Nonce Check
	if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-iqc-regenerate-images' ) ) {
		exit( json_encode( array(
			'ok'      => false,
			'id'      => null,
			'message' => __( 'The page has expired. Please reload the page.', 'still-be-image-quality-control' ),
		) ) );
	}

	// Getting the attachment ID
	$attachment_id = filter_input( INPUT_POST, 'attachment_id' );
	$attachment_id = absint( $attachment_id ?: 0 );

	// Regenerate the Image
	$result = stillbe_iqc_regenerate_images( $attachment_id );

	// Failed to Get a Attachment ID
	if( null === $result || ! is_array( $result ) ) {
		exit( json_encode(
			array(
				'ok'      => false,
				'id'      => null,
				'message' => __( 'Attachment ID is not accepted....', 'still-be-image-quality-control' ),
			)
		) );
	}

	$attachment_ids  = (array) get_option( '_sb-iqc-image-ids', array() );
	$generated_id    = isset( $result['id'] ) ? (int) $result['id'] : 0;
	$generated_index = (int) array_search( $generated_id, $attachment_ids );
	$progress_ratio  = ( $generated_index + 1 ) / max( 1, count( $attachment_ids ) );
	$next_index      = $generated_index + 1;
	$next_id         = isset( $attachment_ids[ $next_index ] ) ? $attachment_ids[ $next_index ] : 0;

	$result['genereted_id']    = $generated_id;
	$result['genereted_index'] = $generated_index;
	$result['progress_ratio']  = $progress_ratio;
	$result['next_id']         = $next_id;
	$result['next_index']      = isset( $attachment_ids[ $next_index ] ) ? $next_index : null;

	if( ! empty( $result['completed'] ) ) {
		// WP-Cron 
		$settings   = get_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME, array() );
		$auto_regen = isset( $settings['auto-regen-wpcron'] ) ? $settings['auto-regen-wpcron']    : array();
		$_number    = isset( $auto_regen['number']          ) ? absint( $auto_regen['number']   ) : 0;
		$_interval  = isset( $auto_regen['interval']        ) ? absint( $auto_regen['interval'] ) : 60;
		$settings['auto-regen-wpcron'] = array(
			'number'   => 0,
			'interval' => $_interval,
		);
		update_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME, $settings );
	}

	// Add the Nonce
	$result['nonce'] = wp_create_nonce( 'sb-iqc-regenerate-images' );

	// Return the Result
	exit( json_encode( $result ) );

} );





// END

?>