<?php
/**
 * Easy Video Reviews - Ask for review
 * Ask for review
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<div class="notice notice-info is-dismissible evr-notice" data-evrnotice="review">
	<div class="evr-notice-text">
		<span class="dashicons dashicons-heart"></span>
		<?php esc_html__( 'Loving Easy Video Reviews? Leave a 5 star rating to help us growing', 'easy-video-reviews' ); ?>
	</div>
	<div class="evr-notice-buttons">
		<a href="https://wordpress.org/support/plugin/easy-video-reviews/reviews/" target="_blank" class="button button-primary" data-value="hide"><?php esc_html__( 'Rate us', 'easy-video-reviews' ); ?></a>.
		<button class="evr-notice-button button"><?php esc_html__( 'Remind me later', 'easy-video-reviews' ); ?></button>
		<div class="evr-notice-buttons-extended" style="display: none">
			<div>

				<button class="button button-primary" data-value="3"><?php esc_html__( 'Remind me in 3 days', 'easy-video-reviews' ); ?></button>
				<button class="button button-primary" data-value="7"><?php esc_html__( 'Remind me in 7 days', 'easy-video-reviews' ); ?></button>
				<button class="button button-primary" data-value="hide"><?php esc_html__( 'Never show again', 'easy-video-reviews' ); ?></button>
			</div>
		</div>
	</div>
</div>
