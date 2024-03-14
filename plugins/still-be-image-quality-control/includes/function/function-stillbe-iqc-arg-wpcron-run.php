<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Regenerate Images Function using WP-Cron
function stillbe_iqc_arg_wpcron_run( $time ) {

	// Get the Settings
	$settings = get_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME, array() );

	// Setting of Auto Regenerate using WP-Cron
	$auto_regen = isset( $settings['auto-regen-wpcron'] ) ? $settings['auto-regen-wpcron']    : array();
	$_number    = isset( $auto_regen['number']          ) ? absint( $auto_regen['number']   ) : 0;
	$_interval  = isset( $auto_regen['interval']        ) ? absint( $auto_regen['interval'] ) : 60;

	// Regenerate the Image
	$is_completed = false;
	for( $i = 0; $i < $_number; $i++ ) {
		$result = stillbe_iqc_regenerate_images();
		if( ! $result || empty( $result['ok'] ) ) {
			$attachment_id = empty( $result['id'] ) ? 0 : absint( $result['id'] );
			error_log( sprintf(
				'Regenerate an image (Attachment ID = %d) is failed; time = %s, log = %s',
				$attachment_id,
				wp_date( 'Y-m-d H:i:s', $time ),
				json_encode( $result )
			) );
		}
		if( $result && ! empty( $result['completed'] ) ) {
			$is_completed = true;
			break;
		}
	}

	// Set a Next Schedule
	if( ! $is_completed && $_number ) {
		wp_schedule_single_event(
			absint( @time() ) + $_interval,
			'stillbe_image_quality_control_arg_wpcron_run',
			array( time() )
		);
	}

	if( $is_completed ) {
		error_log( 'Regenerate all images are finished!!' );
		// WP-Cron 
		$settings['auto-regen-wpcron'] = $auto_regen;
		$settings['auto-regen-wpcron'] = array(
			'number'   => 0,
			'interval' => $_interval,
		);
		update_option( StillBE_Image_Quality_Ctrl_Setting::SETTING_NAME, $settings );
	}

}


// Add Filter
add_action( 'stillbe_image_quality_control_arg_wpcron_run', 'stillbe_iqc_arg_wpcron_run' );





// END

?>