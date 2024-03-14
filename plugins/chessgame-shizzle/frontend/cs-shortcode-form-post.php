<?php
/*
 * Handle the $_POST for the frontend on a new entry.
 * Use the 'wp' hook, since $post is populated and we can use get_the_ID().
 *
 * @since 1.0.0
 */
function chessgame_shizzle_form_post() {
	if ( ! is_admin() ) {
		/* Frontend Handling of $_POST, only one form. */
		if ( isset($_POST['cs_function']) && $_POST['cs_function'] === 'new_chessgame' ) {
			chessgame_shizzle_form_post_handling();
		}
	}
}
add_action('wp', 'chessgame_shizzle_form_post');


function chessgame_shizzle_form_post_handling() {
	$post_status = 'pending';

	$cs_title            = '';
	$cs_content          = '';
	$cs_pgn              = ''; // required
	$cs_white_player     = ''; // required
	$cs_black_player     = ''; // required
	$cs_result           = '';
	$cs_elo_white_player = '';
	$cs_elo_black_player = '';
	$cs_datetime         = '';
	$cs_location         = '';
	$cs_tournament       = '';
	$cs_round            = '';
	$cs_submitter        = '';
	$cs_code             = '';
	$cs_puzzle           = 0;


	/* Title */
	if (isset($_POST['cs_title'])) {
		$cs_title = chessgame_shizzle_sanitize_meta($_POST['cs_title']);
		chessgame_shizzle_add_formdata( 'cs_title', $cs_title );
	}

	/* PGN (required) */
	if (isset($_POST['cs_pgn'])) {
		$cs_pgn = chessgame_shizzle_sanitize_pgn($_POST['cs_pgn']);
		chessgame_shizzle_add_formdata( 'cs_pgn', $cs_pgn );
	}
	if ( $cs_pgn === '' ) {
		// required
		chessgame_shizzle_add_message( '<p class="error_fields"><strong>' . esc_html__('Your PGN data is not filled in, even though it is mandatory.', 'chessgame-shizzle') . '</strong></p>', true, 'cs_pgn');
	}

	/* Content */
	if (isset($_POST['cs_content'])) {
		$cs_content = chessgame_shizzle_sanitize_content($_POST['cs_content']);
		chessgame_shizzle_add_formdata( 'cs_content', $cs_content );
	}
	if ( $cs_content === '' ) {
		$cs_content = chessgame_shizzle_get_content_from_pgn( $cs_pgn );
	}

	/* White player (required) */
	if (isset($_POST['cs_white_player'])) {
		$cs_white_player = chessgame_shizzle_sanitize_meta($_POST['cs_white_player']);
		chessgame_shizzle_add_formdata( 'cs_white_player', $cs_white_player );
	}
	if ( $cs_white_player === '' ) {
		// required
		chessgame_shizzle_add_message( '<p class="error_fields"><strong>' . esc_html__('Your White player is not filled in, even though it is mandatory.', 'chessgame-shizzle') . '</strong></p>', true, 'cs_white_player');
	}

	/* Black player (required) */
	if (isset($_POST['cs_black_player'])) {
		$cs_black_player = chessgame_shizzle_sanitize_meta($_POST['cs_black_player']);
		chessgame_shizzle_add_formdata( 'cs_black_player', $cs_black_player );
	}
	if ( $cs_black_player === '' ) {
		// required
		chessgame_shizzle_add_message( '<p class="error_fields"><strong>' . esc_html__('Your Black player is not filled in, even though it is mandatory.', 'chessgame-shizzle') . '</strong></p>', true, 'cs_black_player');
	}

	/* Title (revisit) */
	if ( $cs_title === '' ) {
		// Generate title from playernames.
		$cs_title = $cs_white_player . ' - ' . $cs_black_player;
		chessgame_shizzle_add_formdata( 'cs_title', $cs_title );
	}

	/* Result */
	if (isset($_POST['cs_result'])) {
		$cs_result = chessgame_shizzle_sanitize_meta($_POST['cs_result']);
		chessgame_shizzle_add_formdata( 'cs_result', $cs_result );
	}

	/* Elo White player */
	if (isset($_POST['cs_elo_white_player'])) {
		$cs_elo_white_player = chessgame_shizzle_sanitize_meta_elo($_POST['cs_elo_white_player']);
		chessgame_shizzle_add_formdata( 'cs_elo_white_player', $cs_elo_white_player );
	}

	/* Elo Black player */
	if (isset($_POST['cs_elo_black_player'])) {
		$cs_elo_black_player = chessgame_shizzle_sanitize_meta_elo($_POST['cs_elo_black_player']);
		chessgame_shizzle_add_formdata( 'cs_elo_black_player', $cs_elo_black_player );
	}

	/* Datetime */
	if (isset($_POST['cs_datetime'])) {
		$cs_datetime = chessgame_shizzle_sanitize_meta($_POST['cs_datetime']);
		chessgame_shizzle_add_formdata( 'cs_datetime', $cs_datetime );
	}

	/* Location */
	if (isset($_POST['cs_location'])) {
		$cs_location = chessgame_shizzle_sanitize_meta($_POST['cs_location']);
		chessgame_shizzle_add_formdata( 'cs_location', $cs_location );
	}

	/* Tournament */
	if (isset($_POST['cs_tournament'])) {
		$cs_tournament = chessgame_shizzle_sanitize_meta($_POST['cs_tournament']);
		chessgame_shizzle_add_formdata( 'cs_tournament', $cs_tournament );
	}

	/* Round */
	if (isset($_POST['cs_round'])) {
		$cs_round = chessgame_shizzle_sanitize_meta($_POST['cs_round']);
		chessgame_shizzle_add_formdata( 'cs_round', $cs_round );
	}

	/* Submitter */
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id(); // returns 0 if no current user
		$userdata = get_userdata( $user_id );
		if ( is_object( $userdata ) ) {
			if ( isset( $userdata->display_name ) ) {
				$cs_submitter = esc_attr( $userdata->display_name );
			} else {
				$cs_submitter = esc_attr( $userdata->user_login );
			}
			chessgame_shizzle_add_formdata( 'cs_submitter', $cs_submitter );
		}
	} else {
		if (isset($_POST['cs_submitter'])) {
			$cs_submitter = chessgame_shizzle_sanitize_meta($_POST['cs_submitter']);
			chessgame_shizzle_add_formdata( 'cs_submitter', $cs_submitter );
		}
	}

	/* Opening code */
	if (isset($_POST['cs_chessgame_code'])) {
		$cs_code = chessgame_shizzle_sanitize_meta_code($_POST['cs_chessgame_code']);
		chessgame_shizzle_add_formdata( 'cs_code', $cs_code );
	}

	/* Puzzle checkbox (or with FEN code in PGN data) */
	$cs_fen = chessgame_shizzle_pgn_get_fen( $cs_pgn );
	$cs_new_fen = chessgame_shizzle_get_new_fen();
	if ( strlen($cs_fen) > 0 && strlen($cs_new_fen) > 0 && $cs_fen !== $cs_new_fen ) {
		$cs_puzzle = 1;
		chessgame_shizzle_add_formdata( 'cs_puzzle', 'on' );
	} else if ( isset($_POST['cs_puzzle']) && $_POST['cs_puzzle'] === 'on' ) {
		$cs_puzzle = 1;
		chessgame_shizzle_add_formdata( 'cs_puzzle', 'on' );
	}

	/* Honeypot: check for spam and set accordingly. */
	if (get_option( 'chessgame_shizzle-honeypot', 'true') === 'true') {
		$field_name = chessgame_shizzle_get_field_name( 'honeypot' );
		$field_name2 = chessgame_shizzle_get_field_name( 'honeypot2' );
		$honeypot_value = (int) get_option( 'chessgame_shizzle-honeypot_value', 15 );
		if ( isset($_POST["$field_name"]) && strlen($_POST["$field_name"]) > 0 ) {
			// Input field was filled in, so considered spam
			chessgame_shizzle_add_message( '<p class="refuse-spam-honeypot"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'chessgame-shizzle') . '</strong></p>', true, false );
		}
		if ( ! isset($_POST["$field_name2"]) || (int) $_POST["$field_name2"] !== $honeypot_value ) {
			// Input field was not filled in correctly, so considered spam
			chessgame_shizzle_add_message( '<p class="refuse-spam-honeypot2"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'chessgame-shizzle') . '</strong></p>', true, false );
		}
	}

	/* Nonce: check for spam and set accordingly. */
	if (get_option( 'chessgame_shizzle-nonce', 'true') === 'true') {
		$field_name = chessgame_shizzle_get_field_name( 'nonce' );
		$verified = wp_verify_nonce( $_REQUEST["$field_name"], 'chessgame_shizzle_form' );
		if ( $verified == false ) {
			// Nonce is invalid, so considered spam
			chessgame_shizzle_add_message( '<p class="refuse-spam-nonce"><strong>' . esc_html__('The Nonce did not validate. Please try again.', 'chessgame-shizzle') . '</strong></p>', true, false );
		}
	}

	/* Form Timeout: check for spam and set accordingly. */
	if (get_option( 'chessgame_shizzle-timeout', 'true') === 'true') {
		$field_name = chessgame_shizzle_get_field_name( 'timeout' );
		$field_name2 = chessgame_shizzle_get_field_name( 'timeout2' );
		if ( isset($_POST["$field_name"]) && strlen($_POST["$field_name"]) > 0 && isset($_POST["$field_name2"]) && strlen($_POST["$field_name2"]) > 0 ) {
			// Input fields were filled in, so continue.
			$timeout  = (int) $_POST["$field_name"];
			$timeout2 = (int) $_POST["$field_name2"];
			if ( ( $timeout2 - $timeout ) < 1 ) {
				// Submitted less then 1 second after loading. Considered spam.
				chessgame_shizzle_add_message( '<p class="refuse-spam-timeout"><strong>' . esc_html__('Your entry was submitted too fast, please slow down and try again.', 'chessgame-shizzle') . '</strong></p>', true, false );
			}
		} else {
			// Input fields were not filled in correctly. Considered spam.
			chessgame_shizzle_add_message( '<p class="refuse-spam-timeout"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'chessgame-shizzle') . '</strong></p>', true, false );
		}
	}


	/* Check for errors. */
	$cs_errors = chessgame_shizzle_get_errors();
	if ( $cs_errors ) {
		return false; // Do not process further.
	}


	/*
	 * Save post, and save meta when it is fine.
	 * Setting both dates will set the published date to this, instead of when moderating.
	 */
	$post_date = current_time( 'mysql' );
	$post_date_gmt = get_gmt_from_date( $post_date );

	$post_data = array(
		'post_parent'    => 0,
		'post_status'    => $post_status,
		'post_type'      => 'cs_chessgame',
		'post_date'      => $post_date,
		'post_date_gmt'  => '0000-00-00 00:00:00', // will use the date of first publishing.
		'post_author'    => get_current_user_id(),
		'post_password'  => '',
		'post_content'   => $cs_content,
		'post_title'     => $cs_title,
		'menu_order'     => 0,
	);
	$post_id = wp_insert_post( $post_data );

	/* Bail if no post was added. */
	if ( empty( $post_id ) ) {
		chessgame_shizzle_add_message( '<p class="notsaved">' . esc_html__('Sorry, something went wrong with saving your chessgame. Please contact a site admin.', 'chessgame-shizzle') . '</p>', true, false );
		return 0;
	}

	$post_meta = array(
		'cs_chessgame_pgn'              => $cs_pgn,
		'cs_chessgame_white_player'     => $cs_white_player,
		'cs_chessgame_black_player'     => $cs_black_player,
		'cs_chessgame_result'           => $cs_result,
		'cs_chessgame_elo_white_player' => $cs_elo_white_player,
		'cs_chessgame_elo_black_player' => $cs_elo_black_player,
		'cs_chessgame_datetime'         => $cs_datetime,
		'cs_chessgame_location'         => $cs_location,
		'cs_chessgame_tournament'       => $cs_tournament,
		'cs_chessgame_round'            => $cs_round,
		'cs_chessgame_submitter'        => $cs_submitter,
		'cs_chessgame_code'             => $cs_code,
		'cs_chessgame_puzzle'           => $cs_puzzle,
	);

	// Insert post meta.
	foreach ( $post_meta as $meta_key => $meta_value ) {
		update_post_meta( $post_id, $meta_key, $meta_value );
	}


	/*
	 * Hooks chessgame_shizzle_mail_moderators().
	 */
	do_action( 'chessgame_shizzle_save_frontend', $post_id );


	chessgame_shizzle_add_message( '<p class="saved">' . esc_html__('Thank you for your chessgame.', 'chessgame-shizzle') . '</p>', false, false );
	chessgame_shizzle_add_message( '<p>' . esc_html__('We will review it and unlock it in a short while.', 'chessgame-shizzle') . '</p>', false, false );

	return $post_id;
}
