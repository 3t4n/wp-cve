<?php
/*
 * Support preview of chessgame in an iframe.
 * Only show a game when the preview nonce is verified.
 *
 * @since 1.2.1
 *
 */

	?>
	<!DOCTYPE html>
	<html>
		<head>
			<?php
			if ( function_exists( 'wp_custom_css_cb' ) ) {
				wp_custom_css_cb();
			}
			?>
		</head>

		<body class="cs-iframe cs-iframe-extended">

	<?php
	require_once '../../../../../wp-load.php';

	$verified = wp_verify_nonce( $_GET['cs_nonce'], 'chessgame_shizzle_form' );
	if ( $verified === false ) {
		// Nonce is invalid, so considered spam
		echo '<p class="refuse-spam-nonce"><strong>' . esc_html__('The Nonce did not validate. Please try again.', 'chessgame-shizzle') . $_GET['cs_nonce'] . '</strong></p>';
	} else {

		// PGN data
		if ( isset($_GET['cs_pgn']) && strlen( $_GET['cs_pgn'] ) > 0 ) {
			$cs_chessgame_pgn = wp_unslash( $_GET['cs_pgn'] );
		}
		if ( ! isset( $cs_chessgame_pgn ) ) {
			echo '<p class="refuse-no-pgn-data"><strong>' . esc_html__('Your PGN data is not filled in, please try again.', 'chessgame-shizzle') . '</strong></p>';
		} else {

			$class_div = '';
			$cs_chessgame_pgn = chessgame_shizzle_sanitize_pgn( $cs_chessgame_pgn );

			/* Puzzle checkbox (or with FEN code in PGN data) */
			$cs_fen = chessgame_shizzle_pgn_get_fen( $cs_chessgame_pgn );
			$cs_new_fen = chessgame_shizzle_get_new_fen();
			if ( strlen($cs_fen) > 0 && strlen($cs_new_fen) > 0 && $cs_fen !== $cs_new_fen ) {
				$class_div .= ' cs-puzzle';
			} else if ( isset($_GET['cs_puzzle']) && $_GET['cs_puzzle'] === 'on' ) {
				$class_div .= ' cs-puzzle';
			}

			$class_div .= ' ' . chessgame_shizzle_get_boardtheme_class();

			/* pgn4web.js */
			echo '
				<div class="cs-chessgame-iframe-extended">
				<div class="cs_chessgame_pgn chessboard-wrapper' . esc_attr( $class_div ) . '">
					<form style="display: none;">
						<textarea style="display: none;" id="pgnText">
							' . esc_textarea($cs_chessgame_pgn) . '
						</textarea>
					</form>
					<center>
						<div id="GameBoard"></div>
						<p></p>
						<div id="GameButtons"></div>
						<p></p>
						<div id="GameText"></div>
						<div id="GamePuzzleTask">
							<div class="cs-icon-color"></div>
							<a class="cs-puzzle-task" href="#" title=""></a>
							<span class="cs-text-white" style="display:none;">' . esc_html__( 'White to move: find the best move... click the ? for the solution', 'chessgame-shizzle' ) . '</span>
							<span class="cs-text-black" style="display:none;">' . esc_html__( 'Black to move: find the best move... click the ? for the solution', 'chessgame-shizzle' ) . '</span>
						</div>
					</center>
				</div>
				<noscript><div class="no-js">' . esc_html__( 'Warning: This game can only be seen if JavaScript is enabled in your browser.', 'chessgame-shizzle' ) . '</div></noscript>
			</div">';

			chessgame_shizzle_pgn4web_dead_enqueue( 0 );

		}

	}

	?>

		</body>
	</html>
