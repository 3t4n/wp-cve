<?php

/**
 * Step 4 template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__pane" :class="{'active' : isStep(4), 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
	<div class="place-code" :class="{'active' : isFirstScreen, 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
		<div class="entry-title text-center">
			<h3 class="title">
				<?php
				esc_html_e(
					'Add Script Code on Editor',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				<span class="ssgs-tooltip bottom left">
					<i class="ssgs-help"></i>
					<span>
						<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_copy-paste-appScript.png'); ?>" alt="" />
					</span>
				</span>
			</h3>
		</div>

		<div class="ssgs-clipboard medium">
			<input type="text" readonly @click.prevent="copyAppsScript" value="**This is your Apps Script code. Click to copy and paste accordingly..." class="ssgs-input" id="clipboard-input-code">
			<span class="ssgs-tooltip text">
				<button class="ssgs-btn" @click.prevent="copyAppsScript" @mouseover="state.copied_apps_script = false">
					<?php
					esc_html_e(
						'Copy code',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
				</button>
				<span class="text" x-text="state.copied_apps_script ? '<?php esc_html_e('Copied to clipboard', 'stock-sync-with-google-sheet-for-woocommerce'); ?>' : '<?php esc_html_e('Copy to clipboard', 'stock-sync-with-google-sheet-for-woocommerce'); ?>'"></span>
			</span>
		</div>

		<div class="ssgs-row align-items-center">
			<div class="ssgs-column">
				<div class="content">
					<ol>
						<li>
							<?php
							esc_html_e(
								'Copy the Script Code from the box',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-tooltip">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_copy-code.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php
							esc_html_e(
								'Go to your Google Sheet and click on the',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-badge gray">
								<?php
								esc_html_e(
									'Extension',
									'stock-sync-with-google-sheet-for-woocommerce'
								);
								?>
							</span>
							<?php esc_html_e('menu', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_extention.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Click on', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<i class="ssgs-script"></i>
							<?php
							esc_html_e(
								'Apps Script',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-tooltip bottom">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_appscript.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php
							esc_html_e(
								'Now remove the existing code and paste the Script Code here',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-tooltip right">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_copy-paste-appScript.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php
							esc_html_e(
								'Finally, don’t forget to',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<strong>save</strong>
							<?php
							esc_html_e(
								'the code. Then click on',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-badge gray">
								<i class="ssgs-run"></i>
								<?php esc_html_e('Run', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span>
							<?php esc_html_e('button', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip right">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_save-and-run.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php
							esc_html_e(
								'If you are doing it for the first time, a popup will appear asking your permission. Make sure popups are not blocked in your browser.',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span style="font-weight: 600;">Watch the video for details 👉</span>
						</li>
					</ol>
				</div>
			</div>

			<div class="ssgs-column">
				<div class="ssgs-video-wrapper">
					<h4 class="title">
						<?php
						esc_html_e(
							'How to add Apps Script?',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
						<span class="ssgs-badge gray">
							<?php esc_html_e('0:55', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</span>
					</h4>

					<div class="sgss-video play-icon" data-play="https://youtu.be/KpKzI47XuIk">
						<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/thumbnails/apps-script.png'); ?>" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="place_code"><input type="checkbox" name="place_code" id="place_code" x-model="state.pasted_apps_script">
				<?php
				esc_html_e(
					"I've placed the code and clicked on",
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				<span class="ssgs-badge gray"><i class="ssgs-run"></i>
					<?php esc_html_e('Run', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				</span>
				<?php esc_html_e('button', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
			</label>
		</div>
	</div><!-- /First Step - Place Code -->

	<div class="trigger-permissions" :class="{'active' : !isFirstScreen, 'bounceInRight' : state.doingNext, 'bounceInLeft' : state.doingPrev}">
		<div class="entry-title text-center">
			<h3 class="title">
				<?php
				esc_html_e(
					'Add Trigger',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
				<span class="ssgs-tooltip bottom">
					<i class="ssgs-help"></i>
					<span>
						<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_Add-Trigger.png'); ?>" alt="" />
					</span>
				</span>
			</h3>
		</div>

		<div class="ssgs-row align-items-center">
			<div class="ssgs-column">
				<div class="content">
					<ol>
						<li>
							<?php esc_html_e('Go to', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-badge gray"><i class="ssgs-clock"></i>
								<?php esc_html_e('Triggers', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span>
							<?php esc_html_e('menu', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip bottom">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_trigger-menu.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Click on', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-badge round">
								<?php
								esc_html_e(
									'+ Add Trigger ',
									'stock-sync-with-google-sheet-for-woocommerce'
								);
								?>
							</span>
							<?php esc_html_e(' button', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip bottom">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_Add-Trigger.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Find the', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-badge gray">
								<?php
								esc_html_e(
									'Choose which function to run',
									'stock-sync-with-google-sheet-for-woocommerce'
								);
								?>
							</span>
							<?php
							esc_html_e(
								'dropdown menu, and select',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-badge gray">
								<?php esc_html_e('onEdit', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span>
							<?php esc_html_e('option', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip bottom">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_onEdit.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Find the', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-badge gray">
								<?php
								esc_html_e(
									'Select event type',
									'stock-sync-with-google-sheet-for-woocommerce'
								);
								?>
							</span>
							<?php
							esc_html_e(
								'dropdown menu, and select',
								'stock-sync-with-google-sheet-for-woocommerce'
							);
							?>
							<span class="ssgs-badge gray">
								<?php esc_html_e('On edit', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							</span>
							<?php esc_html_e('option', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<span class="ssgs-tooltip right">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_On-edit.png'); ?>" alt="" />
								</span>
							</span>
						</li>
						<li>
							<?php esc_html_e('Click', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
							<strong>
								<?php
								esc_html_e(
									'Save button.',
									'stock-sync-with-google-sheet-for-woocommerce'
								);
								?>
							</strong> 
							<span class="ssgs-tooltip right">
								<i class="ssgs-help"></i>
								<span>
									<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/tooltip/step4_save.png'); ?>" alt="" />
								</span>
							</span>
						</li>
					</ol>
				</div>
			</div>

			<div class="ssgs-column">
				<div class="ssgs-video-wrapper">
					<h4 class="title">
						<?php
						esc_html_e(
							'How to add Trigger?',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
						<span class="ssgs-badge gray">
							<?php esc_html_e('0:47', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
						</span>
					</h4>

					<div class="sgss-video play-icon" data-play="https://youtu.be/ePUBBMO5X0k">
						<img src="<?php echo esc_url(SSGSW_PUBLIC . '/images/thumbnails/apps-script-trigger.png'); ?>" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="trigger_permissions"><input type="checkbox" name="trigger_permissions" id="trigger_permissions" x-model="state.triggered_apps_script">
				<?php
				esc_html_e(
					'I’ve added the trigger and gave permissions',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
			</label>
		</div>
	</div><!-- /Second Step - Add Trigger -->

</div><!-- /Configure Apps Script -->
