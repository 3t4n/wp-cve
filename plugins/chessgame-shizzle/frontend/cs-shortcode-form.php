<?php
/*
 * Template Function for upload form.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_form( $atts ) {
	echo get_chessgame_shizzle_form( $atts );
}


/*
 * Frontend function to show the upload form for a chessgame.
 *
 * @since 1.0.0
 */
function get_chessgame_shizzle_form( $atts ) {

	/* Get the messages and formdata from the form handling in cs-shortcode-form-post.php. */
	$cs_messages     = chessgame_shizzle_get_messages();
	$cs_errors       = chessgame_shizzle_get_errors();
	$cs_error_fields = chessgame_shizzle_get_error_fields();
	$cs_formdata     = chessgame_shizzle_get_formdata();

	$html5  = current_theme_supports( 'html5' );
	$autofocus = ' autofocus="autofocus"';
	$output = '';


	// Set data up for prefilling an already submitted form that had errors.
	$cs_title            = '';
	$cs_content          = '';
	$cs_pgn              = '';
	$cs_white_player     = '';
	$cs_black_player     = '';
	$cs_result           = '';
	$cs_elo_white_player = '';
	$cs_elo_black_player = '';
	$cs_datetime         = '';
	$cs_location         = '';
	$cs_tournament       = '';
	$cs_round            = '';
	$cs_submitter        = '';
	$cs_code             = '';
	$cs_puzzle           = '';


	// Only show old data when there are errors.
	if ( $cs_errors ) {
		if ( is_array($cs_formdata) && ! empty($cs_formdata) ) {
			if (isset($cs_formdata['cs_title'])) {
				$cs_title = esc_html($cs_formdata['cs_title']);
			}
			if (isset($cs_formdata['cs_content'])) {
				$cs_content = esc_textarea($cs_formdata['cs_content']);
			}
			if (isset($cs_formdata['cs_pgn'])) {
				$cs_pgn = esc_textarea($cs_formdata['cs_pgn']);
			}
			if (isset($cs_formdata['cs_white_player'])) {
				$cs_white_player = esc_html($cs_formdata['cs_white_player']);
			}
			if (isset($cs_formdata['cs_black_player'])) {
				$cs_black_player = esc_html($cs_formdata['cs_black_player']);
			}
			if (isset($cs_formdata['cs_result'])) {
				$cs_result = esc_html($cs_formdata['cs_result']);
			}
			if (isset($cs_formdata['cs_elo_white_player'])) {
				$cs_elo_white_player = (int) $cs_formdata['cs_elo_white_player'];
			}
			if (isset($cs_formdata['cs_elo_black_player'])) {
				$cs_elo_black_player = (int) $cs_formdata['cs_elo_black_player'];
			}
			if (isset($cs_formdata['cs_datetime'])) {
				$cs_datetime = esc_html($cs_formdata['cs_datetime']);
			}
			if (isset($cs_formdata['cs_location'])) {
				$cs_location = esc_html($cs_formdata['cs_location']);
			}
			if (isset($cs_formdata['cs_tournament'])) {
				$cs_tournament = esc_html($cs_formdata['cs_tournament']);
			}
			if (isset($cs_formdata['cs_round'])) {
				$cs_round = esc_html($cs_formdata['cs_round']);
			}
			if (isset($cs_formdata['cs_submitter'])) {
				$cs_submitter = esc_html($cs_formdata['cs_submitter']);
			}
			if (isset($cs_formdata['cs_code'])) {
				$cs_code = esc_html($cs_formdata['cs_code']);
			}
			if ( isset($cs_formdata['cs_puzzle']) && $cs_formdata['cs_puzzle'] === 'on' ) {
				$cs_puzzle = ' checked="checked"';
			}
		}
	}


	/*
	 * Handle Messaging to the user.
	 */
	$messageclass = '';
	if ( $cs_errors ) {
		$messageclass = 'error';
	}
	$output .= '
		<div id="cs_messages_top_container">';
	if ( isset($cs_messages) && $cs_messages !== '') {
		$output .= '
			<div id="cs_messages" class="' . $messageclass . '">';
		$output .= $cs_messages;
		$output .= '
			</div>';
	}
	$output .= '
		</div>';


	/*
	 * Build up Form.
	 */
	$output .= '
		<form id="cs_new_chessgame" action="#" method="POST">
			<input type="hidden" name="cs_function" id="cs_function" value="new_chessgame" />';

	/* Nonce, always add it. */
	$field_name = chessgame_shizzle_get_field_name( 'nonce' );
	$nonce = wp_create_nonce( 'chessgame_shizzle_form' );
	$output .= '
			<input type="hidden" id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $field_name ) . '" value="' . esc_attr( $nonce ) . '" />';


	/* Title, if empty it gets generated from the names. */
	$output .= '
			<div class="cs_title">
				<div class="label">
					<label for="cs_title" class="text-info">' . esc_html__('Title', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_title ) . '" type="text" name="cs_title" id="cs_title" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Content */
	$output .= '
			<div class="cs_content">
				<div class="label">
					<label for="cs_content" class="text-info">' . esc_html__('Description', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<textarea class="wp-exclude-emoji" name="cs_content" id="cs_content"';
	if (in_array('content', $cs_error_fields)) {
		$output .= ' error';
	}
	if ( in_array('content', $cs_error_fields) && isset($autofocus) ) {
		$output .= $autofocus;
		$autofocus = false; // disable it for the next error.
	}
	$output .= '>' . esc_textarea( $cs_content ) . '</textarea>
				</div>
			</div>
			<div class="clear"></div>';

	/* PGN data */
	$output .= '
			<div class="cs_pgn">
				<div class="label">
					<label for="cs_pgn" class="text-info">' . esc_html__('PGN data (required)', 'chessgame-shizzle') . '</label>
				</div>
				' . chessgame_shizzle_get_form_help_text() . '
				<div class="input">
					<textarea class="wp-exclude-emoji cs_pgn" name="cs_pgn" id="cs_pgn"
						placeholder="1. e4 e6 2. d4 d5 3. exd5 exd5 4. Nf3 Nf6 5. Bd3 Bd6 ..." required';
	if (in_array('cs_pgn', $cs_error_fields)) {
		$output .= ' error';
	}
	if ( in_array('cs_pgn', $cs_error_fields) && isset($autofocus) ) {
		$output .= $autofocus;
		$autofocus = false; // disable it for the next error.
	}
	$output .= '>' . esc_textarea( $cs_pgn ) . '</textarea>
				</div>
			</div>
			<div class="clear"></div>';

	/* White player */
	$output .= '
			<div class="cs_white_player">
				<div class="label">
					<label for="cs_white_player" class="text-info">' . esc_html__('White player (required)', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_white_player ) . '" type="text" name="cs_white_player" id="cs_white_player" required';
	if (in_array('cs_white_player', $cs_error_fields)) {
		$output .= ' error';
	}
	if ( in_array('cs_white_player', $cs_error_fields) && isset($autofocus) ) {
		$output .= $autofocus;
		$autofocus = false; // disable it for the next error.
	}
	$output .= ' />
				</div>
			</div>
			<div class="clear"></div>';

	/* Black player */
	$output .= '
			<div class="cs_black_player">
				<div class="label">
					<label for="cs_black_player" class="text-info">' . esc_html__('Black player (required)', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_black_player ) . '" type="text" name="cs_black_player" id="cs_black_player" required';
	if (in_array('cs_black_player', $cs_error_fields)) {
		$output .= ' error';
	}
	if ( in_array('cs_black_player', $cs_error_fields) && isset($autofocus) ) {
		$output .= $autofocus;
		$autofocus = false; // disable it for the next error.
	}
	$output .= ' />
				</div>
			</div>
			<div class="clear"></div>';

	/* Result */
	$output .= '
			<div class="cs_result">
				<div class="label">
					<label for="cs_result" class="text-info">' . esc_html__('Result', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_result ) . '" type="text" name="cs_result" id="cs_result" />
					<select class="cs_result_ajax" name="cs_result_ajax" data-placeholder="' . esc_attr__('Choose a result...', 'chessgame-shizzle' ) . '">
						<option value="">' . esc_html__('Choose a result...', 'chessgame-shizzle') . '</option>
						<option value="1-0">1-0</option>
						<option value="0-1">0-1</option>
						<option value="½-½">½-½</option>
						<option value="1-0R">1-0R</option>
						<option value="0-1R">0-1R</option>
						<option value="*">* ' . esc_html__('(undecided yet)', 'chessgame-shizzle') . '</option>
					</select>
				</div>
			</div>
			<div class="clear"></div>';

	/* Elo white player */
	$output .= '
			<div class="cs_elo_white_player">
				<div class="label">
					<label for="cs_elo_white_player" class="text-info">' . esc_html__('Elo white player', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . (int) $cs_elo_white_player . '" type="text" name="cs_elo_white_player" id="cs_elo_white_player" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Elo black player */
	$output .= '
			<div class="cs_elo_black_player">
				<div class="label">
					<label for="cs_elo_black_player" class="text-info">' . esc_html__('Elo black player', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . (int) $cs_elo_black_player . '" type="text" name="cs_elo_black_player" id="cs_elo_black_player" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Datetime */
	$output .= '
			<div class="cs_datetime">
				<div class="label">
					<label for="cs_datetime" class="text-info">' . esc_html__('Date', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_datetime ) . '" type="text" name="cs_datetime" id="cs_datetime"
						placeholder="' . date( 'Y.m.d', time() ) . '" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Location */
	$output .= '
			<div class="cs_location">
				<div class="label">
					<label for="cs_location" class="text-info">' . esc_html__('Location', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_location ) . '" type="text" name="cs_location" id="cs_location" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Tournament */
	$output .= '
			<div class="cs_tournament">
				<div class="label">
					<label for="cs_tournament" class="text-info">' . esc_html__('Tournament', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_tournament ) . '" type="text" name="cs_tournament" id="cs_tournament" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Round */
	$output .= '
			<div class="cs_round">
				<div class="label">
					<label for="cs_round" class="text-info">' . esc_html__('Round', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_round ) . '" type="text" name="cs_round" id="cs_round" />
				</div>
			</div>
			<div class="clear"></div>';

	/* Submitter */
	if ( ! is_user_logged_in() ) {
		$output .= '
			<div class="cs_submitter">
				<div class="label">
					<label for="cs_result" class="text-info">' . esc_html__('Submitter', 'chessgame-shizzle') . '</label>
				</div>
				<div class="input">
					<input class="wp-exclude-emoji" value="' . esc_attr( $cs_submitter ) . '" type="text" name="cs_submitter" id="cs_submitter" />
				</div>
			</div>
			<div class="clear"></div>';
	}


	$dropdown = chessgame_shizzle_get_dropdown_openingcodes( $cs_code, 'cs_chessgame_code' );
	$output .= '
			<div class="cs_code">
				<div class="label">
					<label for="cs_chessgame_code" class="text-info">' . esc_html__('Opening code', 'chessgame-shizzle' ) . '</label>
				</div>
				<div class="input">
					' . $dropdown . '
				</div>
			</div>
			<div class="clear"></div>';

	/* Puzzle checkbox */
	$output .= '
			<div class="cs_puzzle">
				<div class="label"><label for="cs_puzzle" class="text-info">' . esc_html__('Puzzle', 'chessgame-shizzle' ) . '</label></div>
				<div class="input"><input type="checkbox" name="cs_puzzle" class="cs_puzzle" ' . $cs_puzzle . ' /></div>
			</div>
			<div class="clearBoth">&nbsp;</div>';

	/* Honeypot */
	if ( get_option( 'chessgame_shizzle-honeypot', 'true') === 'true' ) {
		$field_name = chessgame_shizzle_get_field_name( 'honeypot' );
		$field_name2 = chessgame_shizzle_get_field_name( 'honeypot2' );
		$honeypot_value = (int) get_option( 'chessgame_shizzle-honeypot_value', 15 );
		$output .= '
			<div class="' . esc_attr( $field_name ) . '" style="display:none;overflow:hidden;">
				<div class="label">
					<label for="' . esc_attr( $field_name ) . '" class="text-primary">' . /* translators: label for spamfilter in form. */ esc_html__('Do not touch this', 'chessgame-shizzle') . ':</label>
					<label for="' . esc_attr( $field_name2 ) . '" class="text-primary">' . /* translators: label for spamfilter in form. */ esc_html__('Do not touch this', 'chessgame-shizzle') . ':</label>
				</div>
				<div class="input">
					<input value="' . (int) $honeypot_value . '" type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" placeholder="" style="transform: translateY(10000px);" />
					<input value="" type="text" name="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_name2 ) . '" placeholder="" style="transform: translateY(10000px);" />
				</div>
			</div>
			<div class="clear"></div>';
	}

	/* Form Timeout */
	if ( get_option( 'chessgame_shizzle-timeout', 'true') === 'true' ) {
		$field_name = chessgame_shizzle_get_field_name( 'timeout' );
		$field_name2 = chessgame_shizzle_get_field_name( 'timeout2' );
		$random = rand( 100, 100000 );
		$output .= '
			<div class="' . esc_attr( $field_name ) . '" style="display:none;overflow:hidden;">
				<div class="label">
					<label for="' . esc_attr( $field_name ) . '" class="text-primary">' . /* translators: label for spamfilter in form. */ esc_html__('Do not touch this', 'chessgame-shizzle') . ':</label>
					<label for="' . esc_attr( $field_name2 ) . '" class="text-primary">' . /* translators: label for spamfilter in form. */ esc_html__('Do not touch this', 'chessgame-shizzle') . ':</label>
				</div>
				<div class="input">
					<input value="' . (int) $random . '" type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" placeholder="" style="transform: translateY(10000px);" />
					<input value="' . (int) $random . '" type="text" name="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_name2 ) . '" placeholder="" style="transform: translateY(10000px);" />
				</div>
			</div>
			<div class="clear"></div>';
	}

	$output .= '
			<noscript><div class="no-js">' . esc_html__( 'Warning: This form can only be used if JavaScript is enabled in your browser.', 'chessgame-shizzle' ) . '</div></noscript>';

	/* Submit button */
	$output .= '
			<div class="cs_submit">
				<div class="input">
					<input type="submit" name="chessgame_shizzle_submit" id="chessgame_shizzle_submit" class="btn btn-primary button" value="' . esc_attr__('Submit', 'chessgame-shizzle') . '" />
					<input type="button" name="chessgame_shizzle_preview" id="chessgame_shizzle_preview" class="btn button" value="' . esc_attr__('Preview', 'chessgame-shizzle') . '" />
				</div>
			</div>
			<div class="clear"></div>
			<div class="cs-preview"></div>
			<div class="clear"></div>
		</form>
		';


	// Add filter for the form, so devs can manipulate it.
	$output = apply_filters( 'chessgame_shizzle_form', $output);

	return $output;

}
add_shortcode( 'chessgame_shizzle_form', 'get_chessgame_shizzle_form' );
