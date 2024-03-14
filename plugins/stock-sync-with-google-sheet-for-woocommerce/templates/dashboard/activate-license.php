<?php
/**
 * Activate license template.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit();
?>
<div class="ssgsw-license-notice">
	<?php esc_html_e('Please activate your license.', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
	<a href="<?php echo esc_url(admin_url('admin.php?page=ssgsw-license')); ?>">
		<?php esc_html_e('Activate Now &#8594;', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
	</a>
</div>
