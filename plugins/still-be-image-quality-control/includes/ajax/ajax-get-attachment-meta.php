<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Get the Meta Data
add_action( 'wp_ajax_sb_iqc_get_attachment_meta', function(){

	// Nonce Check
	if( ! wp_verify_nonce( $_GET['_nonce'], 'sb-iqc-get-attachment-meta' ) ) {
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

	// Return Meta
	header( 'Content-Type: application/json' );
	exit( json_encode(
		array(
			'ok'      => true,
			'id'      => $attachment_id,
			'meta'    => $meta,
			'message' => esc_html__( 'Success !!', 'still-be-image-quality-control' ),
		)
	) );

} );





// END

?>