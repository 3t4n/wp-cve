<?php

/**
 * WooCommerce template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-popup bg-none" style="display: none" x-show="!is_woocommerce_activated">
	<div class="popup-overlay">
	</div>
	<div class="content text-center" style="position: fixed;">

		<figure class="media">
			<img src="<?php echo esc_url(SSGSW_PUBLIC . 'images/woo.svg'); ?>" alt="">
		</figure>

		<h3 x-show="!state.activatingWooCommerce" class="title" x-text="(is_woocommerce_installed ? '<?php esc_html_e('Activate', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Install and activate', 'stock-sync-with-google-sheet-for-woocommerce'); ?>') + ' WooCommerce'"><?php esc_html_e('Install and activate WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce'); ?></h3>

		<div class="text" x-show="!state.activatingWooCommerce">
			<p>
				<?php esc_html_e('The plugin only works when WooCommerce is', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
				<span x-show="!is_woocommerce_installed">
					<strong>
						<?php esc_html_e('installed and', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					</strong>
				</span>
				<strong>
					<?php esc_html_e('activated', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</strong>
			</p>
		</div>

		<div x-show="state.activatingWooCommerce" class="ssgsw_flex_loader">
			<div>
				<div class="loader"></div>
			</div>
			<?php echo wp_kses_post('Activating WooCommerce... <br> This may take a couple of seconds.'); ?>
			<span x-show="is_woocommerce_activated">✔</span>
		</div>

		<a x-show="!state.activatingWooCommerce" href="#" @click.prevent="activateWooCommerce()" class="ssgs-btn flex-button" x-html="(is_woocommerce_installed ? '<?php esc_html_e('Activate', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Install & activate', 'stock-sync-with-google-sheet-for-woocommerce'); ?>')"></a>
	</div>
</div>
