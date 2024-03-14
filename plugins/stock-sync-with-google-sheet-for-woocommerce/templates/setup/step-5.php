<?php

/**
 * Step 5 template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__pane" :class="{'active' : isStep(5), 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
	<div class="sync-google-sheet" :class="{'active' : isFirstScreen}">
		<div class="content text-center">
			<figure class="media">
				<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/recycling.svg'); ?>" alt="">
			</figure>

			<h3 class="title">
			<?php
			esc_html_e(
				'Sync with Google Sheet',
				'stock-sync-with-google-sheet-for-woocommerce'
			);
			?>
			</h3>

			<div class="description">
				<p>
				<?php
				esc_html_e(
					'You’re almost ready. Press this button to sync your WooCommerce products with Google Sheet.',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				</p>
			</div>

			<a :class="{'disabled' : state.syncingGoogleSheet}" href="javascript:;" @click.prevent="syncGoogleSheet" class="ssgs-btn flex-button" 
			x-html="state.syncingGoogleSheet ? '<div class=\'loader small\'></div><?php esc_html_e('Syncing', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Sync with Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'); ?>'"></a>
		</div>
	</div><!-- /First Step - Sync Google Sheet -->




	<!-- after completing syncing  -->
	<div class="congratulations" x-show="!isFirstScreen" x-transition.50ms>
		<div class="content text-center">
			<figure class="media">
				<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/congratulations.svg'); ?>" alt="">
			</figure>

			<h3 class="title">
			<?php
			esc_html_e(
				'Congratulations',
				'stock-sync-with-google-sheet-for-woocommerce'
			);
			?>
			</h3>

			<div class="description">
				<p>
				<?php
				esc_html_e(
					'Your products have been successfully synced to Google Sheet',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				</p>
			</div>

			<div class="ssgs-btn-group">
				<a href="
				<?php
				echo esc_url(
					admin_url(
						'admin.php?page=ssgsw-admin'
					)
				);
				?>
				" class="ssgs-btn border">
				<?php
				esc_html_e(
					'Got to Dashboard',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				</a>
				<a target="_blank" href="javascript:;" @click.prevent="viewGoogleSheet" class="ssgs-btn">
				<?php
				esc_html_e(
					'View on Google Sheet',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				</a>
			</div>

			<p><span class="ssgs-badge aliceblue"><span x-show="limit">
			<?php
			esc_html_e(
				'Only',
				'stock-sync-with-google-sheet-for-woocommerce'
			);
			?>
			</span> <strong><span x-text="limit || 'All'"></span> 
			<?php
			esc_html_e(
				'products',
				'stock-sync-with-google-sheet-for-woocommerce'
			);
			?>
			</strong> 
			<?php
				esc_html_e(
					'can be synced with your current plan',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
			</span></p>

			<div class="profeatures">
				<p x-show="!isPro">
					<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/gift.svg'); ?> " alt="">
					<a href="javascript:;" class="ssgsw-promo">
						<?php esc_html_e('Sync unlimited products with premium features', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					</a>
				</p>
			</div>
		</div>
	</div><!-- /Second Step - Congratulations -->
</div><!-- /Done -->
