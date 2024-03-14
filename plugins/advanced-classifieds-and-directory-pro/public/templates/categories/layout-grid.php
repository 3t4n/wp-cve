<?php

/**
 * Layout Grid.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$columns = (int) $attributes['columns'];

$responsive_classes = array( 'md:acadp-grid-cols-' . $columns );
if ( $columns > 2 ) $responsive_classes[] = 'sm:acadp-grid-cols-3';
if ( $columns > 1 ) $responsive_classes[] = 'xs:acadp-grid-cols-2';
?>

<div class="acadp acadp-categories acadp-layout-grid">
	<div class="acadp-grid acadp-grid-cols-1 acadp-gap-6 <?php echo implode( ' ', $responsive_classes ); ?>">
		<?php foreach ( $terms as $term ) :	
			$count = 0;
			if ( ! empty( $attributes['hide_empty'] ) || ! empty( $attributes['show_count'] ) ) {
				$count = (int) acadp_get_listings_count_by_category( $term->term_id, $attributes['pad_counts'] );				
				if ( ! empty( $attributes['hide_empty'] ) && 0 == $count ) continue;
			} 

			$category_url = acadp_get_category_page_link( $term );					
			?>
			<div class="acadp-card">
				<?php
				// Image
				$image = '';
				
				if ( $image_id = get_term_meta( $term->term_id, 'image', true ) ) {
					$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
					if ( $image_attributes ) $image = $image_attributes[0];
				}

				if ( empty( $image ) ) {
					$image = ACADP_PLUGIN_IMAGE_PLACEHOLDER;
				}
				?>					
				<div class="acadp-image">
					<a href="<?php echo esc_url( $category_url ); ?>" class="acadp-block acadp-leading-none">
						<img src="<?php echo esc_url( $image ); ?>" class="acadp-w-full acadp-aspect-video acadp-object-cover acadp-rounded-t" alt="" />
					</a>
				</div>

				<div class="acadp-content acadp-flex acadp-flex-col acadp-gap-1 acadp-p-4">  
					<div class="acadp-title">
						<a href="<?php echo esc_url( $category_url ); ?>" class="acadp-text-lg">
							<?php echo esc_html( $term->name ); ?>
						</a>
					</div>

					<?php if ( ! empty( $attributes['show_count'] ) ) :	?>
						<div class="acadp-listings-count acadp-text-muted">
							<?php 
							printf( 
								_n( '%d Listing', '%d Listings', $count, 'advanced-classifieds-and-directory-pro' ), 
								$count 
							)
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>	
		<?php endforeach; ?>	
	</div>
</div>

<?php 
// Share buttons
include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/share-buttons.php' );