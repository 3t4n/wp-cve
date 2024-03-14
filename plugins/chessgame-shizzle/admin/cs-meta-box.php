<?php
/*
 * Metabox for Chessgame admin.
 * See frontend/cs-content-filters.php for the frontend.
 * @since 1.0.0
 *
 * Boxes are here for custom fields:
 * - PGN data
 * - White player
 * - Black player
 * - Result
 * - Elo white player
 * - Elo black player
 * - Date/Time
 * - Location
 * - Tournament, Occasion
 * - Submitter
 * - ECO code
 * - Puzzle checkbox
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Metabox for Chessgame admin.
 * See frontend/cs-content-filters.php for the frontend.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_add_meta_box() {
	add_meta_box('cs_chessgame-posts-box', esc_html__('PGN and Metadata of the Chessgame', 'chessgame-shizzle' ), 'chessgame_shizzle_display_meta_box', 'cs_chessgame', 'normal', 'high');
}
add_action('admin_menu', 'chessgame_shizzle_add_meta_box');


/*
 * Metabox for Chessgame admin.
 * See frontend/cs-content-filters.php for the frontend.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_display_meta_box() {
	$post_id = get_the_ID();

	wp_nonce_field( basename( __FILE__ ), 'cs_chessgame_metabox_nonce' );

	$cs_chessgame_pgn = get_post_meta($post_id, 'cs_chessgame_pgn', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_pgn" for="cs_chessgame_pgn">
				<span class="cs_chessgame_title">' . esc_html__('PGN data', 'chessgame-shizzle' ) . '</span>
				' . chessgame_shizzle_get_admin_help_text() . '
				<br />
				<textarea name="cs_chessgame_pgn" id="cs_chessgame_pgn" placeholder="1. e4 e6 2. d4 d5 3. exd5 exd5 4. Nf3 Nf6 5. Bd3 Bd6 ...">' . esc_textarea($cs_chessgame_pgn) . '</textarea>
			</label>
		</div>';

	$cs_chessgame_white_player = get_post_meta($post_id, 'cs_chessgame_white_player', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_white_player" for="cs_chessgame_white_player">
				<span class="cs_chessgame_title">' . esc_html__('White player', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_white_player" id="cs_chessgame_white_player" value="' . esc_attr($cs_chessgame_white_player) . '" placeholder="' . esc_attr__('White player', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_black_player = get_post_meta($post_id, 'cs_chessgame_black_player', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_black_player" for="cs_chessgame_black_player">
				<span class="cs_chessgame_title">' . esc_html__('Black player', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_black_player" id="cs_chessgame_black_player" value="' . esc_attr($cs_chessgame_black_player) . '" placeholder="' . esc_attr__('Black player', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_result = get_post_meta($post_id, 'cs_chessgame_result', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_result" for="cs_chessgame_result">
				<span class="cs_chessgame_title">' . esc_html__('Result', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_result" id="cs_chessgame_result" value="' . esc_attr($cs_chessgame_result) . '" placeholder="' . esc_attr__('Result', 'chessgame-shizzle' ) . '">
				<select class="cs_result_ajax" name="cs_result_ajax" data-placeholder="' . esc_attr__('Choose a result...', 'chessgame-shizzle' ) . '">
					<option value="">' . esc_html__('Choose a result...', 'chessgame-shizzle') . '</option>
					<option value="1-0">1-0</option>
					<option value="0-1">0-1</option>
					<option value="½-½">½-½</option>
					<option value="1-0R">1-0R</option>
					<option value="0-1R">0-1R</option>
					<option value="*">* ' . esc_html__('(undecided yet)', 'chessgame-shizzle') . '</option>
				</select>
			</label>
		</div>';

	$cs_chessgame_elo_white_player = get_post_meta($post_id, 'cs_chessgame_elo_white_player', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_elo_white_player" for="cs_chessgame_elo_white_player">
				<span class="cs_chessgame_title">' . esc_html__('Elo white player', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_elo_white_player" id="cs_chessgame_elo_white_player" value="' . esc_attr($cs_chessgame_elo_white_player) . '" placeholder="' . esc_attr__('Elo white player', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_elo_black_player = get_post_meta($post_id, 'cs_chessgame_elo_black_player', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_elo_black_player" for="cs_chessgame_elo_black_player">
				<span class="cs_chessgame_title">' . esc_html__('Elo black player', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_elo_black_player" id="cs_chessgame_elo_black_player" value="' . esc_attr($cs_chessgame_elo_black_player) . '" placeholder="' . esc_attr__('Elo black player', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_datetime = get_post_meta($post_id, 'cs_chessgame_datetime', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_datetime" for="cs_chessgame_datetime">
				<span class="cs_chessgame_title">' . esc_html__('Date', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_datetime" id="cs_chessgame_datetime" value="' . esc_attr($cs_chessgame_datetime) . '" placeholder="' . esc_attr( date( 'Y.m.d', time() ) ) . '">
			</label>
		</div>';

	$cs_chessgame_location = get_post_meta($post_id, 'cs_chessgame_location', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_location" for="cs_chessgame_location">
				<span class="cs_chessgame_title">' . esc_html__('Location', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_location" id="cs_chessgame_location" value="' . esc_attr($cs_chessgame_location) . '" placeholder="' . esc_attr__('Location', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_tournament = get_post_meta($post_id, 'cs_chessgame_tournament', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_tournament" for="cs_chessgame_tournament">
				<span class="cs_chessgame_title">' . esc_html__('Tournament', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_tournament" id="cs_chessgame_tournament" value="' . esc_attr($cs_chessgame_tournament) . '" placeholder="' . esc_attr__('Tournament', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_round = get_post_meta($post_id, 'cs_chessgame_round', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_round" for="cs_chessgame_round">
				<span class="cs_chessgame_title">' . esc_html__('Round', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_round" id="cs_chessgame_round" value="' . esc_attr($cs_chessgame_round) . '" placeholder="' . esc_attr__('Round', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_submitter = get_post_meta($post_id, 'cs_chessgame_submitter', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_submitter" for="cs_chessgame_submitter">
				<span class="cs_chessgame_title">' . esc_html__('Submitter', 'chessgame-shizzle' ) . '</span>
				<input type="text" name="cs_chessgame_submitter" id="cs_chessgame_submitter" value="' . esc_attr($cs_chessgame_submitter) . '" placeholder="' . esc_attr__('Submitter', 'chessgame-shizzle' ) . '">
			</label>
		</div>';

	$cs_chessgame_code = get_post_meta($post_id, 'cs_chessgame_code', true);
	$dropdown = chessgame_shizzle_get_dropdown_openingcodes( $cs_chessgame_code, 'cs_chessgame_code' );
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_code" for="cs_chessgame_code">
				<span class="cs_chessgame_title">' . esc_html__('Opening code', 'chessgame-shizzle' ) . '</span>' .
				$dropdown . '
			</label>
		</div>';

	$cs_chessgame_puzzle = get_post_meta($post_id, 'cs_chessgame_puzzle', true);
	$cs_puzzle = '';
	if ( $cs_chessgame_puzzle ) {
		$cs_puzzle = ' checked="checked"';
	}
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_puzzle" for="cs_chessgame_puzzle">
				<span class="cs_chessgame_title">' . esc_html__('Puzzle', 'chessgame-shizzle' ) . '</span>
				<input type="checkbox" name="cs_chessgame_puzzle" id="cs_chessgame_puzzle" ' . $cs_puzzle . ' ">
			</label>
		</div>';

	$cs_chessgame_level = (int) get_post_meta($post_id, 'cs_chessgame_level', true);
	echo '
		<div class="cs_chessgame_custom_field">
			<label class="cs_chessgame_level" for="cs_chessgame_level">
				<span class="cs_chessgame_title">' . esc_html__('Level', 'chessgame-shizzle' ) . '</span>
				<select class="cs_chessgame_level" name="cs_chessgame_level" data-placeholder="' . esc_attr__('Level of the puzzle...', 'chessgame-shizzle' ) . '">
					<option value="">' . esc_html__('Level of the puzzle...', 'chessgame-shizzle') . '</option>
					<option value="1" ' . selected( 1, $cs_chessgame_level, false ) . '>' . esc_html__('More Easy', 'chessgame-shizzle' ) . '</option>
					<option value="2" ' . selected( 2, $cs_chessgame_level, false ) . '>' . esc_html__('Easy', 'chessgame-shizzle' ) . '</option>
					<option value="3" ' . selected( 3, $cs_chessgame_level, false ) . '>' . esc_html__('Standard', 'chessgame-shizzle' ) . '</option>
					<option value="4" ' . selected( 4, $cs_chessgame_level, false ) . '>' . esc_html__('Difficult', 'chessgame-shizzle' ) . '</option>
					<option value="5" ' . selected( 5, $cs_chessgame_level, false ) . '>' . esc_html__('More Difficult', 'chessgame-shizzle' ) . '</option>
				</select>
			</label>
		</div>';

}


/*
 * Save metabox for chessgame.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_save_meta_box( $id ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

	$post = get_post();

	if ( 'cs_chessgame' != get_post_type( $post ) ) {
		return;
	}

	$page = get_current_screen();
	$page = $page->base;

	/* Check that the user is allowed to edit the post. */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['cs_chessgame_metabox_nonce']) ) {
		$verified = wp_verify_nonce( $_POST['cs_chessgame_metabox_nonce'], basename( __FILE__ ) );
	}
	if ( $verified == false ) {
		return; // Nonce is invalid, do not process further.
	}

	if ( isset($_POST['cs_chessgame_pgn']) ) {
		$pgn = chessgame_shizzle_sanitize_pgn( $_POST['cs_chessgame_pgn'] );
		update_post_meta($id, 'cs_chessgame_pgn', $pgn);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_pgn']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_pgn');
		}
	}

	if ( isset($_POST['cs_chessgame_white_player']) ) {
		$white = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_white_player'] );
		update_post_meta($id, 'cs_chessgame_white_player', $white);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_white_player']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_white_player');
		}
	}

	if ( isset($_POST['cs_chessgame_black_player']) ) {
		$black = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_black_player'] );
		update_post_meta($id, 'cs_chessgame_black_player', $black);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_black_player']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_black_player');
		}
	}

	if ( isset($_POST['cs_chessgame_result']) ) {
		$result = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_result'] );
		update_post_meta($id, 'cs_chessgame_result', $result);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_result']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_result');
		}
	}

	if ( isset($_POST['cs_chessgame_elo_white_player']) ) {
		$elo_white = chessgame_shizzle_sanitize_meta_elo( $_POST['cs_chessgame_elo_white_player'] );
		update_post_meta($id, 'cs_chessgame_elo_white_player', $elo_white);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_elo_white_player']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_elo_white_player');
		}
	}

	if ( isset($_POST['cs_chessgame_elo_black_player']) ) {
		$elo_black = chessgame_shizzle_sanitize_meta_elo( $_POST['cs_chessgame_elo_black_player'] );
		update_post_meta($id, 'cs_chessgame_elo_black_player', $elo_black);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_elo_black_player']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_elo_black_player');
		}
	}

	if ( isset($_POST['cs_chessgame_datetime']) ) {
		$datetime = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_datetime'] );
		update_post_meta($id, 'cs_chessgame_datetime', $datetime);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_datetime']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_datetime');
		}
	}

	if ( isset($_POST['cs_chessgame_location']) ) {
		$location = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_location'] );
		update_post_meta($id, 'cs_chessgame_location', $location);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_location']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_location');
		}
	}

	if ( isset($_POST['cs_chessgame_tournament']) ) {
		$tournament = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_tournament'] );
		update_post_meta($id, 'cs_chessgame_tournament', $tournament);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_tournament']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_tournament');
		}
	}

	if ( isset($_POST['cs_chessgame_round']) ) {
		$cs_chessgame_round = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_round'] );
		update_post_meta($id, 'cs_chessgame_round', $cs_chessgame_round);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_round']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_round');
		}
	}

	if ( isset($_POST['cs_chessgame_submitter']) ) {
		$submitter = chessgame_shizzle_sanitize_meta( $_POST['cs_chessgame_submitter'] );
		update_post_meta($id, 'cs_chessgame_submitter', $submitter);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_submitter']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_submitter');
		}
	}

	if ( isset($_POST['cs_chessgame_code']) ) {
		$code = chessgame_shizzle_sanitize_meta_code( $_POST['cs_chessgame_code'] );
		update_post_meta($id, 'cs_chessgame_code', $code);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_code']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_code');
		}
	}

	/* Puzzle checkbox */
	if (isset($_POST['cs_chessgame_puzzle']) && $_POST['cs_chessgame_puzzle'] == 'on') {
		update_post_meta($id, 'cs_chessgame_puzzle', 1);
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_puzzle']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_puzzle');
		}
	}

	/* Level of Puzzle */
	if (isset($_POST['cs_chessgame_level']) && is_numeric($_POST['cs_chessgame_level']) && $_POST['cs_chessgame_level'] > 0) {
		$level_options = array( 1, 2, 3, 4, 5 );
		$postdata_level = (int) $_POST['cs_chessgame_level'];
		if ( in_array( $postdata_level, $level_options ) ) {
			update_post_meta($id, 'cs_chessgame_level', $postdata_level);
		}
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['cs_chessgame_level']) ) {
		if ( $page === 'post' ) {
			delete_post_meta($id, 'cs_chessgame_level');
		}
	}

}
add_action('save_post', 'chessgame_shizzle_save_meta_box');


/*
 * Make our meta fields protected, so they are not in the custom fields metabox.
 * Since 1.0.0
 */
function chessgame_shizzle_is_protected_meta( $protected, $meta_key, $meta_type ) {

	switch ($meta_key) {
		case 'cs_chessgame_pgn':
			return true;
		case 'cs_chessgame_white_player':
			return true;
		case 'cs_chessgame_black_player':
			return true;
		case 'cs_chessgame_result':
			return true;
		case 'cs_chessgame_elo_white_player':
			return true;
		case 'cs_chessgame_elo_black_player':
			return true;
		case 'cs_chessgame_datetime':
			return true;
		case 'cs_chessgame_location':
			return true;
		case 'cs_chessgame_tournament':
			return true;
		case 'cs_chessgame_round':
			return true;
		case 'cs_chessgame_submitter':
			return true;
		case 'cs_chessgame_code':
			return true;
		case 'cs_chessgame_puzzle':
			return true;
		case 'cs_chessgame_level':
			return true;
	}

	return $protected;

}
add_filter( 'is_protected_meta', 'chessgame_shizzle_is_protected_meta', 10, 3 );
