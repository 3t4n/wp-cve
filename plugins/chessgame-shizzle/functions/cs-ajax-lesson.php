<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Callback function for Filters for lessons that are generated from the JavaScript in chessgame-shizzle-frontend.js
 */
add_action( 'wp_ajax_chessgame_shizzle_lesson_ajax', 'chessgame_shizzle_lesson_ajax_callback' );
add_action( 'wp_ajax_nopriv_chessgame_shizzle_lesson_ajax', 'chessgame_shizzle_lesson_ajax_callback' );
function chessgame_shizzle_lesson_ajax_callback() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['cs-lesson-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['cs-lesson-nonce'], 'cs-lesson-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		$returndata = array();
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$postdata   = $_POST;
	$tax_query  = array();
	$meta_query = array();
	$returndata = array();

	/* offset counter */
	/* FIXME: remove after saving finished games and using the not_in parameter */
	if ( isset($postdata['cs-lesson-offset']) ) {
		$cs_chessgame_offset = (int) $postdata['cs-lesson-offset'];
	} else {
		$cs_chessgame_offset = 0;
	}

	/* Category select */
	if ( isset($postdata['cs-lesson-category']) && is_numeric($postdata['cs-lesson-category']) && $postdata['cs-lesson-category'] > 0 ) {
		$category = (int) $postdata['cs-lesson-category'];
		$tax_query[] = array(
			'taxonomy' => 'cs_category',
			'field'    => 'term_id',
			'terms'    => $category,
		);
	}

	/* Tag select */
	if ( isset($postdata['cs-lesson-tag']) && is_numeric($postdata['cs-lesson-tag']) && $postdata['cs-lesson-tag'] > 0 ) {
		$tag = (int) $postdata['cs-lesson-tag'];
		$tax_query[] = array(
			'taxonomy' => 'cs_tag',
			'field'    => 'term_id',
			'terms'    => $tag,
		);
	}

	/* ECO select */
	if ( isset($postdata['cs-lesson-eco']) && strlen($postdata['cs-lesson-eco']) === 3 ) {
		$eco = sanitize_text_field( $postdata['cs-lesson-eco'] );
		$meta_query[] = array(
			'key'   => 'cs_chessgame_code',
			'value' => $eco,
		);
	}

	/* Order by */
	$orderby = 'date';
	if ( isset($postdata['cs-lesson-orderby']) && strlen($postdata['cs-lesson-orderby']) > 1 ) {
		$orderby_options = array( 'date', 'modified', 'ID', 'rand' );
		$postdata_orderby = sanitize_text_field( $postdata['cs-lesson-orderby'] );
		if ( in_array( $postdata_orderby, $orderby_options, true ) ) {
			$orderby = $postdata_orderby;
		}
	}

	/* Order */
	$order = 'DESC';
	if ( isset($postdata['cs-lesson-order']) && strlen($postdata['cs-lesson-order']) > 2 ) {
		$order_options = array( 'DESC', 'ASC' );
		$postdata_order = sanitize_text_field( $postdata['cs-lesson-order'] );
		if ( in_array( $postdata_order, $order_options, true ) ) {
			$order = $postdata_order;
		}
	}

	/* puzzle checkbox */
	if ( isset($postdata['cs-lesson-puzzle']) ) {
		$meta_query[] = array(
			'key'   => 'cs_chessgame_puzzle',
			'value' => true,
		);

		/* level of puzzle (select) */
		if ( isset($postdata['cs-lesson-level']) ) {
			$level_options = array( 1, 2, 3, 4, 5 );
			$postdata_level = (int) $postdata['cs-lesson-level'];
			if ( in_array( $postdata_level, $level_options, true ) ) {
				$meta_query[] = array(
					'key'   => 'cs_chessgame_level',
					'value' => $postdata_level,
				);
			}
		}
	}

	$frist_post = new WP_Query( array(
		'posts_per_page'         => 1,
		'offset'                 => $cs_chessgame_offset,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'post_type'              => 'cs_chessgame',
		'ignore_sticky_posts'    => true,
		'orderby'                => $orderby,
		'order'                  => $order,
		'meta_query'             => $meta_query,
		'tax_query'              => $tax_query,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );

	if ( $frist_post->have_posts() ) {

		while ( $frist_post->have_posts() ) {
			$frist_post->the_post();
			$post_id = get_the_ID();
			$html = chessgame_shizzle_get_iframe_extended( $post_id, 'cs-lesson' );

			$returndata['cs_post_id'] = $post_id;
			$returndata['cs_message'] = '';
			$returndata['cs_html']    = $html;
		}

	} else {
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('No (next) chessgame found.', 'chessgame-shizzle');
	}

	echo json_encode( $returndata );
	die(); // This is required to return a proper result.

}


/*
 * Callback function for Post ID for lessons that are generated from the JavaScript in chessgame-shizzle-frontend.js
 */
add_action( 'wp_ajax_chessgame_shizzle_lesson_ajax_postid', 'chessgame_shizzle_lesson_ajax_postid_callback' );
add_action( 'wp_ajax_nopriv_chessgame_shizzle_lesson_ajax_postid', 'chessgame_shizzle_lesson_ajax_postid_callback' );
function chessgame_shizzle_lesson_ajax_postid_callback() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['cs-lesson-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['cs-lesson-nonce'], 'cs-lesson-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		$returndata = array();
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$postdata   = $_POST;
	$tax_query  = array();
	$meta_query = array();
	$returndata = array();

	if ( isset($postdata['cs-lesson-search-postid']) && is_numeric($postdata['cs-lesson-search-postid']) ) {
		$cs_chessgame_postid = (int) $postdata['cs-lesson-search-postid'];
	} else {
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('Please enter a numeric Chessgame ID.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$frist_post = new WP_Query( array(
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'post_type'              => 'cs_chessgame',
		'ignore_sticky_posts'    => true,
		'p'                      => $cs_chessgame_postid,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );

	if ( $frist_post->have_posts() ) {

		while ( $frist_post->have_posts() ) {
			$frist_post->the_post();
			$post_id = get_the_ID();
			$html = chessgame_shizzle_get_iframe_extended( $post_id, 'cs-lesson' );

			$returndata['cs_post_id'] = $post_id;
			$returndata['cs_message'] = '';
			$returndata['cs_html']    = $html;
		}

	} else {
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('No chessgame found with this ID.', 'chessgame-shizzle');
	}

	echo json_encode( $returndata );
	die(); // This is required to return a proper result.

}


/*
 * Callback function for Search for lessons that are generated from the JavaScript in chessgame-shizzle-frontend.js
 */
add_action( 'wp_ajax_chessgame_shizzle_lesson_ajax_search', 'chessgame_shizzle_lesson_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_chessgame_shizzle_lesson_ajax_search', 'chessgame_shizzle_lesson_ajax_search_callback' );
function chessgame_shizzle_lesson_ajax_search_callback() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['cs-lesson-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['cs-lesson-nonce'], 'cs-lesson-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		$returndata = array();
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$postdata   = $_POST;
	$tax_query  = array();
	$meta_query = array();
	$returndata = array();

	if ( isset($postdata['cs-lesson-search-text']) && strlen($postdata['cs-lesson-search-text']) > 0 ) {
		$cs_chessgame_search = sanitize_text_field( $postdata['cs-lesson-search-text'] );
	} else {
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('Please enter text to search for.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$frist_post = new WP_Query( array(
		'posts_per_page'         => 10,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'post_type'              => 'cs_chessgame',
		'ignore_sticky_posts'    => true,
		's'                      => $cs_chessgame_search,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );

	if ( $frist_post->have_posts() ) {

		$postcount = 0;
		$html = '<table><tbody>
					<tr>
						<th colspan="2">' . esc_html__('Click on a game to have it load.', 'chessgame-shizzle') . '</th>
					</tr>
				';
		while ( $frist_post->have_posts() ) {
			$frist_post->the_post();
			$post_id = get_the_ID();
			$cs_chessgame_datetime = get_post_meta($post_id, 'cs_chessgame_datetime', true);
			$cs_chessgame_datetime_human = chessgame_shizzle_get_human_date( $cs_chessgame_datetime );

			$html .= '
				<tr data-cs-postid="' . (int) $post_id . '" style="cursor:pointer;">
					<td class="cs-chessgame-datetime">' . esc_html( $cs_chessgame_datetime_human ) . '</td>
					<td class="cs-chessgame-title">' . esc_html( get_the_title() ) . '</td>
				</tr>
				';

			$postcount++;
		}
		$html .= '</table></tbody>';

		$returndata['cs_post_id'] = $postcount; // yes, it is not an ID.
		$returndata['cs_message'] = '';
		$returndata['cs_html']    = $html;

	} else {
		$returndata['cs_post_id'] = 0; // ID of 0 will mean an error happened.
		$returndata['cs_message'] = esc_html__('No chessgame found with this title or content.', 'chessgame-shizzle');
	}

	echo json_encode( $returndata );
	die(); // This is required to return a proper result.

}
