<?php

/**
 * Step 2 template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__pane" :class="{'active' : isStep(2), 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
	<div class="form-group">
		<label for="google_sheet_url" class="title title-secondary">
			<?php esc_html_e('Add Google Sheet URL', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
			<span class="ssgs-tooltip bottom">
				<i class="ssgs-help"></i>
				<span>
					<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step2_add-google-sheet-url.png'); ?>" alt="" />
				</span>
			</span>
		</label>
		<p class="description">
			<?php esc_html_e('Copy the URL of your Google Sheet and paste it here. So that, our system can add all your WooCommerce Products into it.', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</p>

		<input type="url" id="google_sheet_url" class="ssgs-input" placeholder="<?php esc_html_e('Enter your google sheet URL', 'stock-sync-with-google-sheet-for-woocommerce'); ?>"  x-model="option.spreadsheet_url">
	</div>

	<div class="form-group">
		<label for="google_sheet_name" class="title title-secondary">
			<?php esc_html_e('Enter sheet tab name', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
			<span class="ssgs-tooltip">
				<i class="ssgs-help"></i>
				<span>
					<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step2_enter-sheet-tab-name.png'); ?>" alt="" />
				</span>
			</span>
		</label>
		<p class="description">
			<?php esc_html_e('Copy the sheet tab name (ex: Sheet1) from your Google Sheet and paste it here. So that, our system can add your WooCommerce products to the spreadsheet.', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
		</p>

		<input type="text" value="Sheet1" id="google_sheet_name" class="ssgs-input" placeholder="<?php esc_html_e('Enter your google sheet Name', 'stock-sync-with-google-sheet-for-woocommerce'); ?>"  x-model="option.sheet_tab">
	</div>
</div><!-- /Set URL -->
