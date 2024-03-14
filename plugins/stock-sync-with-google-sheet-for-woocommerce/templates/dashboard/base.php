<?php

/**
 * Base template for dashboard.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit();
?>
<div x-data="dashboard">
	<?php
	if ( ! ssgsw()->is_ultimate_activated() && ( true !== wp_validate_boolean(get_transient('ssgsw_hide_upgrade_notice')) ) && true !== wp_validate_boolean(ssgsw_get_option('hide_upgrade_notice')) ) :
		$screen = get_current_screen();
		if ( 'toplevel_page_ssgsw-admin' !== $screen->id ) {
			return;
		}
		?>
	<div class="ssgsw-upgrade-notice">
		<div class="ssgsw-upgrade-notice-content">
			<?php echo wp_sprintf('Get <strong>unlimited syncs</strong> and <strong>premium features</strong> in the ULTIMATE version.'); ?>
		</div>
		<div class="ssgsw-upgrade-notice-buttons">
			<a href="https://go.wppool.dev/mr9n" target="_blank"><?php esc_html_e('Upgrade to ULTIMATE', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
			<a href="#" @click.prevent="hideUpgradeNotice"><?php esc_html_e('Hide', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
		</div>
	</div>
		<?php
	endif;
	?>

	<section class="ssgsw-wrapper">
		<?php ssgsw()->load_template('dashboard/header'); ?>

		<?php ssgsw()->load_template('dashboard/overview'); ?>

		<?php ssgsw()->load_template('dashboard/settings'); ?>

		<?php
		if ( ssgsw()->is_ultimate_activated() && ssgsw()->is_license_valid() ) {
			ssgsw()->load_template('dashboard/support');
		}
		?>
	</section>
</div>
