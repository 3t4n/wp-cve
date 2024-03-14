<?php
/**
 * Portfolio Categories
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

$categories = get_terms(
	array(
		'taxonomy'   => 'sight-categories',
		'hide_empty' => false,
		'include'    => isset( $attributes['categories_filter_ids'] ) ? $attributes['categories_filter_ids'] : '',
		'orderby'    => isset( $attributes['categories_orderby'] ) ? $attributes['categories_orderby'] : 'name',
		'order'      => isset( $attributes['categories_order'] ) ? $attributes['categories_order'] : 'ASC',
		'number'     => isset( $attributes['number_items'] ) ? $attributes['number_items'] : null,
	)
);

if ( $categories ) {
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>">

		<div class="sight-portfolio-area__outer">
			<div class="sight-portfolio-area__main" <?php sight_portfolio_area_main_attrs( $attributes, $options ); ?>>
				<?php
				// Start the Loop.
				foreach ( $categories as $category ) {

					$attachment_id = get_term_meta( $category->term_id, 'sight_featured_image', true );

					// Get item project.
					if ( wp_get_attachment_image( $attachment_id ) ) {

						$portfolio_entry = new Sight_Entry( $attributes, $options );

						// Set settings.
						$portfolio_entry->object_id     = $category->term_id;
						$portfolio_entry->attachment_id = $attachment_id;

						// Init portfolio entry.
						$portfolio_entry->init();

						require apply_filters( 'sight_portfolio_item_path', SIGHT_PATH . 'render/handler/portfolio-entry.php', $attributes, $options, $portfolio_entry );
					}
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
