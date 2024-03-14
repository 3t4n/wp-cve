<?php
/**
 * A module for [mathcaptcha]
 */

// shortcode handler
add_action( 'init', 'wpcf7_add_shortcode_mathcaptcha', 5 );

function wpcf7_add_shortcode_mathcaptcha() {
	if ( function_exists( 'wpcf7_add_form_tag' ) )
		wpcf7_add_form_tag( 'mathcaptcha', 'wpcf7_mathcaptcha_shortcode_handler', true );
}

function wpcf7_mathcaptcha_shortcode_handler( $tag ) {
	if ( ! is_user_logged_in() || ( is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'] ) ) {
		$tag = new WPCF7_FormTag( $tag );

		if ( empty( $tag->name ) )
			return '';

		$validation_error = wpcf7_get_validation_error( $tag->name );
		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error )
			$class .= ' wpcf7-not-valid';

		$atts = array();
		$atts['size'] = 2;
		$atts['maxlength'] = 2;
		$atts['class'] = $tag->get_class_option( $class );
		$atts['id'] = $tag->get_option( 'id', 'id', true );
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );
		$atts['aria-required'] = 'true';
		$atts['type'] = 'text';
		$atts['name'] = $tag->name;
		$atts['value'] = '';
		$atts = wpcf7_format_atts( $atts );

		$mc_form = Math_Captcha()->core->generate_captcha_phrase( 'cf7' );
		$mc_form[$mc_form['input']] = '<input %2$s />';

		$math_captcha_title = apply_filters( 'math_captcha_title', Math_Captcha()->options['general']['title'] );

		return sprintf( ( ( empty( $math_captcha_title ) ) ? '' : $math_captcha_title ) . ' <span class="wpcf7-form-control-wrap %1$s">' . $mc_form[1] . $mc_form[2] . $mc_form[3] . '%3$s</span><input type="hidden" value="' . ( Math_Captcha()->core->session_number - 1 ) . '" name="' . $tag->name . '-sn" />', $tag->name, $atts, $validation_error );
	}
}

// validation
add_filter( 'wpcf7_validate_mathcaptcha', 'wpcf7_mathcaptcha_validation_filter', 10, 2 );

function wpcf7_mathcaptcha_validation_filter( $result, $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	$name = $tag->name;

	if ( isset( $_POST[$name] ) && $_POST[$name] !== '' && ! is_admin() ) {
		$val = (int) $_POST[$name];

		if ( isset( $_POST[$name . '-sn'] ) && $_POST[$name . '-sn'] !== '' ) {
			$val_sn = (int) $_POST[$name . '-sn'];

			if ( array_key_exists( $val_sn, Math_Captcha()->cookie_session->session_ids['multi'] ) )
				$session_id = Math_Captcha()->cookie_session->session_ids['multi'][$val_sn];
			else
				$session_id = '';
		} else
			$session_id = '';

		if ( $session_id !== '' && get_transient( 'cf7_' . $session_id ) !== false ) {
			if ( strcmp( get_transient( 'cf7_' . $session_id ), sha1( AUTH_KEY . $val . $session_id, false ) ) !== 0 ) {
				if ( version_compare( WPCF7_VERSION, '4.1.0', '>=' ) )
					$result->invalidate( $tag, wpcf7_get_message( 'wrong_mathcaptcha' ) );
				else {
					$result['valid'] = false;
					$result['reason'][$name] = wpcf7_get_message( 'wrong_mathcaptcha' );
				}
			}
		} else {
			if ( version_compare( WPCF7_VERSION, '4.1.0', '>=' ) )
				$result->invalidate( $tag, wpcf7_get_message( 'time_mathcaptcha' ) );
			else {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'time_mathcaptcha' );
			}
		}
	} else {
		if ( version_compare( WPCF7_VERSION, '4.1.0', '>=' ) )
			$result->invalidate( $tag, wpcf7_get_message( 'fill_mathcaptcha' ) );
		else {
			$result['valid'] = false;
			$result['reason'][$name] = wpcf7_get_message( 'fill_mathcaptcha' );
		}
	}

	return $result;
}

// messages
add_filter( 'wpcf7_messages', 'wpcf7_mathcaptcha_messages' );

function wpcf7_mathcaptcha_messages( $messages ) {
	return array_merge(
		$messages,
		array(
			'wrong_mathcaptcha'	 => array(
				'description'	 => __( 'Invalid captcha value.', 'math-captcha' ),
				'default'		 => wp_strip_all_tags( Math_Captcha()->core->error_messages['wrong'], true )
			),
			'fill_mathcaptcha'	 => array(
				'description'	 => __( 'Please enter captcha value.', 'math-captcha' ),
				'default'		 => wp_strip_all_tags( Math_Captcha()->core->error_messages['fill'], true )
			),
			'time_mathcaptcha'	 => array(
				'description'	 => __( 'Captcha time expired.', 'math-captcha' ),
				'default'		 => wp_strip_all_tags( Math_Captcha()->core->error_messages['time'], true )
			)
		)
	);
}

// warning message
add_action( 'wpcf7_admin_notices', 'wpcf7_mathcaptcha_display_warning_message' );

function wpcf7_mathcaptcha_display_warning_message() {
	if ( ! empty( $_GET['post'] ) )
		$id = (int) $_GET['post'];
	else
		return;

	if ( ! ( $contact_form = wpcf7_contact_form( $id ) ) )
		return;

	if ( version_compare( WPCF7_VERSION, '4.6.0', '>=' ) )
		$has_tags = (bool) $contact_form->scan_form_tags( array( 'type' => array( 'mathcaptcha' ) ) );
	else
		$has_tags = (bool) $contact_form->form_scan_shortcode( array( 'type' => array( 'mathcaptcha' ) ) );

	if ( ! $has_tags )
		return;
}

// tag generator
add_action( 'admin_init', 'wpcf7_add_tag_generator_mathcaptcha', 45 );

function wpcf7_add_tag_generator_mathcaptcha() {
	if ( function_exists( 'wpcf7_add_tag_generator' ) )
		wpcf7_add_tag_generator( 'mathcaptcha', __( 'Math Captcha', 'math-captcha' ), 'wpcf7-mathcaptcha', 'wpcf7_tg_pane_mathcaptcha' );
}

function wpcf7_tg_pane_mathcaptcha( $contact_form ) {
	echo '
	<div class="control-box">
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="tag-generator-panel-mathcaptcha-name">' . esc_html__( 'Name', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-mathcaptcha-name" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-generator-panel-mathcaptcha-id">' . esc_html__( 'Id attribute', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-mathcaptcha-id" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-generator-panel-mathcaptcha-class">' . esc_html__( 'Class attribute', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-mathcaptcha-class" />
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>
	<div class="insert-box">
		<input type="text" name="mathcaptcha" class="tag code" readonly="readonly" onfocus="this.select();">
		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="' . esc_attr__( 'Insert Tag', 'contact-form-7' ) . '">
		</div>
		<br class="clear">
	</div>';
}