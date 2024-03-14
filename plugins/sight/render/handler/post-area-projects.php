<?php
/**
 * Portfolio Projects
 *
 * @var        $attributes - attributes
 * @var        $options    - options
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

$class_name = sight_portfolio_area_classes( $attributes, $options );

// Args Query.
$args_query = array(
	'post_type'           => $attributes['projects_filter_post_type'],
	'ignore_sticky_posts' => true,
	'posts_per_page'      => $attributes['number_items'],
);

// Filter Categories.
if ( 'sight-projects' === $attributes['projects_filter_post_type'] ) {

	if ( isset( $attributes['projects_filter_categories'] ) ) {
		$filter_categories = $attributes['projects_filter_categories'];

		if ( $filter_categories ) {
			$args_query['tax_query'][] = array(
				'taxonomy' => 'sight-categories',
				'field'    => 'id',
				'terms'    => $filter_categories,
			);

			$args_query['tax_query']['relation'] = 'OR';
		}
	}
}

// Filter Offset.
if ( isset( $attributes['projects_filter_offset'] ) ) {
	$args_query['offset'] = (int) $attributes['projects_filter_offset'];
}

// Filter Orderby.
if ( $attributes['projects_orderby'] ) {
	$type_post_views = sight_post_views_enabled();
	// Order by Views.
	if ( $type_post_views && 'views' === $attributes['projects_orderby'] ) {
		$args_query['orderby'] = $type_post_views;
		// Don't hide posts without views.
		$args_query['views_query']['hide_empty'] = false;

	} else {
		$args_query['orderby'] = $attributes['projects_orderby'];
	}
}

// Filter Order.
if ( $attributes['projects_order'] ) {
	$args_query['order'] = $attributes['projects_order'];
}

/** ---------------------------------- */
/** ---------------------------------- */

// WP Query.
$portfolio_list = new WP_Query( $args_query );

// WP Query Data.
if ( isset( $options['pagination_type'] ) ) {
	$portfolio_list->pagination_type = $options['pagination_type'];
} else {
	$portfolio_list->pagination_type = 'none';
}

// Theme data.
$data = array(
	'is_sight_query'  => true,
	'max_num_pages'   => $portfolio_list->max_num_pages,
	'pagination_type' => $portfolio_list->pagination_type,
	'query_vars'      => $portfolio_list->query,
);

$args = sight_portfolio_get_load_more_args( $data, $attributes, $options );

// Set posts per page.
$args['posts_per_page'] = $args_query['posts_per_page'];

$query_data = sight_encode_data( $args );

if ( $portfolio_list->have_posts() ) {
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>" data-items-area="<?php echo esc_attr( $query_data ); ?>">

		<?php if ( isset( $options['filter_items'] ) && $options['filter_items'] ) { ?>
			<div class="sight-portfolio-area-filter">
				<div class="sight-portfolio-area-filter__title"><?php esc_html_e( 'Categories', 'sight' ); ?></div>

				<ul class="sight-portfolio-area-filter__list">

					<li class="sight-portfolio-area-filter__list-item sight-filter-all sight-filter-active">
						<a href="#" data-filter="*">
							<?php esc_html_e( 'All', 'sight' ); ?>
						</a>
					</li>

					<?php
					$categories = get_terms(
						array(
							'taxonomy'   => 'sight-categories',
							'hide_empty' => false,
							'include'    => isset( $filter_categories ) && $filter_categories ? $filter_categories : array(),
						)
					);

					foreach ( $categories as $category ) {
						?>
							<li class="sight-portfolio-area-filter__list-item">
								<a href="#" data-filter="<?php echo esc_attr( $category->slug ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							</li>
						<?php
					}
					?>
				</ul>
			</div>
		<?php } ?>

		<div class="sight-portfolio-area__outer">
			<div class="sight-portfolio-area__main" <?php sight_portfolio_area_main_attrs( $attributes, $options ); ?>>
				<?php
				// Start the Loop.
				while ( $portfolio_list->have_posts() ) {
					$portfolio_list->the_post();

					$portfolio_entry = new Sight_Entry( $attributes, $options );

					// Init portfolio entry.
					$portfolio_entry->init();

					// Get item project.
					require apply_filters( 'sight_portfolio_item_path', SIGHT_PATH . 'render/handler/portfolio-entry.php', $attributes, $options, $portfolio_entry );
				}
				?>
			</div>
		</div>
	</div>
	<?php
} else {
	require SIGHT_PATH . 'render/handler/post-area-none.php';
}

wp_reset_postdata();
