
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

	$login_template_string = Canvas::get_option( 'generated-existing-login-html-template' );
	$login_template_string = stripslashes( $login_template_string );
	$login_template_string = str_replace( '{{ canvas_logo }}', CanvasForm::get_logo(), $login_template_string );
	$login_template_string = str_replace( '{{ canvas_notices }}', $notices_string, $login_template_string );
	$login_template_string = str_replace( '{{ canvas_login_nonce }}', wp_nonce_field( 'canvas-login', 'canvas_nonce', true, false ), $login_template_string );
	$login_template_string = str_replace( '{{ canvas_spinner }}', $spinner, $login_template_string );
	echo $login_template_string;
?>