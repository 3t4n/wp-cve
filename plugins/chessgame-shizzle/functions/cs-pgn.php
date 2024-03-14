<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Find the FEN code inside the PGN data and return it.
 *
 * @param  string $pgn  PGN data, requires full pgn data.
 * @return string $fen  FEN code from that PGN data. Start position if no FEN was found. Empty string if not found.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_pgn_get_fen( $pgn ) {

	$pgn = trim( (string) $pgn );
	$pgn = chessgame_shizzle_add_fake_data_to_pgn( $pgn );
	$fen = '';
	chessgame_shizzle_chessparser_include();

	$pgnparser = new PgnParser();
	$pgnparser->setPgnContent($pgn);
	$game = $pgnparser->getFirstGame();

	if ( is_array( $game ) && count( $game ) > 0 ) {
		if ( isset($game['fen']) ) {
			$fen = $game['fen'];
			// we can validate it by setting and getting the fen.
			$fenparser = new FenParser0x88( $fen );
			$fen = $fenparser->getFen();
		}
	}

	return $fen;

}


/*
 * Generate a FEN code from the tart position in a game and return it.
 *
 * @return string $fen  FEN code with default start position.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_get_new_fen() {

	chessgame_shizzle_chessparser_include();

	$fenparser = new FenParser0x88();
	$fenparser->newGame();
	$fen = $fenparser->getFen();

	return $fen;

}


/*
 * Generate a FEN code from the position after the last move in the PGN data and return it.
 *
 * @param  string $pgn  PGN data, requires full pgn data.
 * @return string $fen  FEN code from that PGN data. Empty string if no valid FEN was found.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_pgn_get_last_position( $pgn ) {

	$pgn = trim( (string) $pgn );
	$pgn = chessgame_shizzle_add_fake_data_to_pgn( $pgn );
	$fen = '';
	chessgame_shizzle_chessparser_include();

	$pgnparser = new PgnParser();
	$pgnparser->setPgnContent($pgn);
	$game = $pgnparser->getFirstGame();

	if ( is_array( $game ) && count( $game ) > 0 ) {
		if ( is_array($game['moves']) && count($game['moves']) > 0 ) {
			$moves = $game['moves'];
			$lastmove = end( $moves );
			if ( isset($lastmove['fen']) ) {
				$fen = $lastmove['fen'];
				// we can validate it by setting and getting the fen.
				$fenparser = new FenParser0x88( $fen );
				$fen = $fenparser->getFen();
			}
		}
	}

	return $fen;

}

/*
 * Add tags to pgn data if it is not already in the pgn data.
 *
 * https://en.wikipedia.org/wiki/Portable_Game_Notation#Tag_pairs
 * Required tags, in this order:
 * Event  Name of the tournament or match event.
 * Site   Location of the event. This is in City, Region COUNTRY format, where COUNTRY is the three-letter International Olympic Committee code for the country. An example is New York City, NY USA.
 *        Although not part of the specification, some online chess platforms will include a URL or website as the site value.[3]
 * Date   Starting date of the game, in YYYY.MM.DD form. ?? is used for unknown values.
 * Round  Playing round ordinal of the game within the event.
 * White  Player of the white pieces, in Lastname, Firstname format.
 * Black  Player of the black pieces, same format as White.
 * Result Result of the game. It is recorded as White score, dash, then Black score, or * (other, e.g., the game is ongoing).
 *
 * Optional tags:
 * Annotator The person providing notes to the game.
 * FEN       If a FEN tag is used, a separate tag pair SetUp must also appear and have its value set to 1.
 * ECO       ECO code for categorising the opening.
 * WhiteElo  Elo rating of white player.
 * BlackElo  Elo rating of black player.
 * DateTime  Date as submitted in the upload form of this plugin.
 * Puzzle    Whether or not this game is a puzzle.
 * CS_Reference Reference from export, original WordPress Post ID.
 * CS_Content Post content, original WordPress Post content.
 *
 * @param string $pgn     PGN data.
 * @param int    $post_id Post ID that the pgn and chessgame belong to.
 *
 * @return string $pgn PGN data that should be ready for archiving.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_update_pgn_from_meta( $pgn, $post_id ) {

	$pgn_lower = strtolower( $pgn );
	if ( strpos($pgn_lower, '[event') !== false || strpos($pgn_lower, '[ event') !== false ) {
		// It is already filled with meta tags, no need to use post meta to add it.
		return $pgn;
	}

	$event     = get_post_meta($post_id, 'cs_chessgame_tournament', true);
	$site      = get_post_meta($post_id, 'cs_chessgame_location', true);
	$datetime  = get_post_meta($post_id, 'cs_chessgame_datetime', true);
	$date      = chessgame_shizzle_validate_pgn_date( $datetime);
	// not formal field, Date is the formal field.
	$datetime  = '[DateTime "' . esc_attr( $datetime ) . '"]
	';
	$round     = get_post_meta($post_id, 'cs_chessgame_round', true);
	$white     = get_post_meta($post_id, 'cs_chessgame_white_player', true);
	$black     = get_post_meta($post_id, 'cs_chessgame_black_player', true);
	$result    = get_post_meta($post_id, 'cs_chessgame_result', true);

	$annotator = get_post_meta($post_id, 'cs_chessgame_submitter', true);
	$eco       = get_post_meta($post_id, 'cs_chessgame_code', true);
	$whiteelo  = get_post_meta($post_id, 'cs_chessgame_elo_white_player', true);
	$blackelo  = get_post_meta($post_id, 'cs_chessgame_elo_black_player', true);
	$puzzle    = get_post_meta($post_id, 'cs_chessgame_puzzle', true);

	$post_content = apply_filters( 'chessgame_shizzle_no_content_text', esc_html__('(No content to display)', 'chessgame-shizzle') );
	$post = get_post( $post_id );
	if ( is_a( $post, 'WP_Post' ) ) {
		$post_content = $post->post_content;
	}

	$pgn = '[Event "' . esc_attr( $event ) . '"]
[Site "' . esc_attr( $site ) . '"]
[Round "' . esc_attr( $round ) . '"]
[Date "' . esc_attr( $date ) . '"]
[White "' . esc_attr( $white ) . '"]
[Black "' . esc_attr( $black ) . '"]
[Result "' . esc_attr( $result ) . '"]
[Annotator "' . esc_attr( $annotator ) . '"]
[ECO "' . esc_attr( $eco ) . '"]
[WhiteElo "' . (int) $whiteelo . '"]
[BlackElo "' . (int) $blackelo . '"]
[Puzzle "' . (int) $puzzle . '"]
[CS_Reference "' . (int) $post_id . '"]
[CS_Content "' . esc_attr( $post_content ) . '"]
' . $datetime
. $pgn;

	return $pgn;

}


/*
 * Generate a content field for a WordPress Post from PGN data.
 *
 * @param  string $pgn  PGN data, requires full pgn data.
 * @return string $content  Post content to be used.
 *
 * @since 1.2.3
 */
function chessgame_shizzle_get_content_from_pgn( $pgn ) {

	$content = '';
	$pgn = trim( (string) $pgn );
	$pgn = chessgame_shizzle_add_fake_data_to_pgn( $pgn );
	chessgame_shizzle_chessparser_include();

	$pgnparser = new PgnParser();
	$pgnparser->setPgnContent( $pgn );
	$game = $pgnparser->getFirstGame();

	if ( isset($game['moves']) && is_array($game['moves']) && count($game['moves']) > 0 ) {
		foreach ( $game['moves'] as $move ) {
			// comment in PGN
			if ( isset( $move['comment'] ) ) {
				if ( $content === '' ) {
					$content = chessgame_shizzle_sanitize_content( $move['comment'] );
				}
			}
		}
	}

	if ( $content === '' ) {
		$content = apply_filters( 'chessgame_shizzle_no_content_text', esc_html__('(No content to display)', 'chessgame-shizzle') );
	}

	return $content;

}


/*
 * Get a human readable date from a PGN formatted date.
 *
 * @param  string $date  PGN date. (yyyy.mm.dd)
 * @return string $date  Human readable date.
 *
 * @since 1.1.9
 */
function chessgame_shizzle_get_human_date( $date ) {

	if ( strlen( $date ) !== 10 ) {
		return $date;
	}

	$year  = substr( $date, 0, 4 );
	$month = substr( $date, 5, 2 );
	$day   = substr( $date, 8, 2 );

	$datestring = $year . '-' . $month . '-' . $day;
	$timestamp = strtotime( $datestring );

	if ( $timestamp === false ) {
		return $date;
	}

	$date = date_i18n( get_option('date_format'), $timestamp );

	return $date;

}


/*
 * Validate a PGN formatted date.
 *
 * @param  string $date  PGN date. (yyyy.mm.dd)
 * @return string $date  PGN date. (yyyy.mm.dd or ????.??.??)
 *
 * @since 1.1.9
 */
function chessgame_shizzle_validate_pgn_date( $date ) {

	$undefined_date = '????.??.??';

	if ( strlen( $date ) !== 10 ) {
		return $undefined_date;
	}

	$year  = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day   = substr($date, 8, 2);

	$datestring = $year . '-' . $month . '-' . $day;
	$timestamp = strtotime( $datestring );

	if ( $timestamp === false ) {
		return $undefined_date;
	}

	return $date;

}


/*
 * Cleanup the content, so it will not have any errors.
 * Used when saving and when displaying.
 *
 * Known are:
 * * line breaks ==> spaces
 * * Pattern: ... ==> ...
 *
 * @since 1.0.0
 */
function chessgame_shizzle_cleanup_pgn( $pgn ) {

	$pgn = trim( $pgn );

	$search = array( '&#8230;' ); // '&#8221;', '&#8220;'  ?
	$replace = array( '...' );
	$pgn = str_replace( $search, $replace, $pgn );

	// Remove and add newlines.
	$pgn = str_replace( array( "\r\n", "\n", "\r", '<br />', '<br>', '  ', '   ' ), ' ', $pgn );
	$pgn = preg_replace("/\]\[/", "]\n[", $pgn);
	$pgn = preg_replace("/\]\s\[/", "]\n[", $pgn);
	$pgn = preg_replace("/\]1/", "]\n\n1", $pgn);
	$pgn = preg_replace("/\]\s1/", "]\n\n1", $pgn); // Extra newline between headers and first move.
	$pgn = preg_replace("/\]\{/", "]\n\n{", $pgn);
	$pgn = preg_replace("/\]\s\{/", "]\n\n{", $pgn);

	return $pgn;

}


/*
 * Add fake pgn data to simple pgn data.
 * Can be needed, since simple pgn data is in the meta field, but PgnParser requires full pgn data for generating moves and fen data.
 *
 * @param  string $pgn  Simple or full PGN data.
 * @return string $fake_pgn  Full PGN data. Only fake data will be added if it wasn't already there.
 *
 * @since 1.2.3
 */
function chessgame_shizzle_add_fake_data_to_pgn( $pgn ) {

	if (strpos($pgn, '[') !== false) {
		return $pgn;
	}

	$fake_pgn = '
[Event "Event"]
[Site "Site"]
[Date "2015.09.06"]
[Round "1"]
[White "White Player"]
[Black "Black PLayer"]
[Result "0-1"]
[ECO "B20"]
[WhiteElo "2700"]
[BlackElo "2700"]
[PlyCount "146"]
[EventDate "2015.09.06"]

' . $pgn;

	return $fake_pgn;

}
