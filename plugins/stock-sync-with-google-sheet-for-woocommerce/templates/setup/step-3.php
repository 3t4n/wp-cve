<?php

/**
 * Step 3 template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__pane" :class="{'active' : isStep(3), 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
	<div class="access-email-id">
		<div class="entry-title text-center">
			<h3 class="title">
				<?php
					printf(
						'%1$s <span class="ssgs-badge gray">%2$s</span> %3$s <span class="ssgs-tooltip bottom left"><i class="ssgs-help"></i><span><img src="%4$s" alt="" /></span></span>',
						esc_html__('Give', 'stock-sync-with-google-sheet-for-woocommerce'),
						esc_html__('Editor', 'stock-sync-with-google-sheet-for-woocommerce'),
						esc_html__('access to the following ID', 'stock-sync-with-google-sheet-for-woocommerce'),
						esc_url(SSGSW_PUBLIC . '/images/tooltip/step3_Editor-access-to-emial-ID.png')
					);
					?>
			</h3>
		</div>

		<div class="ssgs-clipboard">
			<input type="text" readonly @click.prevent="copyServiceAccountEmail" :value="credentials ? credentials.client_email : false" class="ssgs-input" id="clipboard-input-id">
			<span class="ssgs-tooltip text">
				<button class="ssgs-btn" @click.prevent="copyServiceAccountEmail" @mouseover="state.copied_client_email = false">
					<?php esc_html_e('Copy', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</button>
				<span class="text" id="tooltiptext-id" x-text="state.copied_client_email ? '<?php esc_html_e('Copied to Clipboard', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Copy to clipboard', 'stock-sync-with-google-sheet-for-woocommerce'); ?>'"></span>
			</span>
		</div>

		<div class="ssgs-row align-items-center">
			<div class="ssgs-column">
				<div class="content">
					<ol>
						<li>
							<?php esc_html_e('Copy the email ID from the box', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-tooltip">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step3_copy-email-ID.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Go to your Google Sheet & click', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-badge green">
								<i class="ssgs-link-share"></i> 
								<?php esc_html_e('Share', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span> 
							<?php esc_html_e('button at the top-right position', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-tooltip right">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step3_click-on-share-button.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Paste the Email ID that you copied and give', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-badge gray">
								<?php esc_html_e('Editor', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span> 
							<?php esc_html_e('access', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-tooltip">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step3_Editor-access-to-emial-ID.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Then, click the', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-badge">
								<?php esc_html_e('Send', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span> 
							<?php esc_html_e('or', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
							<span class="ssgs-badge" style="margin-left:0">
							<?php esc_html_e('Share', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</span> 
						<?php esc_html_e('button to confirm', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
						<span class="ssgs-tooltip">
							<i class="ssgs-help"></i>
							<span>
								<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step3_click-share-button.png'); ?>" alt="" />
							</span>
						</span>
					</li>
					</ol>
				</div>
			</div>

			<div class="ssgs-column">
				<div class="ssgs-video-wrapper">
					<h4 class="title">
						<?php esc_html_e('How to set editor access?', 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
						<span class="ssgs-badge gray">
							<?php esc_html_e('0:36', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</span>
					</h4>

					<div class="sgss-video play-icon" data-play="https://youtu.be/bUL4tJFc7kY">
					<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/thumbnails/editor-access.png'); ?>" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="access_email_id">
				<input type="checkbox" name="access_email_id" id="access_email_id" x-model="state.given_editor_access">
				<?php esc_html_e("I've given", 'stock-sync-with-google-sheet-for-woocommerce'); ?> 
				<span class="ssgs-badge gray">
					<?php esc_html_e('Editor', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</span> 
				<?php esc_html_e('access to this email ID', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
			</label>
		</div>
	</div>
</div><!-- /Set ID -->
