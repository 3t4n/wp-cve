<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Main div for tactic Lessons for admin page and for frontend shortcode.
 *
 * @return string $html html with a chessgame included togetrher with filter and search forms.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_get_lesson() {

	$frist_post = new WP_Query( array(
		'posts_per_page'      => 1,
		'offset'              => 0,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'post_type'           => 'cs_chessgame',
		'ignore_sticky_posts' => true,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'meta_query'          => array(
			array(
				'key'   => 'cs_chessgame_puzzle',
				'value' => true,
			),
		),
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) );

	$html = '
		<div class="cs-chessgame-lesson cs-lesson">
			';
	$html .= chessgame_shizzle_get_form_filters();

	if ( $frist_post->have_posts() ) {
		while ( $frist_post->have_posts() ) {
			$frist_post->the_post();
			$post_id = get_the_ID();
			$html .= '<div class="cs-single-lesson-container">
				';
			$html .= chessgame_shizzle_get_iframe_extended( $post_id, 'cs-lesson' );
			$html .= '</div>
				';
		}
	}

	$html .= '
		</div>
		';

	chessgame_shizzle_pgn4web_register();
	chessgame_shizzle_pgn4web_enqueue();
	wp_reset_postdata();

	return $html;

}


/*
 * Get form filters for the lesson.
 *
 * @return string $form html with extra form section for a tactics lesson.
 *
 * @since 1.1.9
 */
function chessgame_shizzle_get_form_filters() {

	$nonce        = wp_create_nonce( 'cs-lesson-nonce' );
	$ajaxurl      = admin_url('admin-ajax.php');
	$defaulterror = esc_attr__('An unexpected error happened. If this happens more often, please contact a site administrator.', 'chessgame-shizzle');
	$postiderror  = esc_attr__('Please enter a numeric Chessgame ID.', 'chessgame-shizzle');
	$searcherror  = esc_attr__('Please enter text to search for.', 'chessgame-shizzle');
	$eco_dropdown = chessgame_shizzle_get_dropdown_openingcodes( '', 'cs-lesson-eco' );

	$category_args = array(
		'show_option_all'   => '',
		'show_option_none'  => esc_html__( 'Select...', 'chessgame-shizzle'),
		'orderby'           => 'name',
		'order'             => 'DESC',
		'show_count'        => 1,
		'hide_empty'        => 1,
		'child_of'          => 0,
		'exclude'           => '',
		'echo'              => 0,
		'selected'          => 0,
		'hierarchical'      => 0,
		'name'              => 'cs-lesson-category',
		'id'                => '',
		'class'             => 'cs-lesson-category',
		'depth'             => 0,
		'tab_index'         => 0,
		'taxonomy'          => 'cs_category',
		'hide_if_empty'     => true,
		'option_none_value' => 0,
		'value_field'       => 'term_id',
		'required'          => false,
	);
	$category_dropdown = wp_dropdown_categories( $category_args );
	if ( strlen($category_dropdown) === 0 ) {
		$category_dropdown = esc_html__( 'No Categories found', 'chessgame-shizzle');
	}

	$tag_args = array(
		'show_option_all'   => '',
		'show_option_none'  => esc_html__( 'Select...', 'chessgame-shizzle'),
		'orderby'           => 'name',
		'order'             => 'DESC',
		'show_count'        => 1,
		'hide_empty'        => 1,
		'child_of'          => 0,
		'exclude'           => '',
		'echo'              => 0,
		'selected'          => 0,
		'hierarchical'      => 0,
		'name'              => 'cs-lesson-tag',
		'id'                => '',
		'class'             => 'cs-lesson-tag',
		'depth'             => 0,
		'tab_index'         => 0,
		'taxonomy'          => 'cs_tag',
		'hide_if_empty'     => true,
		'option_none_value' => 0,
		'value_field'       => 'term_id',
		'required'          => false,
	);
	$tag_dropdown = wp_dropdown_categories( $tag_args );
	if ( strlen($tag_dropdown) === 0 ) {
		$tag_dropdown = esc_html__( 'No Tags found', 'chessgame-shizzle');
	}

	$form = '
		<div class="cs-lesson-form-container">

			<form class="cs-lesson-buttons" action="" method="POST" accept-charset="UTF-8">

				<input type="button"
					value="' . esc_attr__( 'Next Game', 'chessgame-shizzle' ) . '"
					title="' . esc_attr__( 'Load the next game', 'chessgame-shizzle' ) . '"
					class="cs-next-game">

				<input type="button"
					value="' . esc_attr__( 'Filters', 'chessgame-shizzle' ) . '"
					title="' . esc_attr__( 'Filters', 'chessgame-shizzle' ) . '"
					class="cs-lesson-filters-button">

				<input type="button"
					value="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
					title="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
					class="cs-lesson-search-button">

			</form>

			<form class="cs-lesson-filters" action="" method="POST" accept-charset="UTF-8" style="display:none;">

				<input type="hidden" value="1" name="cs-lesson-offset" class="cs-lesson-offset">
				<input type="hidden" value="' . esc_attr( $nonce ) . '" name="cs-lesson-nonce" class="cs-lesson-nonce">
				<input type="hidden" value="' . esc_attr( $ajaxurl ) . '" name="cs-lesson-ajaxurl" class="cs-lesson-ajaxurl">
				<input type="hidden" value="' . esc_attr( $defaulterror ) . '" name="cs-lesson-defaulterror" class="cs-lesson-defaulterror">

				<table>
					<tbody>

						<tr>
							<td>
								<label class="cs-lesson-category" for="cs-lesson-category">
									<span class="cs-chessgame-title">' . esc_html__('Category', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								' . $category_dropdown . '
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-tag" for="cs-lesson-tag">
									<span class="cs-chessgame-title">' . esc_html__('Tag', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								' . $tag_dropdown . '
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-eco" for="cs-lesson-eco">
									<span class="cs-chessgame-title">' . esc_html__('Opening code', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								' . $eco_dropdown . '
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-orderby" for="cs-lesson-orderby">
									<span class="cs-chessgame-title">' . esc_html__('Order by', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								<select class="cs-lesson-orderby" name="cs-lesson-orderby" data-placeholder="' . esc_attr__('Select...', 'chessgame-shizzle' ) . '">
									<option value="date" selected="selected">' . esc_html__('Date published (default)', 'chessgame-shizzle') . '</option>
									<option value="modified">' . esc_html__('Date modified', 'chessgame-shizzle') . '</option>
									<option value="ID">' . esc_html__('ID of chessgame', 'chessgame-shizzle') . '</option>
									<option value="rand">' . esc_html__('Random', 'chessgame-shizzle') . '</option>
								</select>
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-order" for="cs-lesson-order">
									<span class="cs-chessgame-title">' . esc_html__('Sort Order', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								<select class="cs-lesson-order" name="cs-lesson-order" data-placeholder="' . esc_attr__('Select...', 'chessgame-shizzle' ) . '">
									<option value="DESC" selected="selected">' . esc_html__('Descending (default)', 'chessgame-shizzle') . '</option>
									<option value="ASC">' . esc_html__('Ascending', 'chessgame-shizzle') . '</option>
								</select>
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-puzzle" for="cs-lesson-puzzle">
									<span class="cs-chessgame-title">' . esc_html__('Puzzle', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								<input type="checkbox" name="cs-lesson-puzzle" class="cs-lesson-puzzle" checked="checked">
							</td>
						</tr>

						<tr>
							<td>
								<label class="cs-lesson-level" for="cs-lesson-level">
									<span class="cs-chessgame-title">' . esc_html__('Level', 'chessgame-shizzle' ) . '</span>
								</label>
							</td>
							<td>
								<select class="cs-lesson-level" name="cs-lesson-level" data-placeholder="' . esc_attr__('Level of the puzzle...', 'chessgame-shizzle' ) . '">
									<option value="">'  . esc_html__('Level of the puzzle...', 'chessgame-shizzle') . '</option>
									<option value="1">' . esc_html__('More Easy', 'chessgame-shizzle' ) . '</option>
									<option value="2">' . esc_html__('Easy', 'chessgame-shizzle' ) . '</option>
									<option value="3">' . esc_html__('Standard', 'chessgame-shizzle' ) . '</option>
									<option value="4">' . esc_html__('Difficult', 'chessgame-shizzle' ) . '</option>
									<option value="5">' . esc_html__('More Difficult', 'chessgame-shizzle' ) . '</option>
								</select>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<input type="button"
									name="cs-lesson-submit"
									value="' . esc_attr__( 'Apply filters and load new game', 'chessgame-shizzle' ) . '"
									title="' . esc_attr__( 'Apply filters and load new game', 'chessgame-shizzle' ) . '"
									class="cs-lesson-filters-apply">
							</td>
						</tr>

					</tbody>
				</table>

			</form>

			<div class="cs-lesson-search" style="display:none;">

				<form class="cs-lesson-postid" action="" method="POST" accept-charset="UTF-8">

					<input type="hidden" value="' . esc_attr( $nonce ) . '" name="cs-lesson-nonce" class="cs-lesson-nonce">
					<input type="hidden" value="' . esc_attr( $ajaxurl ) . '" name="cs-lesson-ajaxurl" class="cs-lesson-ajaxurl">
					<input type="hidden" value="' . esc_attr( $defaulterror ) . '" name="cs-lesson-defaulterror" class="cs-lesson-defaulterror">
					<input type="hidden" value="' . esc_attr( $postiderror ) . '" name="cs-lesson-postiderror" class="cs-lesson-postiderror">

					<table>
						<tbody>

							<tr>
								<td>
									<label class="cs-lesson-search-postid" for="cs-lesson-search-postid">
										<span class="cs-chessgame-title">' . esc_html__('Chessgame ID', 'chessgame-shizzle' ) . '</span>
									</label>
								</td>
								<td>
									<input type="text" value="" name="cs-lesson-search-postid" class="cs-lesson-search-postid" required="required">
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="button"
										name="cs-lesson-show-postid"
										value="' . esc_attr__( 'Show Game', 'chessgame-shizzle' ) . '"
										title="' . esc_attr__( 'Show Game', 'chessgame-shizzle' ) . '"
										class="cs-lesson-show-postid">
								</td>
							</tr>

						</tbody>
					</table>
				</form>

				<form class="cs-lesson-search" action="" method="POST" accept-charset="UTF-8">

					<input type="hidden" value="' . esc_attr( $nonce ) . '" name="cs-lesson-nonce" class="cs-lesson-nonce">
					<input type="hidden" value="' . esc_attr( $ajaxurl ) . '" name="cs-lesson-ajaxurl" class="cs-lesson-ajaxurl">
					<input type="hidden" value="' . esc_attr( $defaulterror ) . '" name="cs-lesson-defaulterror" class="cs-lesson-defaulterror">
					<input type="hidden" value="' . esc_attr( $searcherror ) . '" name="cs-lesson-searcherror" class="cs-lesson-searcherror">

					<table>
						<tbody>

							<tr>
								<td colspan="2">
									<span class="cs-chessgame-description">
									' . esc_html__('Search for title and content of a chessgame.', 'chessgame-shizzle' ) . '
									</span>
								</td>
							</tr>

							<tr>
								<td>
									<label class="cs-lesson-search-text" for="cs-lesson-search-text">
										<span class="cs-chessgame-title">' . esc_html__('Search', 'chessgame-shizzle' ) . '</span>
									</label>
								</td>
								<td>
									<input type="text" name="cs-lesson-search-text" class="cs-lesson-search-text" value="" required="required">
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="button"
										name="cs-lesson-search-submit"
										value="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
										title="' . esc_attr__( 'Search', 'chessgame-shizzle' ) . '"
										class="cs-lesson-search-submit">

									<input type="button"
										name="cs-lesson-search-clear"
										value="' . esc_attr__( 'Clear', 'chessgame-shizzle' ) . '"
										title="' . esc_attr__( 'Clear', 'chessgame-shizzle' ) . '"
										class="cs-lesson-search-clear">
								</td>
							</tr>

							<tr>
								<td colspan="2" class="cs-chessgame-search-results">
								</td>
							</tr>

						</tbody>
					</table>
				</form>

			</div>

			<div class="cs-lesson-message"></div>

		</div>
		';

	return $form;

}
