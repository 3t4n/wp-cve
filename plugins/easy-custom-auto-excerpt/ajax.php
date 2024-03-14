<?php
/**
 * Ajax preview button
 *
 * @package ECAE
 */

/* for logged in user */
add_action( 'wp_ajax_ecae_preview_button', 'ecae_preview_button' );

function ecae_preview_button() {
	check_ajax_referer( 'tonjoo_options-options', 'security' );

	$button_font = isset( $_POST['button_font'] ) ? sanitize_text_field( wp_unslash( $_POST['button_font'] ) ) : ''; // input var okay.

	/**
	 * Font
	 */
	echo "<style type='text/css'>";

	switch ( $button_font ) {
		case 'Open Sans':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext);'; // Open Sans.
			break;
		case 'Lobster':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Lobster);'; // Lobster.
			break;
		case 'Lobster Two':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Lobster+Two:400,400italic,700,700italic);'; // Lobster Two.
			break;
		case 'Ubuntu':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic);'; // Ubuntu.
			break;
		case 'Ubuntu Mono':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic);'; // Ubuntu Mono.
			break;
		case 'Titillium Web':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Titillium+Web:400,300,700,300italic,400italic,700italic);'; // Titillium Web.
			break;
		case 'Grand Hotel':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Grand+Hotel);'; // Grand Hotel.
			break;
		case 'Pacifico':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Pacifico);'; // Pacifico.
			break;
		case 'Crafty Girls':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Crafty+Girls);'; // Crafty Girls.
			break;
		case 'Bevan':
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Bevan);'; // Bevan.
			break;
		default:
			echo '@import url(' . esc_url( ECAE_HTTP_PROTO ) . 'fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext);'; // Open Sans.
	}

	echo ".ecae-button { font-family: '" . esc_html( $button_font ) . "', Helvetica, Arial, sans-serif; }";
	echo '</style>';

	/**
	 * Button style
	 */
	$button_skin = isset( $_POST['button_skin'] ) ? sanitize_text_field( wp_unslash( $_POST['button_skin'] ) ) : ''; // input var okay.

	$exp  = explode( '-PREMIUM', $button_skin );
	$skin = $exp[0];

	if ( count( $exp ) > 1 && 'true' === $exp[1] ) {
		echo '<link rel="stylesheet" href="' . esc_url( plugins_url( ECAE_PREMIUM_DIR_NAME . "/buttons/{$skin}.css" ) ) . '" type="text/css" media="all">';
	} else {
		echo '<link rel="stylesheet" href="' . esc_url( plugins_url( ECAE_DIR_NAME . "/buttons/{$skin}.css" ) ) . '" type="text/css" media="all">';
	}

	/**
	 * Custom css
	 */
	$button_font_size = isset( $_POST['button_font_size'] ) ? absint( $_POST['button_font_size'] ) : 14; // input var okay.

	$custom_css = isset( $_POST['custom_css'] ) ? wp_strip_all_tags( wp_unslash( $_POST['custom_css'] ) ) : ''; // input var okay.

	echo '<style type="text/css">';
	echo esc_textarea( $custom_css );

	if ( function_exists( 'is_ecae_premium_exist' ) && ! empty( $button_font_size ) ) {
		echo '.ecae-button { font-size: ' . esc_html( $button_font_size ) . 'px !important; }';
	}

	$readmore_inline = isset( $_POST['readmore_inline'] ) ? sanitize_text_field( wp_unslash( $_POST['readmore_inline'] ) ) : ''; // input var okay.
	if ( 'yes' === $readmore_inline ) {
		echo '.ecae-button { display: inline-block !important; }';
	} else {
		echo '.ecae-button { display: block !important; }';
	}

	$read_more_align = isset( $_POST['read_more_align'] ) ? sanitize_text_field( wp_unslash( $_POST['read_more_align'] ) ) : ''; // input var okay.
	if ( in_array( $read_more_align, array( 'left', 'center', 'right' ), true ) ) {
		echo '.ecae-button { text-align: ' . esc_html( $read_more_align ) . ' !important; }';
	}

	echo '</style>';

	/**
	 * Print button
	 */
	$custom_html = isset( $_POST['custom_html'] ) ? wp_kses_post( wp_unslash( $_POST['custom_html'] ) ) : ''; // input var okay.
	$custom_html = str_replace( '{link}', 'javascript:;', $custom_html );
	if ( ! empty( $custom_html ) ) {
		echo wp_kses_post( $custom_html );
	} else {
		$read_more_text_before = isset( $_POST['read_more_text_before'] ) ? sanitize_text_field( wp_unslash( $_POST['read_more_text_before'] ) ) : ''; // input var okay.
		$read_more             = isset( $_POST['read_more'] ) ? sanitize_text_field( wp_unslash( $_POST['read_more'] ) ) : ''; // input var okay.
		$read_more_text_before = trim( $read_more_text_before );
		if ( ! empty( $read_more_text_before ) ) {
			$read_more_text_before = $read_more_text_before . '&nbsp;&nbsp;';
		}
		?>
		<span class="ecae-button <?php echo esc_attr( $skin ); ?>" style="text-align: <?php echo esc_attr( $read_more_align ); ?>">
			<?php echo esc_html( $read_more_text_before ); ?>
			<a class="ecae-link" href="javascript:;">
				<span><?php echo esc_html( $read_more ); ?></span>
			</a>
		</span>
		<?php
	}

	die();
}
