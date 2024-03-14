<?php

/**
 * Welcome template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="start-setup text-center" x-show="!state.setupStarted" x-transition.delay>
	<figure class="media">
		<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/welcome.png'); ?>" alt="">
	</figure>

	<div class="content">
		<h3 class="title">
			<?php esc_html_e('Welcome to setup page', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</h3>
		<p class="description">
			<?php esc_html_e('Stock Sync with Google Sheet for WooCommerce makes it easy to configure your Google Sheet. Press the button and follow the steps to sync your products with Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</p>
		<button @click.prevent="state.setupStarted = true" class="ssgs-btn blue">
			<?php esc_html_e('Start setup', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</button>

		<div class="setup-video-link">
			<a target="_blank" href="https://www.youtube.com/watch?v=9KJCbed6N8U">
				<?php echo esc_html__('Watch video tutorial', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
			</a>
		</div>
	</div>
</div><!-- First Setup Widget -->
