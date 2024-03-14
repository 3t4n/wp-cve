<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Reset Settings
add_action( 'wp_ajax_sb_iqc_reset_settings', function() {

	// Nonce Check
	if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-iqc-reset-settings' ) ) {
		exit( json_encode( array(
			'ok'      => false,
			'message' => __( 'The page has expired. Please reload the page.', 'still-be-image-quality-control' ),
		) ) );
	}

	// Delete Setting
	$result = delete_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME );

	// Delete Regenerate Attachment IDs
	delete_option( '_sb-iqc-image-ids' );
	delete_option( '_sb-iqc-current-id' );
	delete_option( '_sb-iqc-recomp-target-condition' );

	// Get User Data
	exit( json_encode( array(
		'ok'      => $result,
		'message' => $result ? __( 'The settings have been reset!!', 'still-be-image-quality-control' ) :
		                       __( 'Reset failed or it is not set.', 'still-be-image-quality-control' ) ,
		'deleted' => $result ? ( 'Option Name: '. StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME ) : 'null',
	) ) );

} );





// END

?>