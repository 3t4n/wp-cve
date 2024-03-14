<?php
/*
 * Content filters for Chessgame frontend.
 * See admin/cs-meta-boxes.php for the admin.
 *
 * Filter is using custom fields:
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
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add player metadata to content.
 *
 * @param  string $content html content of the post.
 * @return string $content html content of the post together with player meta.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_content_filter_players( $content ) {
	$post_id = get_the_ID();
	$post_type = get_post_type();
	if ( $post_type !== 'cs_chessgame' || is_admin() || ! is_singular( 'cs_chessgame' ) ) {
		return $content;
	}

	$players = chessgame_shizzle_content_get_players( $content, $post_id );

	$content = $content . $players;

	return $content;
}
add_filter( 'the_content', 'chessgame_shizzle_content_filter_players', 12 );


/*
 * Get player metadata for a post.
 *
 * @param  string $content html content of the post.
 * @param  int    $post_id ID of the post.
 * @return string $content html player meta.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_content_get_players( $content, $post_id ) {

	$cs_chessgame_white_player = get_post_meta($post_id, 'cs_chessgame_white_player', true);
	$cs_chessgame_black_player = get_post_meta($post_id, 'cs_chessgame_black_player', true);
	$cs_chessgame_result = get_post_meta($post_id, 'cs_chessgame_result', true);
	$cs_chessgame_elo_white_player = (int) get_post_meta($post_id, 'cs_chessgame_elo_white_player', true);
	$cs_chessgame_elo_black_player = (int) get_post_meta($post_id, 'cs_chessgame_elo_black_player', true);

	if ( $cs_chessgame_elo_white_player > 0 ) {
		$cs_chessgame_white_player = $cs_chessgame_white_player . ' (' . $cs_chessgame_elo_white_player . ')';
	}
	if ( $cs_chessgame_elo_black_player > 0 ) {
		$cs_chessgame_black_player = $cs_chessgame_black_player . ' (' . $cs_chessgame_elo_black_player . ')';
	}

	$players = '
	<div class="cs-chessgame-players">
		<span class="cs-chessgame-player-names">
			<span class="cs-chessgame-player-white">
				<span class="cs-icon-color cs-white"></span><span class="cs-player-name"> ' . esc_html($cs_chessgame_white_player) . '</span>
			</span><br />
			<span class="cs-chessgame-player-black">
				<span class="cs-icon-color cs-black"></span><span class="cs-player-name"> ' . esc_html($cs_chessgame_black_player) . '</span>
			</span>
		</span><br />
		<span class="cs-chessgame-result">' .
			/* translators: result of the game */ esc_html__('Result:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_result)
	. '</span>
	</div>
	';

	return $players;
}


/*
 * Add general metadata to content.
 *
 * @param  string $content html content of the post.
 * @return string $content html content of the post with meta.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_content_filter_meta( $content ) {
	$post_id = get_the_ID();
	$post_type = get_post_type();
	if ( $post_type !== 'cs_chessgame' || is_admin() || ! is_singular( 'cs_chessgame' ) ) {
		return $content;
	}

	$meta = chessgame_shizzle_content_get_meta( $content, $post_id );

	$content = $content . $meta;

	return $content;
}
add_filter( 'the_content', 'chessgame_shizzle_content_filter_meta', 13 );


/*
 * Get metadata for a post.
 *
 * @param  string $content html content of the post.
 * @param  int    $post_id ID of the post.
 * @return string $content html meta.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_content_get_meta( $content, $post_id ) {

	$cs_chessgame_white_player = get_post_meta($post_id, 'cs_chessgame_white_player', true);
	$cs_chessgame_black_player = get_post_meta($post_id, 'cs_chessgame_black_player', true);
	$cs_chessgame_datetime = get_post_meta($post_id, 'cs_chessgame_datetime', true);
	$cs_chessgame_datetime_human = chessgame_shizzle_get_human_date( $cs_chessgame_datetime );
	$cs_chessgame_location = get_post_meta($post_id, 'cs_chessgame_location', true);
	$cs_chessgame_tournament = get_post_meta($post_id, 'cs_chessgame_tournament', true);
	$cs_chessgame_round = get_post_meta($post_id, 'cs_chessgame_round', true);
	$cs_chessgame_submitter = get_post_meta($post_id, 'cs_chessgame_submitter', true);
	$cs_chessgame_published = get_the_time( get_option('date_format'), $post_id );

	$cs_chessgame_code = get_post_meta($post_id, 'cs_chessgame_code', true);
	$codes = chessgame_shizzle_get_array_openingcodes();
	$code = '';
	if ( isset($codes["$cs_chessgame_code"]) ) {
		$code = $codes["$cs_chessgame_code"];
	}

	$blog_url = esc_attr( get_bloginfo('wpurl') );
	$tag_text = '';
	$tags = get_the_terms( get_the_ID(), 'cs_tag' );
	if ( $tags && ! is_wp_error( $tags ) ) {
		$tag_links = array();
		foreach ( $tags as $tag ) {
			$tag_links[] = '<a href="' . esc_attr( $blog_url ) . '/cs_tag/' . esc_attr($tag->slug) . '/">' . esc_attr($tag->name) . '</a>';
		}
		$tag_links = join( ', ', $tag_links );
		$tag_text = '
		<span class="cs_chessgame_tag">' .
			esc_html__( 'Tags:', 'chessgame-shizzle' ) . ' ' . $tag_links
		. '</span><br />';
	}

	$cat_text = '';
	$cats = get_the_terms( get_the_ID(), 'cs_category' );
	if ( $cats && ! is_wp_error( $cats ) ) {
		$cat_links = array();
		foreach ( $cats as $cat ) {
			$cat_links[] = '<a href="' . esc_attr( $blog_url ) . '/cs_category/' . esc_attr($cat->slug) . '/">' . esc_attr($cat->name) . '</a>';
		}
		$cat_links = join( ', ', $cat_links );
		$cat_text = '
		<span class="cs_chessgame_category">' .
			esc_html__( 'Categories:', 'chessgame-shizzle' ) . ' ' . $cat_links
		. '</span><br />';
	}

	$filename = esc_html($cs_chessgame_datetime) . ' - ' . esc_html($cs_chessgame_white_player) . ' - ' . esc_html($cs_chessgame_black_player);

	$pgntext_for_export = get_post_meta($post_id, 'cs_chessgame_pgn', true);
	$pgntext_for_export = chessgame_shizzle_sanitize_pgn( $pgntext_for_export );
	$pgntext_for_export = chessgame_shizzle_update_pgn_from_meta( $pgntext_for_export, $post_id );

	$meta = '
	<div class="cs_chessgame_meta">
		<span class="cs_chessgame_meta_header">' .
		esc_html__('Metadata &raquo;', 'chessgame-shizzle' ) . '<span class="screen-reader-text"> ' . esc_html__('Click to open.', 'chessgame-shizzle') . '</span>' .
		'</span><br />
		<div class="cs_chessgame_meta_inside" style="display:none;">
			<span class="cs_chessgame_datetime">' .
				esc_html__('Date:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_datetime_human) .
			'<br /></span>
			<span class="cs_chessgame_location">' .
				esc_html__('Location:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_location) .
			'<br /></span>
			<span class="cs_chessgame_tournament">' .
				esc_html__('Tournament:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_tournament) .
			'<br /></span>
			<span class="cs_chessgame_round">' .
				esc_html__('Round:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_round) .
			'<br /></span>
			<span class="cs_chessgame_code">' .
				esc_html__('Opening:', 'chessgame-shizzle' ) . ' ' . esc_html($code) .
			'<br /></span>
			<span class="cs_chessgame_submitter">' .
				esc_html__('Submitted by:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_submitter) .
			'<br /></span>
			<span class="cs_chessgame_published">' .
				/* translators: on a certain date */ esc_html__('Published on:', 'chessgame-shizzle' ) . ' ' . esc_html($cs_chessgame_published) .
			'<br /></span>
			' . $tag_text . $cat_text . '
			<div class="cs-chessgame-button-row">
				<div class="cs-chessgame-download">
					<input type="button" name="cs-chessgame-download-pgn" id="cs-chessgame-download-pgn" class="btn button" value="' . esc_attr__('Download PGN file', 'chessgame-shizzle') . '" />
					<a style="display:none;" href="" id="cs-chessgame-download-pgn-link" download="' . chessgame_shizzle_truncate_slug( $filename ) . '.pgn"></a>
					<textarea style="display: none;" id="pgnText_for_export">' . esc_textarea($pgntext_for_export) . '</textarea>
				</div>
				<div class="cs-chessgame-fen">
					<div><input type="button" name="cs-chessgame-gen-fen" id="cs-chessgame-gen-fen" class="btn button" value="' . esc_attr__('Show FEN code', 'chessgame-shizzle') . '" /></div>
					<div style="display:none;" class="cs-chessgame-show-fen">
						<input value="" type="text" name="cs-chessgame-show-fen" id="cs-chessgame-show-fen" />
					</div>
				</div>
			';

	// Only when GD is supported.
	if ( function_exists('gd_info') ) {
		$meta .= '
				<div class="cs-chessgame-fen-image">
					<input type="button" name="cs-chessgame-fen-image" id="cs-chessgame-fen-image" class="btn button" value="' . esc_attr__('Create image from position', 'chessgame-shizzle') . '" />
				</div>
				<div class="cs-chessgame-remove-fen-image" style="display:none;">
					<div><input type="button" name="cs-chessgame-remove-fen-image" id="cs-chessgame-remove-fen-image" class="btn button" value="' . esc_attr__('Remove image again', 'chessgame-shizzle') . '" /></div>
					<div><img class="cs-chessgame-fen-image-png" src=""></div>
				</div>
			';
	}

	$meta .= '
			</div>
		</div>
	</div>
	';

	return $meta;
}


/*
 * Add pgn metadata to content.
 *
 * @param  string $content html content of the post.
 * @return string $content html content of the post with pgn data.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_content_filter_pgn( $content ) {
	$post_id = get_the_ID();
	$post_type = get_post_type();
	if ( $post_type !== 'cs_chessgame' || is_admin() || ! is_singular( 'cs_chessgame' ) ) {
		return $content;
	}
	/*$locale = get_locale();
	$locale = substr( $locale, 0, 2 );
	if ( $locale != 'de' && $locale != 'fr' ) { $locale = 'en'; } */

	$pgn = chessgame_shizzle_content_get_pgn( $content, $post_id );

	chessgame_shizzle_pgn4web_enqueue();

	$content = $content . $pgn;

	return $content;
}
add_filter( 'the_content', 'chessgame_shizzle_content_filter_pgn', 14 );


/*
 * Get pgn metadata for a post.
 *
 * @param  string $content html content of the post.
 * @param  int    $post_id ID of the post.
 * @return string $content html pgn data.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_content_get_pgn( $content, $post_id ) {

	$class_div = '';
	$cs_chessgame_pgn = get_post_meta($post_id, 'cs_chessgame_pgn', true);
	$cs_chessgame_pgn = chessgame_shizzle_sanitize_pgn( $cs_chessgame_pgn );
	$cs_chessgame_puzzle = get_post_meta($post_id, 'cs_chessgame_puzzle', true);
	if ( $cs_chessgame_puzzle ) {
		$class_div .= ' cs-puzzle';
	}
	$class_div .= ' ' . chessgame_shizzle_get_boardtheme_class();

	/* pgn4web.js */
	$pgn = '';
	if ( ! empty( $cs_chessgame_pgn ) ) {
		$pgn = '
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
		';
	}

	return $pgn;
}
