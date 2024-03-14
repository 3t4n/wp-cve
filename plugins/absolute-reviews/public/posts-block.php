<?php
/**
 * Posts Block
 *
 * @package    ABR
 * @subpackage ABR/public
 */

// when layout is not selected, used list.php
// but we don't need to print any html in this situation.
if ( ! isset( $attributes['layout'] ) || ! $attributes['layout'] ) {
	return;
}

$attributes['output']   = 'block';
$attributes['template'] = $attributes['layout'];

// Set classes.
$class_wrap = $attributes['canvasClassName'];

// Class Template.
$class_block = sprintf( 'abr-posts-template-%s', $attributes['layout'] );

// Class Number of Posts.
$class_block .= sprintf( ' abr-posts-per-page-%s', (int) $options['reviewsPostsCount'] );

if ( $posts->have_posts() ) {
	?>
	<div class="abr-block-reviews-posts <?php echo esc_attr( $class_wrap ); ?>">
		<div class="abr-reviews-posts <?php echo esc_attr( $class_block ); ?>">
			<div class="abr-reviews-posts-list">
				<?php
				$attributes['counter'] = 0;

				// Check if there're enough posts in the query.
				while ( $posts->have_posts() ) {
					$posts->the_post();

					$attributes['counter']++;

					$attributes['post_meta_list']    = abr_block_convert_post_meta( $options, 'reviews' );
					$attributes['post_meta_compact'] = isset( $options['reviewsMetaCompact'] ) ? $options['reviewsMetaCompact'] : false;
					$attributes['thumbnail']         = isset( $options['imageSize'] ) ? $options['imageSize'] : 'large';

					if ( in_array( $attributes['layout'], array( 'reviews-3', 'reviews-4', 'reviews-5' ), true ) ) {
						if ( 1 === $attributes['counter'] ) {
							$attributes['post_meta_list']    = abr_block_convert_post_meta( $options, 'reviewsLarge' );
							$attributes['post_meta_compact'] = isset( $options['reviewsLargeMetaCompact'] ) ? $options['reviewsLargeMetaCompact'] : false;
							$attributes['thumbnail']         = isset( $options['largeImageSize'] ) ? $options['largeImageSize'] : 'large';
						} else {
							$attributes['post_meta_list']    = abr_block_convert_post_meta( $options, 'reviewsSmall' );
							$attributes['post_meta_compact'] = isset( $options['reviewsSmallMetaCompact'] ) ? $options['reviewsSmallMetaCompact'] : false;
							$attributes['thumbnail']         = isset( $options['smallImageSize'] ) ? $options['smallImageSize'] : 'large';
						}
					}
					?>
					<div class="abr-post-item">
						<?php abr_reviews_posts_template( $posts, $attributes, null ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
} else {
	cnvs_alert_warning( esc_html__( 'There aren\'t enough posts that match the filter criteria.', 'authentic' ) );
}
