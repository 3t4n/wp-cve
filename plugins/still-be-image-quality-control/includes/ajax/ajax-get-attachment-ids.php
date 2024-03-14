<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Get Attachment IDs
add_action( 'wp_ajax_sb_iqc_get_attachment_ids', function(){

	// Nonce Check
	if( ! wp_verify_nonce( $_GET['_nonce'], 'sb-iqc-get-attachments' ) ) {
		exit( json_encode( array(
			'ok'      => false,
			'message' => __( 'The page has expired. Please reload the page.', 'still-be-image-quality-control' ),
		) ) );
	}

	// Target Image Conditions
	$target_json = filter_input( INPUT_GET, 'target' );
	try{
		$target = json_decode( $target_json, true );
	} catch( Exception $e ) {
		$target = null;
	}
	update_option( '_sb-iqc-recomp-target-condition', $target, false );

	// Get Attachment IDs
	$get_ids        = stillbe_iqc_get_attachment_ids( $target );
	$attachment_ids = isset( $get_ids['ids']  ) ? $get_ids['ids']  : $get_ids;
	$args           = isset( $get_ids['args'] ) ? $get_ids['args'] : null;   // for Debug

	// Save to wp_options
	update_option( '_sb-iqc-current-id', 0,               false );
	update_option( '_sb-iqc-image-ids',  $attachment_ids, false );

	exit( json_encode(
		array(
			'ok'      => true,
			'message' => __( 'Return IDs of all images!!', 'still-be-image-quality-control' ),
			'ids'     => $attachment_ids,
			'target'  => $target,
			'args'    => $args,   // for Debug
		)
	) );

} );





// END

?>