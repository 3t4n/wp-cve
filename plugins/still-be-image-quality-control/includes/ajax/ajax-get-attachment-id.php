<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Get Attachment IDs
add_action( 'wp_ajax_sb_iqc_get_attachment_ids', function(){

	// Nonce Check
	if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-iqc-get-attachments' ) ) {
		exit( json_encode( array(
			'ok'      => false,
			'message' => __( 'The page has expired. Please reload the page.', 'still-be-image-quality-control' ),
		) ) );
	}

	// Get Attachment IDs
	$attachment_ids = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'post_mime_type' => 'image/*',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );

	// Save to wp_options
	update_option( '_sb-iqc-image-ids', $attachment_ids, false );
	update_option( '_sb-iqc-current-id', 0, false );

	exit( json_encode(
		array(
			'ok'      => true,
			'message' => __( 'Return IDs of all images!!', 'still-be-image-quality-control' ),
			'ids'     => $attachment_ids,
		)
	) );

} );





// END

?>