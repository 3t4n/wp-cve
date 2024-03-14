
<?php

	ob_start();
	require CANVAS_DIR . 'views/login-registration/parts/loading-icon.php';
	$spinner = ob_get_clean();

	$notices_string = '';

	if ( count( $canvas_notices['errors'] ) > 0 ) {
		$notices_string .= '<div class="canvas-errors"><p>';

		foreach ( $canvas_notices['errors'] as $error ) {
			$notices_string .= $error . '<br>';
		}

		$notices_string .= '</p></div>';
	}

	if ( '' !== $canvas_notices['message'] ) {
		$notices_string .= '<div class="canvas-message"><p>';
		$notices_string .= $canvas_notices['message'];
		$notices_string .= '</p></div>';
	}

	$registration_template_string = Canvas::get_option( 'generated-existing-registration-html-template' );
	$registration_template_string = stripslashes( $registration_template_string );
	$registration_template_string = str_replace( '{{ canvas_logo }}', CanvasForm::get_logo(), $registration_template_string );
	$registration_template_string = str_replace( '{{ canvas_notices }}', $notices_string, $registration_template_string );
	$registration_template_string = str_replace( '{{ canvas_registration_nonce }}', wp_nonce_field( 'canvas-registration', 'canvas_nonce', true, false ), $registration_template_string );
	$registration_template_string = str_replace( '{{ canvas_spinner }}', $spinner, $registration_template_string );
	echo $registration_template_string;
?>