<?php
/**
 * Easy Video Reviews - Affiliate
 * Affiliate
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<div class="notice notice-info is-dismissible evr-notice" data-notice="affiliate">
	<div class="evr-notice-text"><span class="dashicons dashicons-money-alt"></span><?php esc_html__( 'Earn 25% of each product sale we make', 'easy-video-reviews' ); ?>
	</div>
	<div class="evr-notice-buttons">
		<a href="https://wppool.dev/affiliates/" target="_blank" class="button button-primary" data-hidenotice><?php esc_html__( 'Join affiliate', 'easy-video-reviews' ); ?></a>.
		<button class="evr-notice-button button"><?php esc_html__( 'Remind me later', 'easy-video-reviews' ); ?></button>
		<div class="evr-notice-buttons-extended" style="display: none">
			<div>
				<button class="button button-primary" data-hidenotice="3"><?php esc_html__( 'Remind me in 3 days', 'easy-video-reviews' ); ?></button>
				<button class="button button-primary" data-hidenotice="7"><?php esc_html__( 'Remind me in 7 days', 'easy-video-reviews' ); ?></button>
				<button class="button button-primary" data-hidenotice><?php esc_html__( 'Never show again', 'easy-video-reviews' ); ?></button>
			</div>
		</div>
	</div>
</div>
