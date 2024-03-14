<?php
/**
 * Portfolio Load More Posts via AJAX.
 *
 * @package sight
 */

/**
 * Processing data query for load more
 *
 * @param string $method Processing method $wp_query.
 * @param array  $data Data array.
 */
function sight_portfolio_load_more_query_data( $method = 'get', $data = array() ) {
	global $wp_query;

	$output = array();

	$vars = array(
		'in_the_loop',
		'is_single',
		'is_page',
		'is_archive',
		'is_author',
		'is_category',
		'is_tag',
		'is_tax',
		'is_home',
		'is_singular',
		'is_post_query',
	);

	if ( 'get' === $method ) {
		$output = $data;
	}

	foreach ( $vars as $variable ) {
		if ( ! isset( $wp_query->$variable ) ) {
			continue;
		}
		if ( 'get' === $method ) {
			$output[ $variable ] = $wp_query->$variable;
		}
		if ( ! isset( $data[ $variable ] ) ) {
			continue;
		}
		if ( 'init' === $method ) {
			$wp_query->$variable = $data[ $variable ];
		}
	}

	if ( 'get' === $method ) {
		$output = apply_filters( 'ajax_query_args', $output );
	}

	return wp_json_encode( $output );
}

/**
 * Get load more args.
 *
 * @param array $data       The data.
 * @param array $attributes The attributes.
 * @param array $options    The options.
 */
function sight_portfolio_get_load_more_args( $data, $attributes = false, $options = false ) {
	// Ajax Type.
	$ajax_type = version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ? 'ajax_restapi' : 'ajax';

	$ajax_type = apply_filters( 'ajax_load_more_method', $ajax_type );

	$args = array(
		'type'            => $ajax_type,
		'nonce'           => wp_create_nonce(),
		'url'             => admin_url( 'admin-ajax.php' ),
		'rest_url'        => esc_url( get_rest_url( null, '/sight/v1/portfolio-more-posts' ) ),
		'posts_per_page'  => get_query_var( 'posts_per_page' ),
		'query_data'      => sight_portfolio_load_more_query_data( 'get', $data ),
		'attributes'      => wp_json_encode( $attributes ),
		'options'         => wp_json_encode( $options ),
		'max_num_pages'   => $data['max_num_pages'],
		'pagination_type' => $data['pagination_type'],
		'translation'     => array(
			'load_more' => esc_html__( 'Load more', 'sight' ),
			'loading'   => esc_html__( 'Loading', 'sight' ),
		),
	);

	return $args;
}

/**
 * Fires after the query variable object is created, but before the actual query is run.
 *
 * @param object $wp_query WP Query.
 */
function sight_portfolio_pre_get_posts( &$wp_query ) {

	if ( isset( $wp_query->query['is_sight_query'] ) ) {
		$offset         = (int) $wp_query->get( 'offset' );
		$paged          = (int) $wp_query->get( 'paged' );
		$posts_per_page = (int) $wp_query->get( 'posts_per_page' );

		if ( $wp_query->is_paged ) {
			$page_offset = $offset + ( ( $paged - 1 ) * $posts_per_page );

			$wp_query->set( 'offset', $page_offset );
		} else {
			$wp_query->set( 'offset', $offset );
		}
	}
}
add_action( 'pre_get_posts', 'sight_portfolio_pre_get_posts', 1 );

/**
 * Filters the number of found posts for the query.
 *
 * @param int    $found_posts The number of posts found.
 * @param object $wp_query     WP Query.
 */
function sight_portfolio_found_posts( $found_posts, $wp_query ) {

	if ( isset( $wp_query->query['is_sight_query'] ) ) {

		$offset = isset( $wp_query->query['offset'] ) ? $wp_query->query['offset'] : 0;

		$found_posts = (int) $found_posts - (int) $offset;
	}

	return $found_posts;
}
add_filter( 'found_posts', 'sight_portfolio_found_posts', 1, 2 );

/**
 * Get More Posts
 */
function sight_portfolio_load_more_posts() {

	$posts_end = false;

	// Response default.
	$response = array(
		'page'           => 2,
		'posts_per_page' => 10,
		'query_data'     => array(),
	);

	if ( wp_doing_ajax() ) {
		check_ajax_referer();
	}

	// Set response values of ajax query.
	if ( isset( $_POST['page'] ) && $_POST['page'] ) { // Input var ok.
		$response['page'] = sanitize_key( $_POST['page'] ); // Input var ok; sanitization ok.
	}
	if ( isset( $_POST['posts_per_page'] ) && $_POST['posts_per_page'] ) { // Input var ok.
		$response['posts_per_page'] = sanitize_key( $_POST['posts_per_page'] ); // Input var ok; sanitization ok.
	}
	if ( isset( $_POST['query_data'] ) && $_POST['query_data'] ) { // Input var ok.
		$response['query_data'] = map_deep( json_decode( stripslashes( $_POST['query_data'] ), true ), 'sanitize_text_field' ); // Input var ok; sanitization ok.
	}
	if ( isset( $_POST['attributes'] ) && $_POST['attributes'] ) { // Input var ok.
		$response['attributes'] = map_deep( json_decode( stripslashes( $_POST['attributes'] ), true ), 'sanitize_text_field' ); // Input var ok; sanitization ok.
	}
	if ( isset( $_POST['options'] ) && $_POST['options'] ) { // Input var ok.
		$response['options'] = map_deep( json_decode( stripslashes( $_POST['options'] ), true ), 'sanitize_text_field' ); // Input var ok; sanitization ok.
	}

	// Set Query Vars.
	$query_vars = array_merge(
		(array) $response['query_data']['query_vars'],
		array(
			'is_sight_query' => true,
			'paged'          => (int) $response['page'],
			'posts_per_page' => (int) $response['posts_per_page'],
		)
	);

	// Supportfolio filtering for wp authors.
	if ( $response['query_data']['is_author'] && $query_vars['author'] ) {
		$query_vars['supportfolio_filters'] = true;
	}

	$attributes = $response['attributes'];
	$options    = $response['options'];

	// Get Posts.
	ob_start();

	if ( isset( $_POST['terms'] ) && $_POST['terms'] ) { // Input var ok.
		$terms = array_map( 'sanitize_text_field', $_POST['terms'] ); // Input var ok; sanitization ok.

		if ( $terms ) {
			$query_vars['tax_query'] = array();

			$query_vars['tax_query'][] = array(
				'taxonomy' => 'sight-categories',
				'field'    => 'slug',
				'terms'    => $terms,
			);

			$query_vars['tax_query']['relation'] = 'AND';
		}
	}

	$the_query = new WP_Query( $query_vars );

	$global_name = 'wp_query';

	$GLOBALS[ $global_name ] = $the_query;

	sight_portfolio_load_more_query_data( 'init', $response['query_data'] );

	if ( $the_query->have_posts() ) :

		// Set query vars, so that we can get them across all templates.
		set_query_var( 'sight_query', $response['query_data'] );

		// Get total number of posts.
		$total = $the_query->post_count;

		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			// Start counting posts.
			$current = $the_query->current_post + 1 + $query_vars['posts_per_page'] * $query_vars['paged'] - $query_vars['posts_per_page'];

			// Check End of posts.
			if ( $the_query->found_posts - $current <= 0 ) {
				$posts_end = true;
			}

			$portfolio_entry = new Sight_Entry( $attributes, $options );

			// Init portfolio entry.
			$portfolio_entry->init();

			// Get item project.
			require apply_filters( 'sight_portfolio_item_path', SIGHT_PATH . 'render/handler/portfolio-entry.php', $attributes, $options, $portfolio_entry );

		endwhile;

	endif;

	wp_reset_postdata();

	$content = ob_get_clean();

	if ( ! $content ) {
		$posts_end = true;
	}

	// Return Result.
	$result = array(
		'posts_end' => $posts_end,
		'content'   => $content,
	);

	return $result;
}

/**
 * AJAX Load More
 */
function sight_portfolio_ajax_load_more() {

	// Check Nonce.
	check_ajax_referer();

	// Get Posts.
	$data = sight_portfolio_load_more_posts();

	// Return Result.
	wp_send_json_success( $data );

}
add_action( 'wp_ajax_sight_portfolio_ajax_load_more', 'sight_portfolio_ajax_load_more' );
add_action( 'wp_ajax_nopriv_sight_portfolio_ajax_load_more', 'sight_portfolio_ajax_load_more' );


/**
 * More Posts API Response
 *
 * @param array $request REST API Request.
 */
function sight_portfolio_more_posts_restapi( $request ) {

	// Get Data.
	$data = array(
		'success' => true,
		'data'    => sight_portfolio_load_more_posts(),
	);

	// Return Result.
	return rest_ensure_response( $data );
}

/**
 * Register REST More Posts Routes
 */
function sight_portfolio_register_more_posts_route() {

	register_rest_route(
		'sight/v1',
		'/portfolio-more-posts',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'sight_portfolio_more_posts_restapi',
			'permission_callback' => function() {
				return true;
			},
		)
	);
}
add_action( 'rest_api_init', 'sight_portfolio_register_more_posts_route' );
