<?php
/**
 * Portfolio Post
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

if ( $attributes['custom_post'] ) {
	$attachments = get_attached_media( 'image', $attributes['custom_post'] );

	if ( $attachments && count( $attachments ) > $attributes['number_items'] ) {
		$attachments = array_slice( $attachments, 0, $attributes['number_items'] );
	}
}

if ( isset( $attachments ) && $attachments ) {
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>">

		<div class="sight-portfolio-area__outer">
			<div class="sight-portfolio-area__main" <?php sight_portfolio_area_main_attrs( $attributes, $options ); ?>>
				<?php
				// Start the Loop.
				foreach ( $attachments as $attachment ) {

					// Get item project.
					if ( wp_get_attachment_image( $attachment->ID ) ) {

						$portfolio_entry = new Sight_Entry( $attributes, $options );

						// Set settings.
						$portfolio_entry->attachment_id = $attachment->ID;

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
