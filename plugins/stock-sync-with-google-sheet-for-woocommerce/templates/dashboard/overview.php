<?php

/**
 * Overview template.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit();
?>
<div class="ssgs-dashboard__tab bounceInLeft" :class="{'active' : isTab('dashboard')}">
	<div class="ssgs-admin">
		<div class="ssgs-welcome">
			<div class="ssgs-welcome__left">
				<figure class="media float-left">
					<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/sync.svg'); ?>" alt="">
				</figure>

				<div class="text">
					<h3 class="sub-title" x-html="isReady ? '<?php esc_html_e('Congratulations', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <img src=\'<?php echo esc_url(SSGSW_PUBLIC); ?>/images/congratulation.svg\'>' : '<?php esc_html_e('Setup is not complete', 'stock-sync-with-google-sheet-for-woocommerce'); ?>'"></h3>
					<h2 class="title" x-text="isReady ? '<?php esc_html_e('Your products are syncing', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Your products are NOT syncing!', 'stock-sync-with-google-sheet-for-woocommerce'); ?>'"></h2>
					<a x-show="isReady" target="_blank" :href="option.spreadsheet_url" class="ssgs-btn"><?php esc_html_e('View products on Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
					<a x-show="!isReady" href="<?php echo esc_url(admin_url('/admin.php?page=ssgsw-admin')); ?>" class="ssgs-btn"><?php esc_html_e('Complete setup', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
				</div>
			</div>

			<div class="ssgs-welcome__right">
				<p>
					<span class="ssgs-badge aliceblue flex-button">
						<span x-show="limit" class="flex-button">
							<!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
							<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="width: 22px" xml:space="preserve">
								<path style="fill:#464655;" d="M509.435,448.962L271.75,44.367c-7.076-12.045-24.424-12.045-31.5,0L2.565,448.962
	c-7.194,12.245,1.595,27.705,15.75,27.705h475.368C507.839,476.666,516.629,461.207,509.435,448.962z" />
								<path style="fill:#5B5D6E;" d="M310.09,109.633c-75.525,54.5-124.731,143.208-124.731,243.46c0,44.069,9.578,85.877,26.64,123.573
	h281.685c14.155,0,22.944-15.459,15.75-27.705L310.09,109.633z" />
								<path style="fill:#FFDC64;" d="M252.195,93.738L53.788,431.474c-2.57,4.374,0.585,9.885,5.658,9.885h393.11
	c5.073,0,8.228-5.511,5.658-9.885L259.805,93.738C258.099,90.834,253.901,90.834,252.195,93.738z" />
								<path style="fill:#FFF082;" d="M185.36,353.093c0,30.722,4.638,60.357,13.216,88.267h253.98c5.073,0,8.228-5.511,5.658-9.885
	L282.389,132.181C222.755,187.029,185.36,265.683,185.36,353.093z" />
								<g>
									<circle style="fill:#464655;" cx="256.028" cy="379.568" r="17.653" />
									<path style="fill:#464655;" d="M239.049,213.171l7.566,113.485c0.33,4.948,4.174,8.784,8.805,8.784h1.216
		c4.631,0,8.475-3.834,8.805-8.784l7.566-113.485c0.365-5.475-3.682-10.131-8.805-10.131h-16.347
		C242.731,203.04,238.684,207.697,239.049,213.171z" />
								</g>
								<path style="fill:#FFC850;" d="M452.556,450.186H59.442c-5.499,0-10.62-2.966-13.353-7.741c-2.733-4.78-2.698-10.697,0.086-15.442
	L244.587,89.258c2.405-4.086,6.672-6.525,11.422-6.525c4.741,0,9.008,2.439,11.413,6.525l198.401,337.744
	c2.784,4.75,2.819,10.667,0.086,15.442C463.176,447.22,458.055,450.186,452.556,450.186z M63.399,432.533h385.201L255.998,104.684
	L63.399,432.533z" />
								<path style="fill:#FFDC64;" d="M448.599,432.533H196.069c1.633,5.964,3.455,11.844,5.441,17.653h251.048
	c5.499,0,10.62-2.966,13.353-7.741c2.733-4.775,2.698-10.692-0.086-15.442L289.135,126.224c-4.553,3.944-9,8.005-13.307,12.212
	L448.599,432.533z" />
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
							</svg>

							<?php esc_html_e('Only', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</span>
						<strong>
							<span x-text="limit || 'All'"></span>
							<?php esc_html_e('products', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</strong>
						<?php esc_html_e('can be synced with your current plan', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					</span>
				</p>

				<p x-show="!isPro">
					<a href="javascript:;" class="pro-link ssgsw-promo gradient-text icon-inside">
						<img src="<?php echo esc_url(SSGSW_PUBLIC); ?>images/gift.svg">
						<?php esc_html_e('Sync unlimited products with premium features', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					</a>
				</p>

			</div>
		</div>
	</div>

	<div class="ssgs-admin">
		<div class="ssgs-change-setup">
			<div class="ssgs-row">
				<div class="ssgs-column">
					<div class="form-group">
						<label for="google_spreadsheet_url" class="title title-secondary">
							<?php esc_html_e('Google Sheet URL', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip bottom">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step2_add-google-sheet-url.png'); ?>" alt="" />
								</span>
							</span>
						</label>
						<input type="url" name="google_spreadsheet_url" readonly :value="option.spreadsheet_url" class="ssgs-input" placeholder="<?php esc_html_e('Enter your google sheet URL', 'stock-sync-with-google-sheet-for-woocommerce'); ?>">
					</div>
				</div>

				<div class="ssgs-column">
					<div class="form-group">
						<label for="google_spreadsheet_url" class="title title-secondary"><?php esc_html_e('Sheet tab name ', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <span class="ssgs-tooltip left bottom"><i class="ssgs-help"></i><span><img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step2_enter-sheet-tab-name.png'); ?>" alt="" /></span></span></label>
						<input type="text" readonly :value="option.sheet_tab" class="ssgs-input" placeholder="<?php esc_html_e('Enter your google sheet Name', 'stock-sync-with-google-sheet-for-woocommerce'); ?>">
					</div>
				</div>
			</div>
			<div class="form-group mb-0 text-center">
				<button class="ssgs-btn border" :class="{'disabled' : !isSheetSelected || false}" @click.prevent="changeSetup"><?php esc_html_e('Change setup', 'stock-sync-with-google-sheet-for-woocommerce'); ?></button>
			</div>
		</div>

		<hr x-show="!isPro">

		<div class="ssgs-features" x-show="!isPro">

			<div class="ssgs-row">
				<div class="ssgs-column">
					<div class="content">
						<h3 class="title"><?php esc_html_e('Unleash the true power', 'stock-sync-with-google-sheet-for-woocommerce'); ?></h3>
						<ul>
							<li><?php esc_html_e('Sync unlimited products', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
							<li><?php esc_html_e('You add products from Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
							<li><?php esc_html_e('Description column to show the product description inside Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
							<li><?php esc_html_e('Show the category of the product in a column on the Google Sheets', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
							<li><?php esc_html_e('Show the sales count in a column on the Google Sheets', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
						</ul>

						<a href="javascript:;" class="ssgs-btn ssgsw-promo"><?php esc_html_e('Unlock all features', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
					</div>
				</div>

				<div class="ssgs-column">
					<figure class="media text-center">
						<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/features.svg'); ?>" alt="">
					</figure>
				</div>
			</div>
		</div>
	</div>
</div>
