<?php /*
 *
 * Import page for Chessgame Shizzle admin.
 * Lets the user import chessgames from a PGN file.
 *
 * Tested with Chessbase 8.
 *
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


function chessgame_shizzle_page_import() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	}

	/* $_POST handling. */
	if ( isset( $_POST['chessgame_shizzle_page']) && $_POST['chessgame_shizzle_page'] === 'chessgame_shizzle_import' ) {
		chessgame_shizzle_page_import_post();
	}


	/*
	 * Build the Page and the Form
	 */
	?>
	<div class="wrap chessgame_shizzle">
		<h1><?php esc_html_e('Import chessgames from a PGN file', 'chessgame-shizzle'); ?></h1>

		<?php
		$chessgame_shizzle_messages = chessgame_shizzle_get_messages();
		$chessgame_shizzle_errors   = chessgame_shizzle_get_errors();
		$messageclass = '';
		if ( $chessgame_shizzle_errors ) {
			$messageclass = 'error';
		}

		if ( $chessgame_shizzle_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . $messageclass . ' ">' .
					$chessgame_shizzle_messages .
				'</div>';
		} ?>

		<div id="poststuff" class="chessgame_shizzle_import metabox-holder">
			<div class="postbox-container">

				<?php
				add_meta_box('chessgame_shizzle_import_postbox', esc_html__('Import chessgames from a PGN file', 'chessgame-shizzle'), 'chessgame_shizzle_import_postbox', 'chessgame_shizzle_import', 'normal');
				do_meta_boxes( 'chessgame_shizzle_import', 'normal', '' );
				?>

			</div>
		</div>
	</div>
	<?php
}


function chessgame_shizzle_import_postbox() {
	?>
	<form name="chessgame_shizzle_import_form" id="chessgame_shizzle_import_form" method="POST" action="#" accept-charset="UTF-8" enctype="multipart/form-data">
		<input type="hidden" name="chessgame_shizzle_page" value="chessgame_shizzle_import" />

		<?php
		/* Nonce */
		$nonce = wp_create_nonce( 'chessgame_shizzle_nonce_import' );
		echo '<input type="hidden" id="chessgame_shizzle_nonce_import" name="chessgame_shizzle_nonce_import" value="' . esc_attr( $nonce ) . '" />';
		?>

		<p>
			<label for="start_import_cs_file" class="selectit"><?php esc_html_e('Select a PGN file with chessgames to import from', 'chessgame-shizzle'); ?><br /><br />
				<input id="start_import_cs_file" name="start_import_cs_file" type="file" />
			</label>
		</p>
		<p>
			<input name="start_import_cs" id="start_import_cs" type="submit" class="button" disabled value="<?php esc_attr_e('Start import', 'chessgame-shizzle'); ?>">
		</p>
	</form>
	<?php
}


function chessgame_shizzle_page_import_post() {

	if ( ! current_user_can('manage_options') ) {
		chessgame_shizzle_add_message( '<p>' . esc_html__('You need a higher level of permission.', 'chessgame-shizzle') . '</p>', true, false);
		return;
	}

	if ( isset( $_POST['chessgame_shizzle_page']) && $_POST['chessgame_shizzle_page'] === 'chessgame_shizzle_import' ) {
		if (isset($_POST['start_import_cs'])) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['chessgame_shizzle_nonce_import']) ) {
				$verified = wp_verify_nonce( $_POST['chessgame_shizzle_nonce_import'], 'chessgame_shizzle_nonce_import' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				chessgame_shizzle_add_message( '<p>' . esc_html__('Nonce check failed. Please try again.', 'chessgame-shizzle') . '</p>', true, false);
				return;
			}

			// if they DID upload a file...
			if ( $_FILES['start_import_cs_file']['name'] ) {
				if ( ! $_FILES['start_import_cs_file']['error'] ) { // if no errors...
					// Add extension so PgnParser works with it.
					move_uploaded_file( $_FILES['start_import_cs_file']['tmp_name'], $_FILES['start_import_cs_file']['tmp_name'] . '.pgn' );

					if ( $_FILES['start_import_cs_file']['size'] > ( 4096000 ) ) { // Can't be larger than 4 MB
						$valid_file = false;
						chessgame_shizzle_add_message( '<p>' . esc_html__('Your file is too large.', 'chessgame-shizzle') . '</p>', true, false);
					} else {
						if ( version_compare( PHP_VERSION, '5.3', '<' ) && ( ! $mimetype ) ) {
							chessgame_shizzle_add_message( '<p>' . esc_html__('You have a very old version of PHP. Please contact your hosting provider and request an upgrade.', 'chessgame-shizzle') . '</p>', false, false);
						}
						if ( file_exists( $_FILES['start_import_cs_file']['tmp_name'] . '.pgn' ) ) {

							chessgame_shizzle_chessparser_include();
							$parser = new PgnParser( $_FILES['start_import_cs_file']['tmp_name'] . '.pgn', false );
							$gamelist = $parser->getGames(); // array
							$gamelist_unparsed = $parser->getUnparsedGames(); // array

							$counter_yes = 0;
							$counter_not = 0;
							foreach ( $gamelist as $key => $game ) {

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
								$cs_reference        = '';

								/* PGN (required). Use unparsed PGN so we keep annotations and everything. */
								if (isset($gamelist_unparsed["$key"])) {
									$cs_pgn = chessgame_shizzle_sanitize_pgn( $gamelist_unparsed["$key"] );
								}
								if ( $cs_pgn === '' ) {
									$counter_not++;
									continue; // discard this one, no pgn data.
								}

								/* Content */
								if (isset($game['metadata']['cs_content'])) {
									$cs_content = $game['metadata']['cs_content'];
								} else {
									$cs_content = chessgame_shizzle_get_content_from_pgn( $cs_pgn );
								}

								/* White player (required) */
								if (isset($game['white'])) {
									$cs_white_player = chessgame_shizzle_sanitize_meta($game['white']);
								}

								/* Black player (required) */
								if (isset($game['black'])) {
									$cs_black_player = chessgame_shizzle_sanitize_meta($game['black']);
								}

								/* Title */
								// Generate title from playernames.
								$cs_title = $cs_white_player . ' - ' . $cs_black_player;

								/* Result */
								if (isset($game['result'])) {
									$cs_result = chessgame_shizzle_sanitize_meta($game['result']);
								}

								/* Elo White player */
								if (isset($game['metadata']['whiteelo'])) {
									$cs_elo_white_player = chessgame_shizzle_sanitize_meta_elo($game['metadata']['whiteelo']);
								}

								/* Elo Black player */
								if (isset($game['metadata']['blackelo'])) {
									$cs_elo_black_player = chessgame_shizzle_sanitize_meta_elo($game['metadata']['blackelo']);
								}

								/* Datetime */
								if (isset($game['date'])) {
									$cs_datetime = chessgame_shizzle_sanitize_meta($game['date']);
									if ( $cs_datetime === '????.??.??' && isset( $game['metadata']['datetime'] ) ) {
										$cs_datetime = chessgame_shizzle_sanitize_meta( $game['metadata']['datetime'] );
									}
								}

								/* Location */
								if (isset($game['site'])) {
									$cs_location = chessgame_shizzle_sanitize_meta($game['site']);
								}

								/* Tournament */
								if (isset($game['event'])) {
									$cs_tournament = chessgame_shizzle_sanitize_meta($game['event']);
								}

								/* Round */
								if (isset($game['round'])) {
									$cs_round = chessgame_shizzle_sanitize_meta($game['round']);
								}

								/* Submitter */
								if (isset($game['annotator'])) {
									$cs_submitter = chessgame_shizzle_sanitize_meta($game['annotator']);
								}

								/* Opening code */
								if (isset($game['eco'])) {
									$cs_code = chessgame_shizzle_sanitize_meta_code($game['eco']);
								}

								/* Puzzle */
								if (isset($game['metadata']['puzzle'])) {
									$cs_puzzle = (int) chessgame_shizzle_sanitize_meta($game['metadata']['puzzle']);
								} else {
									$cs_fen = chessgame_shizzle_pgn_get_fen( $cs_pgn );
									$cs_new_fen = chessgame_shizzle_get_new_fen();
									if ( strlen($cs_fen) > 0 && strlen($cs_new_fen) > 0 && $cs_fen !== $cs_new_fen ) {
										$cs_puzzle = 1;
									}
								}

								/* Reference from export, original WordPress Post ID */
								if (isset($game['metadata']['cs_reference'])) {
									$cs_reference = (int) chessgame_shizzle_sanitize_meta($game['metadata']['cs_reference']);
								}

								/*
								 * Save post, and save meta when it is fine.
								 */
								$post_data = array(
									'post_parent'    => 0,
									'post_status'    => 'publish',
									'post_type'      => 'cs_chessgame',
									'post_author'    => get_current_user_id(),
									'post_password'  => '',
									'post_content'   => $cs_content,
									'post_title'     => $cs_title,
									'menu_order'     => 0,
								);
								$post_id = wp_insert_post( $post_data );

								/* Bail if no post was added. */
								if ( empty( $post_id ) ) {
									$counter_not++;
									continue;
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
									'cs_chessgame_reference'        => $cs_reference,
								);
								// Insert post meta.
								foreach ( $post_meta as $meta_key => $meta_value ) {
									update_post_meta( $post_id, $meta_key, $meta_value );
								}

								$counter_yes++;
							}

							chessgame_shizzle_add_message( '<p>' . sprintf( _n('%s chessgame imported successfully from the PGN file.', '%s chessgames imported successfully from the PGN file.', $counter_yes, 'chessgame-shizzle'), $counter_yes ) . '</p>', false, false);

							if ( $counter_not ) {
								chessgame_shizzle_add_message( '<p>' . sprintf( _n('%s chessgame failed to be imported from the PGN file.', '%s chessgames failed to be imported from the PGN file.', $counter_not, 'chessgame-shizzle'), $counter_not ) . '</p>', false, false);
							}
						}
					}
				} else {
					// Set that to be the returned message.
					chessgame_shizzle_add_message( '<p>' . esc_html__('Your upload triggered the following error:', 'chessgame-shizzle') . ' ' . sanitize_text_field( $_FILES['start_import_cs_file']['error'] ) . '</p>', true, false);
				}
			}
		}
	}
}


function chessgame_shizzle_menu_import() {
	add_submenu_page('edit.php?post_type=cs_chessgame', esc_html__('Import', 'chessgame-shizzle'), esc_html__('Import', 'chessgame-shizzle'), 'manage_options', 'cs_import', 'chessgame_shizzle_page_import');
}
add_action( 'admin_menu', 'chessgame_shizzle_menu_import', 18 );
