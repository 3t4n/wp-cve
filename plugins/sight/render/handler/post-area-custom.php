<?php
/**
 * Portfolio Custom
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

if ( $attributes['custom_images'] && count( $attributes['custom_images'] ) > $attributes['number_items'] ) {
	$attributes['custom_images'] = array_slice( $attributes['custom_images'], 0, $attributes['number_items'] );
}

if ( $attributes['custom_images'] ) {
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>">

		<div class="sight-portfolio-area__outer">
			<div class="sight-portfolio-area__main" <?php sight_portfolio_area_main_attrs( $attributes, $options ); ?>>
				<?php
				// Start the Loop.
				foreach ( $attributes['custom_images'] as $attachment_id ) {

					// Get item project.
					if ( wp_get_attachment_image( $attachment_id ) ) {

						$portfolio_entry = new Sight_Entry( $attributes, $options );

						// Set settings.
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
