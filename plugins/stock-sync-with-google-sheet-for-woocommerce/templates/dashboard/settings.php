<?php

/**
 * Settings template.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit(); ?>
<div class="ssgs-dashboard__tab bounceInRight" :class="{'active': isTab('settings')}">
	<div class="ssgs-admin">
		<div class="ssgs-dashboard__block">
			<h4 class="title">
			<?php
			esc_html_e(
				'Preference',
				'stock-sync-with-google-sheet-for-woocommerce'
			);
			?>
			</h4>

			<div class="form-group" :class="forUltimate">
				<label>
					<div class="ssgs-check" :class="forUltimate">
						<input :readonly="!isPro" type="checkbox" name="add_products_from_sheet" class="check" x-model="option.add_products_from_sheet" :checked="option.add_products_from_sheet == 1" @change="save_change++">
						<span class="switch"></span>
					</div>

					<span class="label-text">
						<?php
						esc_html_e(
							'Add new products from Google Sheet',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
					</span>

					<span x-show="!isPro" class="ssgs-badge purple ssgsw-promo">
						<?php esc_html_e( 'Ultimate', 'stock-sync-with-google-sheet-for-woocommerce' ); ?>
					</span>

				</label>

				<div class="description">
					<p>
						<?php
						esc_html_e(
							'Enable this feature to add new products from Google Sheet',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
					</p>
				</div>
			</div>
			<div class="form-group" >
				<label>
					<div class="ssgs-check">
						<input type="checkbox" name="bulk_edit_option" class="check check2"  :checked="option.bulk_edit_option == 1" 
						x-on:change="
						if (option.bulk_edit_option == false || option.bulk_edit_option == null) {
							option.bulk_edit_option = true;
							show_disable_popup = false;
							save_change++
						} else if( option.bulk_edit_option == true || option.bulk_edit_option == 1 ) {
							option.bulk_edit_option = false;
							show_disable_popup = true;
						}"
						>
						<span class="switch"></span>
					</div>

					<span class="label-text">
						<?php
						esc_html_e(
							'Bulk edit on Google Sheet',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
					</span>
					<span class="ssgs-badge green" >
					<?php
					esc_html_e(
						'New',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>

				</label>

				<div class="description">
					<p>
						<?php
						esc_html_e(
							'Enable this feature to bulk edit WooCommerce product data from Google Sheets',
							'stock-sync-with-google-sheet-for-woocommerce'
						);
						?>
					</p>
				</div>
			</div>
		</div>

		<div class="ssgs-dashboard__block">
			<h4 class="title">
				<?php
				esc_html_e(
					'Google Sheet columns',
					'stock-sync-with-google-sheet-for-woocommerce'
				);
				?>
			</h4>

			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input :readonly="!isPro" type="checkbox" name="show_sku" class="check" x-model="option.show_sku" :checked="option.show_sku == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Sync SKU',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple">
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>

				</label>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this button to show the short SKU of the product in a column on the Google Sheets',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>

			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input :readonly="!isPro" type="checkbox" name="show_short_description" class="check" x-model="option.show_short_description" :checked="option.show_short_description == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Sync short description',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple">
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>

				</label>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this button to show the short description of the product in a column on the Google Sheets',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>

			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input :readonly="!isPro" type="checkbox" name="show_attributes" class="check" x-model="option.show_attributes" :checked="option.show_attributes == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Display attributes',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple">
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
				</label>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this button to show the product attributes in a column on the Google Sheets',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>

			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input :readonly="!isPro" type="checkbox" name="show_total_sales" class="check" x-model="option.show_total_sales" :checked="option.show_total_sales == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Display product sales count',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple"> 
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
				</label>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this feature to display the total sales count of the products in a column on the Google Sheets',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>
			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input :readonly="!isPro" type="checkbox" name="show_product_images" class="check" x-model="option.show_product_images" :checked="option.show_product_images == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Display product image',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple"> 
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
				</label>
				<span class="ssgs-badge green" >
					<?php
					esc_html_e(
						'New',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this feature to show the product image in the Google Sheet',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>

			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input type="checkbox" name="show_product_category" class="check" x-model="option.show_product_category" :checked="option.show_product_category == 1" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text">
					<?php
					esc_html_e(
						'Display product category',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple"> 
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
				</label>

				<div class="description">
					<p>
					<?php
					esc_html_e(
						'Enable this button to show the category of the product in a column on the Google Sheets',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
			</div>
			<div class="form-group">
				<label :class="forUltimate">
					<div class="ssgs-check">
						<input type="checkbox" name="show_custom_meta_fileds" class="check" x-model="option.show_custom_meta_fileds" :checked="option.show_custom_meta_fileds == 1 && isPro" @change="save_change++">
						<span class="switch"></span>
					</div>
					<span class="label-text ssgs_custom_fileds">
					<?php
					esc_html_e(
						'Sync Custom Fields',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
					<span x-show="!isPro" class="ssgs-badge purple"> 
					<?php
					esc_html_e(
						'Ultimate',
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</span>
				</label> 
				<div class="description">
					<p>
					<?php
					esc_html_e(
						"Enter or search your product's custom field (meta data) and enable them to display on the Spreadsheet as columns.",
						'stock-sync-with-google-sheet-for-woocommerce'
					);
					?>
					</p>
				</div>
				<div class="ssgsw_description" x-show="option.show_custom_meta_fileds == 1 && isPro" x-init="select2Alpine">
				<?php
					$check_box_values = ssgsw_get_product_custom_fields();
					$checked_value  = ssgsw_get_option('show_custom_fileds');
				?>
					<select x-ref="select" class="ssgsw_custom_filed form-control" multiple="multiple" name="ssgsw_custom_fileds[]">
						<?php
						foreach ( $check_box_values as $key => $value ) {
							$check_type = check_ssgsw_file_type($key);
							$key_word = ssgsw_reserved_keyword($key);
							if ( 'not_suported' === $check_type ) {
								printf('<option value="%s" disabled>%s</option>', esc_html($key), esc_html($key));
							} else if ( 'yes' === $key_word ) {
								printf('<option value="%s" disabled>%s</option>', esc_html($key), esc_html('(Custom field with reserved words are not supported yet)'));
							} else {
								if ( ! empty($checked_value) ) {
									if ( in_array($key, $checked_value) ) {
										printf('<option value="%s" selected>%s</option>', esc_html($key), esc_html($key));
									} else {
										printf('<option value="%s">%s</option>', esc_html($key), esc_html($key));
									}
								} else {
									printf('<option value="%s">%s</option>', esc_html($key), esc_html($key));
								}
							}
						}
						?>
					</select>
					<div class="ssgsw_show_selected_options fixed-bottom" x-bind:class="{ 'ssgsw_show_selected_option2': option.show_custom_fileds && option.show_custom_fileds.length > 0 }">
						  <ul>
						  <template x-if="option.show_custom_fileds && option.show_custom_fileds.length > 0">
							<template x-for="name in option.show_custom_fileds" :key="name">
								<li>
									<input type="checkbox" disabled checked="checked" x-text="name" value="name"><span for="label_demo" x-text="name"></span>
								</li>
							</template>
						  </template>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="ssgsw_button_container" x-show="save_change" style="transition: opacity 300ms ease-in-out 0ms;">
			<button x-on:click="save_checkbox_settings('');isLoading = true" :disabled="isLoading" class="ssgsw_save_button">
				<span x-show="!isLoading"><?php esc_html_e('Save Changes','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
				<span x-show="isLoading"><?php esc_html_e('Saving...','stock-sync-with-google-sheet-for-woocommerce'); ?></span>
			</button>
			<button type="button" class="ssgsw_save_close"  x-on:click="show_discrad = true"><?php esc_html_e('Discard Changes','stock-sync-with-google-sheet-for-woocommerce'); ?></button>
		</div>
		<?php
		/**
		 * Popup show
		 */
		?>
		
	</div>
</div>
<div id="popup1" class="ssgs_popup-container" x-show="show_discrad" style="display: none">
	<div class="ssgs_popup-content" @click.away="show_discrad = false">
		<a href="#" class="close ssgs_close_button" x-on:click="show_discrad = false">&times;</a>
		<div class="profile-section">
			  <div class="profile-image ssgsw_logo_section_popup"><span class="dashicons dashicons-warning ssgs_warning"></span></div>
			<div class="profile-details">
				<h3 class="profile-title"><?php esc_html_e('Discard All Changes','stock-sync-with-google-sheet-for-woocommerce'); ?></h3>
				<p class="profile-description"><?php esc_html_e('You are about to discrad all unsaved changes. All of your settings will be reset to the point where you last saved. Are you sure you want to do this?','stock-sync-with-google-sheet-for-woocommerce'); ?></p>
			</div>
		</div>
		<div class="ssgs_first_section">
			<div class="ssgs_button_section">
				<button type="button" class="ssgsw_save_close1" x-on:click="show_discrad = false"><?php esc_html_e('No, continue editing','stock-sync-with-google-sheet-for-woocommerce'); ?></button>
				<button type="button" class="ssgsw_save_changes" x-on:click="reload_the_page();"><?php esc_html_e('Yes, discard changes','stock-sync-with-google-sheet-for-woocommerce'); ?></button>
			</div>
		</div>
	</div>
</div>
<div id="popup1" class="ssgs_popup-container" x-show="show_disable_popup" style="display: none">
	<div class="ssgs_popup-content" @click.away="show_disable_popup = false; option.bulk_edit_option = true">
		<a href="#" class="close ssgs_close_button" x-on:click="show_disable_popup = false;option.bulk_edit_option = true">&times;</a>
		<div class="profile-section">
			<div class="profile-details">
				<h3 class="profile-title"><?php esc_html_e('⚠️Wait','stock-sync-with-google-sheet-for-woocommerce'); ?></h3>
				<p class="ssgsw_extra_class" style="font-size: 14px; marign-left:10px;"><?php esc_html_e('We recommend keeping this feature enabled at all times. It will help you to swiftly update your data and seamlessly sync it with WooCommerce. Disabling this feature may expose you to unintended changes while editing multiple products on Google Sheets. Do you still want to diable it?'); ?></p>
			</div>
		</div>
		<div class="ssgs_first_section">
			<div class="ssgs_button_section">
				
				<button type="button" class="ssgsw_save_changes ssgsw_save_changes23" x-on:click="option.bulk_edit_option = false;show_disable_popup = false;save_change++"><?php esc_html_e(' Disable at my risk','stock-sync-with-google-sheet-for-woocommerce'); ?></button>
				<button type="button" class="ssgsw_save_close1" style="background-color:#005ae0; color:#fff" x-on:click="show_disable_popup = false; option.bulk_edit_option = true"><?php esc_html_e('Keep Enabled','stock-sync-with-google-sheet-for-woocommerce'); ?></button>
			</div>
		</div>
	</div>
</div>
