<?php
/*
 * Template Function for simple list of chessgames.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_simple_list( $atts ) {
	echo get_chessgame_shizzle_simple_list( $atts );
}


/*
 * Shortcode function to show a simple list of chessgames.
 *
 * @since 1.0.1
 */
function get_chessgame_shizzle_simple_list( $atts ) {

	/*
	 * Tax_query is supported since 1.2.2.
	 * 1.2.2: category and tag parameters for term_ids.
	 */
	$tax_query = array();
	if ( ! empty( $atts['category'] ) ) {
		$cat_in = explode( ',', $atts['category'] );
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
	if ( ! empty( $atts['tag'] ) ) {
		$tag_in = explode( ',', $atts['tag'] );
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


	/*
	 * Pagination is supported since 1.1.2.
	 */
	$pages_total = 1;
	$posts_per_page = 30;
	$the_query_total = new WP_Query(
		array(
			'post_status'            => 'publish',
			'post_type'              => 'cs_chessgame',
			'posts_per_page'         => -1,
			'nopaging'               => true,
			'fields'                 => 'ids',
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'tax_query'              => $tax_query,
		)
	);
	if ( ! empty( $the_query_total->post_count ) ) {
		$pages_total = $the_query_total->post_count;
		$pages_total = (int) ceil( $pages_total / $posts_per_page );
	}
	$pagenum = 1;
	if ( isset($_GET['pagenum']) && is_numeric($_GET['pagenum']) ) {
		$pagenum = (int) $_GET['pagenum'];
	}


	$the_query = new WP_Query(
		array(
			'post_status'            => 'publish',
			'post_type'              => 'cs_chessgame',
			'posts_per_page'         => $posts_per_page,
			'paged'                  => $pagenum,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => $tax_query,
		)
	);


	$boardtheme = chessgame_shizzle_get_boardtheme_class();
	$output = '
		<div class="cs-simple-list ' . $boardtheme . '">';

	$output .= get_chessgame_shizzle_simple_list_search( $atts );
	$output .= '
		<div class="cs-simple-list-items">';

	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$output .= '
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

	$pagination = chessgame_shizzle_simple_list_pagination( $pagenum, $pages_total );
	$output .= $pagination;

	$output .= '
		</div>
		</div>
	';

	// Add filter for the list, so devs can manipulate it.
	$output = apply_filters( 'chessgame_shizzle_simple_list', $output );

	return $output;

}
add_shortcode( 'chessgame_shizzle_simple_list', 'get_chessgame_shizzle_simple_list' );
