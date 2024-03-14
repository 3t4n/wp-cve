<?php


/*
 * Search form in shortcode function to show a simple list of chessgames.
 *
 * @since 1.2.6
 */
function get_chessgame_shizzle_simple_list_search( $atts ) {

	if (get_option( 'chessgame_shizzle-simple-list-search', 'true') !== 'true') {
		return;
	}

	$nonce        = wp_create_nonce( 'cs-simple-list-search-nonce' );
	$ajaxurl      = admin_url('admin-ajax.php');
	$defaulterror = esc_attr__('An unexpected error happened. If this happens more often, please contact a site administrator.', 'chessgame-shizzle');
	$searcherror  = esc_attr__('Please enter text to search for.', 'chessgame-shizzle');

	$cats = '';
	if ( ! empty( $atts['category'] ) ) {
		$cats = '<input type="hidden" value="' . esc_attr( $atts['category'] ) . '" name="cs-simple-list-search-cats" class="cs-simple-list-search-cats">
			';
	}
	$tags = '';
	if ( ! empty( $atts['tag'] ) ) {
		$tags = '<input type="hidden" value="' . esc_attr( $atts['tag'] ) . '" name="cs-simple-list-search-tags" class="cs-simple-list-search-tags">
			';
	}

	$form = '
		<div class="cs-simple-list-form-container">

			<form class="cs-simple-list-buttons" action="" method="POST" accept-charset="UTF-8">

				<input type="button"
					value="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
					title="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
					class="cs-simple-list-search-button">

			</form>

			<div class="cs-simple-list-search" style="display:none;">

				<form class="cs-simple-list-search" action="" method="POST" accept-charset="UTF-8">

					<input type="hidden" value="' . esc_attr( $nonce ) . '" name="cs-simple-list-search-nonce" class="cs-simple-list-search-nonce">
					<input type="hidden" value="' . esc_attr( $ajaxurl ) . '" name="cs-simple-list-search-ajaxurl" class="cs-simple-list-search-ajaxurl">
					<input type="hidden" value="' . esc_attr( $defaulterror ) . '" name="cs-simple-list-search-defaulterror" class="cs-simple-list-search-defaulterror">
					<input type="hidden" value="' . esc_attr( $searcherror ) . '" name="cs-simple-list-search-searcherror" class="cs-simple-list-search-searcherror">
					' . $cats . $tags . '

					<table>
						<tbody>

							<tr>
								<td colspan="2">
									<span class="cs-chessgame-description">
									' . esc_html__('Search for title and content of a chessgame in this list.', 'chessgame-shizzle' ) . '
									</span>
								</td>
							</tr>

							<tr>
								<td>
									<label class="cs-simple-list-search-text" for="cs-simple-list-search-text">
										<span class="cs-chessgame-title">' . esc_html__('Search', 'chessgame-shizzle' ) . '</span>
									</label>
								</td>
								<td>
									<input type="text" name="cs-simple-list-search-text" class="cs-simple-list-search-text" value="" required="required">
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="button"
										name="cs-simple-list-search-submit"
										value="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
										title="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
										class="cs-simple-list-search-submit">
								</td>
							</tr>

						</tbody>
					</table>
				</form>

				<div class="cs-simple-list-search-message" style="display:none;"></div>

			</div>

		</div>
		';

	return $form;

}


/*
 * Callback function for searching inside the simple list shortcode from the JavaScript in chessgame-shizzle-frontend.js.
 */
add_action( 'wp_ajax_chessgame_shizzle_simple_list_search', 'chessgame_shizzle_simple_list_search_callback' );
add_action( 'wp_ajax_nopriv_chessgame_shizzle_simple_list_search', 'chessgame_shizzle_simple_list_search_callback' );
function chessgame_shizzle_simple_list_search_callback() {

	if (get_option( 'chessgame_shizzle-simple-list-search', 'true') !== 'true') {
		die; // This is required to return a proper result.
	}

	$returndata = array();
	$returndata['cs_error'] = false;
	$returndata['cs_html'] = '';

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['cs-simple-list-search-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['cs-simple-list-search-nonce'], 'cs-simple-list-search-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		$returndata['cs_error'] = true;
		$returndata['cs_message'] = esc_html__('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$postdata = $_POST;

	if ( isset($postdata['cs-simple-list-search-text']) && strlen($postdata['cs-simple-list-search-text']) > 0 ) {
		$cs_chessgame_search = sanitize_text_field( $postdata['cs-simple-list-search-text'] );
	} else {
		$returndata['cs_error'] = true;
		$returndata['cs_message'] = esc_html__('Please enter text to search for.', 'chessgame-shizzle');
		echo json_encode( $returndata );
		die(); // This is required to return a proper result.
	}

	$tax_query = array();
	if ( ! empty( $postdata['cs-simple-list-search-cats'] ) ) {
		$cat_in = sanitize_text_field( $postdata['cs-simple-list-search-cats'] );
		$cat_in = explode( ',', $cat_in );
		$cat_in = array_map( 'absint', array_unique( (array) $cat_in ) );
		if ( ! empty( $cat_in ) ) {
			$tax_query['relation'] = 'OR';
			$tax_query[] = array(
				'taxonomy'         => 'cs_category',
				'terms'            => $cat_in,
				'field'            => 'term_id',
				'include_children' => true,
			);
		}
	}
	if ( ! empty( $postdata['cs-simple-list-search-tags'] ) ) {
		$tag_in = sanitize_text_field( $postdata['cs-simple-list-search-tags'] );
		$tag_in = explode( ',', $tag_in );
		$tag_in = array_map( 'absint', array_unique( (array) $tag_in ) );
		if ( ! empty( $tag_in ) ) {
			$tax_query['relation'] = 'OR';
			$tax_query[] = array(
				'taxonomy'         => 'cs_tag',
				'terms'            => $tag_in,
				'field'            => 'term_id',
				'include_children' => true,
			);
		}
	}

	$search_query = new WP_Query( array(
		'posts_per_page'         => -1,
		'nopaging'               => true,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'post_type'              => 'cs_chessgame',
		'ignore_sticky_posts'    => true,
		's'                      => $cs_chessgame_search,
		'tax_query'              => $tax_query,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );

	$html = '';
	if ( $search_query->have_posts() ) {

		while ( $search_query->have_posts() ) {
			$search_query->the_post();
			$html .= '
			<div class="cs-simple-list-item">
				<span class="cs-title">
					<a href="' . esc_attr( get_the_permalink() ) . '" title="' . esc_attr__('chessgame:', 'chessgame-shizzle') . ' ' . esc_attr( get_the_title() ) . '">' .
					get_the_title() .
					'</a>
				</span>
				&nbsp;
				<span class="cs-date">(' .
					esc_html__('played:', 'chessgame-shizzle' ) . ' ' . chessgame_shizzle_get_human_date( get_post_meta( get_the_ID(), 'cs_chessgame_datetime', true ) )
					. ')</span>
					&nbsp;
					<span class="cs-date">(' .
						esc_html__('published:', 'chessgame-shizzle' ) . ' ' . get_the_time( get_option('date_format') )
					. ')</span>
				</span>
			</div>
			';
		}

		// Reset $post before doing pagination, otherwise get_permalink() will show the last chessgame.
		wp_reset_postdata();

		// Add filter for the list, so devs can manipulate it.
		$returndata['cs_html'] = apply_filters( 'chessgame_shizzle_simple_list', $html );
		$returndata['cs_message'] = '';

	} else {

		$returndata['cs_error'] = true;
		$returndata['cs_message'] = esc_html__('No chessgame found with this title or content.', 'chessgame-shizzle');

	}

	echo json_encode( $returndata );
	die(); // This is required to return a proper result.

}
