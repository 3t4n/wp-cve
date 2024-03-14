<?php

/**
 * Header template for dashboard.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit();

		/**
		 * Directory to access.
		 *
		 * @var string
		 */
?>



<div class="ssgs-admin">

	<div class="ssgs-dashboard__header">
		<ul class="ssgs-dashboard__nav">
			<li class="ssgs-dashboard__nav-link" 
				:class="{'active' : 'dashboard' === state.currentTab }" 
				@click.prevent="setTab('dashboard')">
				<a href="#">
					<i class="ssgs-dashboard"></i>
					<?php esc_html_e('Dashboard', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</a>
			</li>
			<li class="ssgs-dashboard__nav-link" 
				:class="{'active' : 'settings' === state.currentTab }" 
				@click.prevent="setTab('settings')">
				<a href="#">
					<i class="ssgs-settings"></i>
					<?php esc_html_e('Settings', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</a>
			</li>
		</ul>

		<ul class="ssgs-dashboard-help">
			<li class="ssgs-support" @mouseenter="hovered = true" @mouseleave="hovered = false">
			<?php if ( ssgsw()->is_ultimate_activated() && ssgsw()->is_license_valid() ) : ?>
		<a href="javascript:;" @click="togglePopup">
			<i class="ssgs-support-icon" :class="{'blue': hovered }" x-init="$watch('hovered', value => console.log(value))"></i>
				<?php esc_html_e('Support', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</a>
	<?php else : ?>
		<a href="<?php echo esc_url('//wordpress.org/support/plugin/stock-sync-with-google-sheet-for-woocommerce'); ?>" target="_blank">
			<i class="ssgs-support-icon" :class="{'blue': hovered }"></i>
			<?php esc_html_e('Support', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</a>
	<?php endif; ?>
			</li>
			<li>
				<a href="https://www.youtube.com/watch?v=9KJCbed6N8U" target="_blank">
					<i class="ssgs-video"></i>
					<?php esc_html_e('Video tutorial', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</a>
			</li>
			<li class="gradient force-flex" :class="{'no-padding' : isPro}">
				<a  href="javascript:;" class="ssgsw_changelogs_trigger" 
					@click.prevent="toggleChangelogs">
					🤩 <?php esc_html_e('What\'s new?', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</a>
				<div id="ssgsw_changelogs"></div>
			</li>
		</ul>
	</div>
</div>
