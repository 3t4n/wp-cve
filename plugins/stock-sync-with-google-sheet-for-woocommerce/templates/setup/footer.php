<?php

/**
 * Footer template for setup wizard.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__controls">
	<div class="ssgs-progressbar" x-show="[1, 4].includes(state.currentStep)">
		<div class="ssgs-progressbar__percentage" :style="`width: ${isFirstScreen ? 50 : 100}%;`"></div>
	</div>

	<div class="float-left">
		<button type="button" class="ssgs-btn border gray" x-show="showPrevButton" x-transition @click.prevent="clickPreviousButton">
		<?php
		esc_html_e(
			'Back',
			'stock-sync-with-google-sheet-for-woocommerce'
		);
		?>
		</button>
	</div>

	<div class="float-right">
		<button type="button" x-show="showNextButton" class="ssgs-btn flex-button" x-transition @click.prevent="clickNextButton" :class="{'disabled' : state.loadingNext}" 
		x-html="state.loadingNext ? '<div class=\'loader small\'></div> Please wait.' : 'Next'"></button>
	</div>
</div><!-- /Next Previous Controls -->
