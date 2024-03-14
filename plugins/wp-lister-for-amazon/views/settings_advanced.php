<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

	.wpla-page #poststuff #color-map-table,
	.wpla-page #poststuff #color-map-table input.text_input,
	.wpla-page #poststuff #color-map-table select.select,
    .wpla-page #poststuff #ship-from-addresses-table,
    .wpla-page #poststuff #ship-from-addresses-table input.text_input,
    .wpla-page #poststuff #ship-from-addresses-table select.select
    {
		width: 100%;
	}
	.wpla-page #poststuff #color-map-table th {
		width: 50%;
		text-align: left;
	}

    .wpla-page #poststuff #ship-from-addresses-table th {
        text-align: left;
    }

    .wpla-page #poststuff #ship-from-addresses-table td.delete {
        vertical-align: top;
    }

	.wpla-page #poststuff #size-map-table,
	.wpla-page #poststuff .size-map-table,
	.wpla-page #poststuff #size-map-table input.text_input,
	.wpla-page #poststuff #size-map-table select.select {
		width: 100%;
	}
	.wpla-page #poststuff #size-map-table th,
	.wpla-page #poststuff .size-map-table th {
		width: 50%;
		text-align: left;
	}

	.wpla-page #poststuff #variation-attributes-table,
	.wpla-page #poststuff #variation-attributes-table select.select {
		width: 100%;
	}
	.wpla-page #poststuff #variation-attributes-table th {
		width: 50%;
		text-align: left;
	}

	.wpla-page #poststuff #variation-merger-table,
	.wpla-page #poststuff #variation-merger-table input.text_input,
	.wpla-page #poststuff #variation-merger-table select.select {
		width: 100%;
	}
	.wpla-page #poststuff #variation-merger-table th {
		text-align: left;
	}

	.wpla-page #poststuff #custom-shortcodes-table {
		width: 100%;
	}
	.wpla-page #poststuff #custom-shortcodes-table input.text_input {
		width: 95%;
	}
	.wpla-page #poststuff #custom-shortcodes-table th {
		text-align: left;
	}
	.wpla-page #poststuff #custom-shortcodes-table td {
		vertical-align: top;
	}
	.wpla-page #poststuff #custom-shortcodes-table textarea {
		height: 6em;
		width: 100%;
	}

	.wpla-page #poststuff #custom-variation-fields-table {
		width: 100%;
	}
	.wpla-page #poststuff #custom-variation-fields-table input.text_input {
		width: 95%;
	}
	.wpla-page #poststuff #custom-variation-fields-table th {
		text-align: left;
	}

	.wpla-page #poststuff #side-sortables .postbox input.text_input,
	.wpla-page #poststuff #side-sortables .postbox select.select {
	    width: 50%;
	}
	.wpla-page #poststuff #side-sortables .postbox label.text_label {
	    width: 45%;
	}
	.wpla-page #poststuff #side-sortables .postbox p.desc {
	    margin-left: 5px;
	}

    .wpla-page #poststuff .size-map-block {
        border: 1px solid #ccc;
        padding: 0 20px 20px 20px;
        margin: 20px 0;
    }

</style>

<div class="wrap wpla-page">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>

	<?php include_once( dirname(__FILE__).'/settings_tabs.php' ); ?>
	<?php echo $wpl_message ?>

	<form method="post" id="settingsForm" action="<?php echo $wpl_form_action; ?>">

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box">


					<!-- first sidebox -->
					<div class="postbox" id="submitdiv">
						<!--<div title="Click to toggle" class="handlediv"><br></div>-->
						<h3 class="hndle"><span><?php echo __( 'Update', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<div id="submitpost" class="submitbox">

								<div id="misc-publishing-actions">
									<div class="misc-pub-section">
										<p><?php echo __( 'This page contains some advanced options for special use cases.', 'wp-lister-for-amazon' ) ?></p>
									</div>
								</div>

								<div id="major-publishing-actions">
									<div id="publishing-action">
                                        <?php wp_nonce_field( 'wpla_save_advanced_settings' ); ?>
										<input type="hidden" name="action" value="save_wpla_advanced_settings" >
										<input type="submit" value="<?php echo __( 'Save Settings', 'wp-lister-for-amazon' ); ?>" id="save_settings" class="button-primary" name="save">
									</div>
									<div class="clear"></div>
								</div>

							</div>

						</div>
					</div>

					<?php if ( ( ! is_multisite() ) || ( is_main_site() ) ) : ?>
					<div class="postbox" id="UninstallSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Uninstall on removal', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-option-uninstall" class="text_label"><?php echo __( 'Uninstall', 'wp-lister-for-amazon' ); ?>:</label>
							<select id="wpl-option-uninstall" name="wpla_option_uninstall" title="Uninstall" class=" required-entry select">
								<option value="0" <?php if ( $wpl_option_uninstall != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_option_uninstall == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this to completely remove listings, orders and settings when removing the plugin.', 'wp-lister-for-amazon' ); ?><br><br>
							</p>

						</div>
					</div>
					<?php endif; ?>

				</div>
			</div> <!-- #postbox-container-1 -->





			<!-- #postbox-container-3 -->
			<?php if ( ( ! is_multisite() || is_main_site() ) && apply_filters( 'wpla_enable_capabilities_options', true ) ) : ?>
			<div id="postbox-container-3" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">

					<div class="postbox" id="PermissionsSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Roles and Capabilities', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<?php
								$wpl_caps = array(
									'manage_amazon_listings'  => __( 'Manage Amazon Listings', 'wp-lister-for-amazon' ),
									'manage_amazon_options'   => __( 'Manage Amazon Settings', 'wp-lister-for-amazon' ),
									// 'prepare_amazon_listings' => __( 'Prepare Listings', 'wp-lister-for-amazon' ),
									// 'publish_amazon_listings' => __( 'Publish Listings', 'wp-lister-for-amazon' ),
								);
							?>

							<table style="width:100%">
                            <?php foreach ($wpl_available_roles as $role => $role_name) : ?>
                            	<tr>
                            		<th style="text-align: left">
		                                <?php echo $role_name; ?>
		                            </th>

		                            <?php foreach ($wpl_caps as $cap => $cap_name ) : ?>
                            		<td>
		                                <input type="checkbox"
		                                    	name="wpla_permissions[<?php echo $role ?>][<?php echo $cap ?>]"
		                                       	id="wpla_permissions_<?php echo $role.'_'.$cap ?>" class="checkbox_cap"
		                                       	<?php if ( isset( $wpl_wp_roles[ $role ]['capabilities'][ $cap ] ) ) : ?>
		                                       		checked
		                                   		<?php endif; ?>
		                                       	/>
		                                       	<label for="wpla_permissions_<?php echo $role.'_'.$cap ?>">
				                               		<?php echo $cap_name; ?>
				                               	</label>
			                            </td>
		                            <?php endforeach; ?>

		                        </tr>
                            <?php endforeach; ?>
                        	</table>


						</div>
					</div>

				</div>
			</div> <!-- #postbox-container-3 -->
			<?php endif; ?>


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">

					<div class="postbox" id="UISettingsBox">
						<h3 class="hndle"><span><?php echo __( 'User Interface', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-thumbs_display_size" class="text_label">
								<?php echo __( 'Listing thumbnails', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select the thumbnail size on the Listings page.<br><br>To disable the image column entirely use the "Screen Options" on the Listings page.') ?>
							</label>
							<select id="wpl-thumbs_display_size" name="wpla_thumbs_display_size" class="required-entry select">
								<option value="0" <?php if ( $wpl_thumbs_display_size == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Small', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_thumbs_display_size == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Medium', 'wp-lister-for-amazon' ); ?></option>
								<option value="2" <?php if ( $wpl_thumbs_display_size == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Large', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Select the thumbnail size on the Listings page.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<label for="wpl-default_matcher_selection" class="text_label">
								<?php echo __( 'Default matching query', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select which product property to use by default when matching products on Amazon.') ?>
							</label>
							<select id="wpl-default_matcher_selection" name="wpla_default_matcher_selection" class=" required-entry select">
								<option value="title" <?php if ( $wpl_default_matcher_selection == 'title' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Title', 'wp-lister-for-amazon' ) ?></option>
								<option value="sku"   <?php if ( $wpl_default_matcher_selection == 'sku' ):   ?>selected="selected"<?php endif; ?>><?php echo __( 'SKU', 'wp-lister-for-amazon' ) ?></option>
				                <?php foreach ($wpl_available_attributes as $attribute) : ?>
									<option value="<?php echo $attribute->label ?>"   <?php if ( $wpl_default_matcher_selection == $attribute->label ):   ?>selected="selected"<?php endif; ?>><?php echo $attribute->label ?></option>
				                <?php endforeach; ?>
							</select>

                            <label for="wpl-default_matched_profile" class="text_label">
                                <?php echo __( 'Default matched listings profile', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select which profile to assign to matched listings.') ?>
                            </label>
                            <select id="wpl-default_matched_profile" name="wpla_default_matched_profile" class=" required-entry select">
                                <option value="" <?php selected( '', $wpl_default_matched_profile ); ?>><?php echo __( 'No profile', 'wp-lister-for-amazon' ) ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <?php foreach ($wpl_available_profiles as $profile_id => $profile) : ?>
                                    <option value="<?php echo esc_attr( $profile_id ); ?>" <?php selected( $wpl_default_matched_profile, $profile_id ); ?>><?php echo esc_html( $profile ); ?></option>
                                <?php endforeach; ?>
                            </select>

							<label for="wpl-dismiss_imported_products_notice" class="text_label">
								<?php echo __( 'Import Queue reminder', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select whether you want to see a reminder message prompting you to import items witing in the import queue.<br><br>It will always show when you view the <i>Import Queue</i> on the listings page.') ?>
							</label>
							<select id="wpl-dismiss_imported_products_notice" name="wpla_dismiss_imported_products_notice" class=" required-entry select">
								<option value="0" <?php if ( $wpl_dismiss_imported_products_notice != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Always show when there are items queued for import', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_dismiss_imported_products_notice == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Only show when visiting the Import Queue', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-enable_missing_details_warning" class="text_label">
								<?php echo __( 'Missing product details warning', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will show a warning when you create or update a product which is missing required details like SKU, price or quantity.') ?>
							</label>
							<select id="wpl-enable_missing_details_warning" name="wpla_enable_missing_details_warning" class=" required-entry select">
								<option value="0" <?php if ( $wpl_enable_missing_details_warning != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_enable_missing_details_warning == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>

                            <label for="wpl-validate_ean" class="text_label">
                                <?php echo __( 'Check for invalid EAN/UPC', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will show a warning when invalid EANs or UPCs are detected.') ?>
                            </label>
                            <select id="wpl-validate_ean" name="wpla_validate_ean" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_validate_ean, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                                <option value="1" <?php selected( $wpl_validate_ean, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>

                            <label for="wpl-validate_sku" class="text_label">
                                <?php echo __( 'Check for invalid SKU', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will show a warning when invalid SKUs are detected.') ?>
                            </label>
                            <select id="wpl-validate_sku" name="wpla_validate_sku" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_validate_sku, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                                <option value="1" <?php selected( $wpl_validate_sku, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>

							<label for="wpl-enable_custom_product_prices" class="text_label">
								<?php echo __( 'Enable custom price field', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If do not use custom prices in Amazon and prefer less options when editing a product, you can disable the custom price fields here.') ?>
							</label>
							<select id="wpl-enable_custom_product_prices" name="wpla_enable_custom_product_prices" class=" required-entry select">
								<option value="0" <?php if ( $wpl_enable_custom_product_prices == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_enable_custom_product_prices == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="2" <?php if ( $wpl_enable_custom_product_prices == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Hide for variations', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-enable_minmax_product_prices" class="text_label">
								<?php echo __( 'Enable min. / max. price fields', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If do not use minimum and maximum prices in Amazon and prefer less options when editing a product, you can disable these fields here.') ?>
							</label>
							<select id="wpl-enable_minmax_product_prices" name="wpla_enable_minmax_product_prices" class=" required-entry select">
								<option value="0" <?php if ( $wpl_enable_minmax_product_prices == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_enable_minmax_product_prices == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
								<option value="2" <?php if ( $wpl_enable_minmax_product_prices == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Hide for variations', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-enable_item_condition_fields" class="text_label">
								<?php echo __( 'Enable item condition fields', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If you only sell new item on Amazon and prefer less options when editing a product, you can disable these fields here.') ?>
							</label>
							<select id="wpl-enable_item_condition_fields" name="wpla_enable_item_condition_fields" class=" required-entry select">
								<option value="0" <?php if ( $wpl_enable_item_condition_fields == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_enable_item_condition_fields == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
								<option value="2" <?php if ( $wpl_enable_item_condition_fields == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Hide for variations', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
							</select>

							<label for="wpl-enable_categories_page" class="text_label">
								<?php echo __( 'Categories in main menu', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will add a <em>Categories</em> submenu entry visible to users who can manage listings.') ?>
							</label>
							<select id="wpl-enable_categories_page" name="wpla_enable_categories_page" class="required-entry select">
								<option value="0" <?php if ( $wpl_enable_categories_page != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_enable_categories_page == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this to make category settings available to users without access to other Amazon settings.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<label for="wpl-enable_accounts_page" class="text_label">
								<?php echo __( 'Accounts in main menu', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will add a <em>Accounts</em> submenu entry visible to users who can manage listings.') ?>
							</label>
							<select id="wpl-enable_accounts_page" name="wpla_enable_accounts_page" class="required-entry select">
								<option value="0" <?php if ( $wpl_enable_accounts_page != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_enable_accounts_page == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this to make account settings available to users without access to other Amazon settings.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<label for="wpl-enable_repricing_page" class="text_label">
								<?php echo __( 'Repricing Tool in main menu', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will add a <em>Repricing</em> submenu entry visible to users who can manage listings.') ?>
							</label>
							<select id="wpl-enable_repricing_page" name="wpla_enable_repricing_page" class="required-entry select">
								<option value="0" <?php if ( $wpl_enable_repricing_page != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_enable_repricing_page == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this to make the repricing tool available to users without access to other Amazon settings.', 'wp-lister-for-amazon' ); ?><br>
							</p>

                            <label for="wpl-display_product_counts" class="text_label">
                                <?php _e( 'Show Amazon product totals', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('This will display the total number of products <i>On Amazon</i> and <i>Not on Amazon</i> on the Products admin page in WooCommerce.<br><br>Please note: Enabling this option requires some complex database queries which might slow down loading the Products admin page.<br><br>If the Products page is taking too long to load, you should disable this option or move to a more powerful hosting/server.'); ?>
                            </label>
                            <select id="wpl-display_product_counts" name="wpla_display_product_counts" class="required-entry select">
                                <option value="0" <?php selected( $wpl_display_product_counts, 0 ); ?>><?php _e( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( $wpl_display_product_counts, 1 ); ?>><?php _e( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this to display the total number of products on Amazon / not on Amazon in WooCommerce.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div>

					<div class="postbox" id="RepricingSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Repricing Tool', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">


                            <label for="wpl-repricing_pricing_options" class="text_label">
                                <?php echo __( 'Enable pricing options', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Apply profile pricing options when repricing your listings') ?>
                            </label>
                            <select id="wpl-repricing_pricing_options" name="wpla_repricing_pricing_options" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_repricing_pricing_options, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( $wpl_repricing_pricing_options, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Enable this if you want the profile pricing option to be applied to the target price', 'wp-lister-for-amazon' ); ?>
                            </p>

							<label for="wpl-pricing_info_expiry_time" class="text_label">
								<?php echo __( 'Update lowest price info', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select the time after which the lowest price information is refreshed from Amazon for listings with status "online".<br><br>Note: The number of products that can have their pricing data updated per hour depends on the update interval or how often your external cron job runs.<br><br>With a cron job running every 5 minutes, WP-Lister for Amazon can update up to 2400 items per hour.') ?>
							</label>
							<select id="wpl-pricing_info_expiry_time" name="wpla_pricing_info_expiry_time" class=" required-entry select">
								<option value=""   <?php if ( $wpl_pricing_info_expiry_time == ''   ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Off', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value=".1" <?php if ( $wpl_pricing_info_expiry_time == '.1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 6 min.', 'wp-lister-for-amazon' ); ?></option>
								<option value=".2" <?php if ( $wpl_pricing_info_expiry_time == '.2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 12 min.', 'wp-lister-for-amazon' ); ?></option>
								<option value=".5" <?php if ( $wpl_pricing_info_expiry_time == '.5' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 30 min.', 'wp-lister-for-amazon' ); ?></option>
								<option value="1"  <?php if ( $wpl_pricing_info_expiry_time == '1'  ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every hour', 'wp-lister-for-amazon' ); ?></option>
								<option value="2"  <?php if ( $wpl_pricing_info_expiry_time == '2'  ): ?>selected="selected"<?php endif; ?>><?php echo sprintf( __( 'Every %s hours', 'wp-lister-for-amazon' ), 2);  ?></option>
								<option value="3"  <?php if ( $wpl_pricing_info_expiry_time == '3'  ): ?>selected="selected"<?php endif; ?>><?php echo sprintf( __( 'Every %s hours', 'wp-lister-for-amazon' ), 3);  ?></option>
								<option value="6"  <?php if ( $wpl_pricing_info_expiry_time == '6'  ): ?>selected="selected"<?php endif; ?>><?php echo sprintf( __( 'Every %s hours', 'wp-lister-for-amazon' ), 6);  ?></option>
								<option value="12" <?php if ( $wpl_pricing_info_expiry_time == '12' ): ?>selected="selected"<?php endif; ?>><?php echo sprintf( __( 'Every %s hours', 'wp-lister-for-amazon' ), 12); ?></option>
								<option value="24" <?php if ( $wpl_pricing_info_expiry_time == '24' ): ?>selected="selected"<?php endif; ?>><?php echo sprintf( __( 'Every %s hours', 'wp-lister-for-amazon' ), 24); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Select the time after which the lowest price information is refreshed.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-pricing_info_process_oos_items" class="text_label">
								<?php echo __( 'Update out of stock items', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Disable this option to skip out of stock items when fetching latest prices from Amazon.') ?>
							</label>
							<select id="wpl-pricing_info_process_oos_items" name="wpla_pricing_info_process_oos_items" class=" required-entry select">
								<option value="0" <?php if ( $wpl_pricing_info_process_oos_items == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_pricing_info_process_oos_items == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Disable this option to skip out of stock items when fetching latest prices.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-repricing_use_lowest_offer" class="text_label">
								<?php echo __( 'Upprice based on', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select whether only the Buy Box price should be checked - or whether the lowest offer / next competitor price should be used when you already have the Buy Box.') ?>
							</label>
							<select id="wpl-repricing_use_lowest_offer" name="wpla_repricing_use_lowest_offer" class=" required-entry select">
								<option value="0" <?php if ( $wpl_repricing_use_lowest_offer == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Buy Box only', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_repricing_use_lowest_offer == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Boy Box and Lowest Offer', 'wp-lister-for-amazon' ); ?> (recommeded)</option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Select if you want the lowest offer to be regarded when you already have the Buy Box.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-text-repricing_margin" class="text_label">
								<?php echo __( 'Repricing undercut', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Enter the amount you want to stay below your competitors lowest price.<br>Example: 0.01') ?>
							</label>
							<input type="text" name="wpla_repricing_margin" id="wpl-text-repricing_margin" value="<?php echo $wpl_repricing_margin; ?>" placeholder="0.00" class="text_input" />
							<p class="desc" style="display: block;">
								<?php echo __( 'Enter the amount you want to stay below your competitors lowest price.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-text-repricing_shipping" class="text_label">
                                <?php echo __( 'Repricing shipping fee', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Enter the amount that you use for shipping. This will be deducted from the final repriced value.<br>Example: 0.01') ?>
                            </label>
                            <input type="text" name="wpla_repricing_shipping" id="wpl-text-repricing_shipping" value="<?php echo $wpl_repricing_shipping; ?>" placeholder="0.00" class="text_input" />
							<p class="desc" style="display: block;">
								<?php echo __( 'Enter your default shipping fee to deduct from the final repriced value.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-external_repricer_mode" class="text_label">
								<?php echo __( 'Enable external repricing', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('This will make WP-Lister stop sending price updates to Amazon.<br>You should only enable this if you want to use an external repricing tool or service for all your products.') ?>
							</label>
							<select id="wpl-external_repricer_mode" name="wpla_external_repricer_mode" class=" required-entry select">
								<option value="0" <?php if ( $wpl_external_repricer_mode == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_external_repricer_mode == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes, enable for all products', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you want already use an external repricing tool for all your products.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-repricing_table_show_quantity_source" class="text_label">
                                <?php echo __( 'Show FBA and Total Stocks', 'wp-lister-for-amazon' ) ?>
                            </label>
                            <select id="wpl-repricing_table_show_quantity_source" name="wpla_repricing_table_show_quantity_source" class=" required-entry select">
                                <option value="0" <?php selected( 0, $wpl_repricing_table_show_quantity_source ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( 1, $wpl_repricing_table_show_quantity_source ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Enable this if you want to see both the Total and FBA quantities in the Repricing Table', 'wp-lister-for-amazon' ); ?>
                            </p>

						</div>
					</div>

					<div class="postbox" id="ImportSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Import', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-import_parent_category_id" class="text_label">
								<?php echo __( 'Product category', 'wp-lister-for-amazon' ); ?>
	                            <?php wpla_tooltip('If you want to assign a product category when importing products from Amazon, select your category here.<br><br>Note: This option applies when the import queue is processed (import step 2).') ?>
							</label>
							<select id="wpl-import_parent_category_id" name="wpla_import_parent_category_id" class=" required-entry select">
								<option value="">-- <?php echo __( 'top level', 'wp-lister-for-amazon' ); ?> --</option>
							<?php

					            // get categories
								$tax_slug = 'product_cat';
								$tax_obj  = get_taxonomy( $tax_slug );
								$tax_name = $tax_obj->labels->name;
								$terms    = get_terms( $tax_slug, array( 'hide_empty' => false ) );

					            // output html for taxonomy dropdown filter
					            foreach ($terms as $term) {
					                $selected = $wpl_import_parent_category_id == $term->term_id ? ' selected="selected"' : '';
					                echo '<option value="'. $term->term_id . '" ' . $selected . '>' . $term->name . '</option>';
					            }
					        ?>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Select the product category for products imported from Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-enable_variation_image_import" class="text_label">
								<?php echo __( 'Import variation images', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Variation images are imported by default. If you get timeout errors when importing large variable products from Amazon, you might have to disable this or increase your <code>max_execution_time</code> PHP setting.') ?>
							</label>
							<select id="wpl-enable_variation_image_import" name="wpla_enable_variation_image_import" class=" required-entry select">
								<option value="1" <?php if ( $wpl_enable_variation_image_import == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="0" <?php if ( $wpl_enable_variation_image_import == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
							</select>

                            <label for="wpl-variation_image_to_gallery" class="text_label">
                                <?php echo __( 'Variation image to gallery', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Add imported variation images to the product gallery.') ?>
                            </label>
                            <select id="wpl-variation_image_to_gallery" name="wpla_variation_image_to_gallery" class=" required-entry select">
                                <option value="1" <?php selected( 1, $wpl_variation_image_to_gallery ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="0" <?php selected( 0, $wpl_variation_image_to_gallery ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                            </select>
							<label for="wpl-enable_gallery_images_import" class="text_label">
								<?php echo __( 'Import additional images', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('All product images are imported by default. If you get timeout errors when importing large variable products from Amazon, you might have to only import the main image or increase your <code>max_execution_time</code> PHP setting.') ?>
							</label>
							<select id="wpl-enable_gallery_images_import" name="wpla_enable_gallery_images_import" class=" required-entry select">
								<option value="1" <?php if ( $wpl_enable_gallery_images_import == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="0" <?php if ( $wpl_enable_gallery_images_import == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No, only import main image', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-import_images_subfolder_level" class="text_label">
								<?php echo __( 'Create image subfolders', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If you import a large number of products, enable this option to lower the number of images per folder.') ?>
							</label>
							<select id="wpl-import_images_subfolder_level" name="wpla_import_images_subfolder_level" class=" required-entry select">
								<option value="0" <?php if ( $wpl_import_images_subfolder_level == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No subfolders', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_import_images_subfolder_level == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( '1 level', 'wp-lister-for-amazon' ); ?></option>
								<option value="2" <?php if ( $wpl_import_images_subfolder_level == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( '2 levels', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-text-import_images_basedir_name" class="text_label">
								<?php echo __( 'Image base folder', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('This folder will be created in /wp-content/uploads/ and will hold images imported from Amazon.') ?>
							</label>
							<input type="text" name="wpla_import_images_basedir_name" id="wpl-text-import_images_basedir_name" value="<?php echo $wpl_import_images_basedir_name; ?>" placeholder="imported/" class="text_input" />

                            <label for="wpl-display_condition_and_notes" class="text_label">
                                <?php echo __( 'Display condition and notes', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Display imported product condition and condition notes in the product page') ?>
                            </label>
                            <select id="wpl-display_condition_and_notes" name="wpla_display_condition_and_notes" class=" required-entry select">
                                <option value="1" <?php selected( $wpl_display_condition_and_notes, 1 ); ?>><?php _e( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                                <option value="0" <?php selected( $wpl_display_condition_and_notes, 0 ); ?>><?php _e( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                            </select>

                            <label for="wpl-disable_unit_conversion" class="text_label">
                                <?php echo __( 'Disable unit conversion', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Set to "Yes" to stop WP-Lister from automatically setting the weight and dimension units to <em>lbs</em> and <em>in</em> when importing products into WooCommerce.') ?>
                            </label>
                            <select id="wpl-disable_unit_conversion" name="wpla_disable_unit_conversion" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_disable_unit_conversion, 0 ); ?>><?php _e( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( $wpl_disable_unit_conversion, 1 ); ?>><?php _e( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
						</div>
					</div>

					<div class="postbox" id="ReportSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Reports', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-autofetch_listing_quality_feeds" class="text_label">
								<?php echo __( 'Fetch listing quality data', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Automatically request and process a daily listing quality report.') ?>
							</label>
							<select id="wpl-autofetch_listing_quality_feeds" name="wpla_autofetch_listing_quality_feeds" class=" required-entry select">
								<option value="1" <?php if ( $wpl_autofetch_listing_quality_feeds == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
								<option value="0" <?php if ( $wpl_autofetch_listing_quality_feeds == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Automatically request and process a daily listing quality report.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<label for="wpl-autofetch_inventory_report" class="text_label">
								<?php echo __( 'Process daily inventory report', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Automatically request and process a daily inventory report and update WooCommerce products using the current import options.<br><br>Note: It is <i>not recommended</i> to use this option and we can not provide any support for issues that might arise from enabling it.<br><br>If you are currently using this option to pull changes from Amazon into WP-Lister (for example price updates from a 3rd party repricing tool), please consider applying your changes to WooCommerce directly, using the WooCommerce REST API.') ?>
							</label>
							<select id="wpl-autofetch_inventory_report" name="wpla_autofetch_inventory_report" class=" required-entry select">
								<option value="1" <?php if ( $wpl_autofetch_inventory_report == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('not recommended', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="0" <?php if ( $wpl_autofetch_inventory_report == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
							</select>
							<p class="desc" style="display: block;">
								<?php //echo __( 'Enable this to update products in WooCommerce daily, based on their status on Amazon.', 'wp-lister-for-amazon' ); ?><!br>
								<?php echo __( 'Warning: Inventory reports might contain outdated information. Use this option on your own risk!', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-autofetch_order_report_data" class="text_label">
                                <?php echo __( 'Process daily order report data', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Automatically request and process a daily order report data for invoicing which pulls the Business Tax ID for business orders.') ?>
                            </label>
                            <select id="wpl-autofetch_order_report_data" name="wpla_autofetch_order_report_data" class=" required-entry select">
                                <option value="0" <?php selected( 0, $wpl_autofetch_order_report ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( 1, $wpl_autofetch_order_report ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>

                            <label for="wpl-autosubmit_inventory_feeds" class="text_label">
                                <?php echo __( 'Automatically Submit Inventory Feeds', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Automatically submit inventory feeds as soon as they are generated.') ?>
                            </label>
                            <select id="wpl-autosubmit_inventory_feeds" name="wpla_autosubmit_inventory_feeds" class=" required-entry select">
                                <option value="1" <?php selected( $wpl_autosubmit_inventory_feeds, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                                <option value="0" <?php selected( $wpl_autosubmit_inventory_feeds, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Enable this if you are overselling due to inventory feeds from WooCommerce sales not getting sent to Amazon fast enough.', 'wp-lister-for-amazon' ); ?><!br>
                            </p>

                            <label for="wpl-case_sensitive_sku_matching" class="text_label">
                                <?php echo __( 'Enable case-sensitive SKU matching ', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('SKU matching in reports are not case sensitive by default.' ) ?>
                            </label>
                            <select id="wpl-case_sensitive_sku_matching" name="wpla_case_sensitive_sku_matching" class=" required-entry select">
                                <option value="1" <?php selected( 1, $wpl_case_sensitive_sku_matching ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                                <option value="0" <?php selected( 0, $wpl_case_sensitive_sku_matching ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Warning: Your database needs to have the UTFMB4_BIN character set for this setting to work. If you get database errors with this setting enabled, please contact your host to have the utf8mb4_bin charset added for you.', 'wp-lister-for-amazon' ); ?>
                            </p>

						</div>
					</div>

					<div class="postbox" id="OtherSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Misc Options', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">


                            <label for="wpl-disable_sale_price" class="text_label">
                                <?php echo __( 'Use sale price', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Set this to No if you want your sale prices to be ignored.') ?>
                            </label>
                            <select id="wpl-disable_sale_price" name="wpla_disable_sale_price" class="required-entry select">
                                <option value="0"  <?php selected( $wpl_disable_sale_price, 0 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1"  <?php selected( $wpl_disable_sale_price, 1 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Should sale prices be used automatically on Amazon?', 'wp-lister-for-amazon' ); ?><br>
                            </p>

                            <label for="wpl-fallback_to_stock_status" class="text_label">
                                <?php echo __( 'Fallback to Stock Status', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If the products have Manage Stock disabled, enable this option to rely on its Stock Status instead.') ?>
                            </label>
                            <select id="wpl-fallback_to_stock_status" name="wpla_fallback_to_stock_status" class="required-entry select">
                                <option value="0"  <?php selected( $wpl_fallback_to_stock_status, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1"  <?php selected( $wpl_fallback_to_stock_status, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'If enabled and if Manage Stock is disabled for a product, WP-Lister sends a quantity of 1 if the stock status is In Stock, and 0 for Out of Stock or On Backorder.', 'wp-lister-for-amazon' ); ?><br>
                            </p>

                            <label for="wpl-allow_listing_drafts" class="text_label">
                                <?php echo __( 'Allow listing drafts', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Set this to Yes to allow matching and listing draft products to Amazon.') ?>
                            </label>
                            <select id="wpl-allow_listing_drafts" name="wpla_allow_listing_drafts" class="required-entry select">
                                <option value="0"  <?php selected( $wpl_allow_listing_drafts, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1"  <?php selected( $wpl_allow_listing_drafts, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Enable to push product drafts to Amazon', 'wp-lister-for-amazon' ); ?><br>
                            </p>

							<label for="wpl-product_gallery_fallback" class="text_label">
								<?php echo __( 'Product Gallery Mode', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('In order to find additional product images, WP-Lister first checks if there is a WooCommerce <i>Product Gallery</i>.<br>
                                						If there\'s none, it can use all images which were uploaded (attached) to the product, which might be required for 3rd party gallery plugins.') ?>
							</label>
							<select id="wpl-product_gallery_fallback" name="wpla_product_gallery_fallback" class="required-entry select">
								<option value="none"     <?php selected( $wpl_product_gallery_fallback, 'none' ); ?>><?php echo __( 'Use Product Gallery images', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="attached" <?php selected( $wpl_product_gallery_fallback, 'attached' ); ?>><?php echo __( 'Use attached images if no Product Gallery found', 'wp-lister-for-amazon' ); ?></option>
								<option value="ignore"   <?php selected( $wpl_product_gallery_fallback, 'ignore' ); ?>><?php echo __( 'Don\'t send images', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-product_gallery_first_image" class="text_label">
								<?php echo __( 'First gallery image', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If your product gallery in WooCommerce contains the features image as the first image, you can choose to skip the first image to avoid duplicate images on Amazon.') ?>
							</label>
							<select id="wpl-product_gallery_first_image" name="wpla_product_gallery_first_image" class=" required-entry select">
								<option value=""     <?php if ( $wpl_product_gallery_first_image != 'skip' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Use all gallery images', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="skip" <?php if ( $wpl_product_gallery_first_image == 'skip' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Skip first gallery image', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-variation_main_image_fallback" class="text_label">
								<?php echo __( 'Variation main image', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If a child variation has no dedicated product image, the default setting is to use the featured image from the parent product.<br><br>Disable this option if you are experiencing issues with identical swatch images.') ?>
							</label>
							<select id="wpl-variation_main_image_fallback" name="wpla_variation_main_image_fallback" class="required-entry select">
								<option value="parent" <?php if ( $wpl_variation_main_image_fallback == 'parent' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Use featured image from parent product', 'wp-lister-for-amazon' ); ?> (<?php echo __('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="none"   <?php if ( $wpl_variation_main_image_fallback == 'none'   ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Leave empty if no variation image is set', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-enable_out_of_stock_threshold" class="text_label">
								<?php echo __( 'Out Of Stock Threshold', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Enable this to automatically reduce the quantity sent to Amazon by the value you entered as "Out Of Stock Threshold" in WooCommerce.') ?>
							</label>
							<select id="wpl-enable_out_of_stock_threshold" name="wpla_enable_out_of_stock_threshold" class="required-entry select">
								<option value="0"  <?php if ( $wpl_enable_out_of_stock_threshold == '0' ):  ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1"  <?php if ( $wpl_enable_out_of_stock_threshold == '1' ):  ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you use the "Out Of Stock Threshold" option in WooCommerce.', 'wp-lister-for-amazon' ); ?><br>
							</p>

                            <label for="wpl-conditional_order_item_updates" class="text_label">
                                <?php echo __( 'Conditional Order Item Updates', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Set to "Yes" to have WP-Lister skip order line item updates for orders that have already been shipped or completed.<br><br>Enable this if you are experiencing throttling issues from Amazon while processing a large number of orders.') ?>
                            </label>
                            <select id="wpl-conditional_order_item_updates" name="wpla_conditional_order_item_updates" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_conditional_order_item_updates, 0 ); ?>><?php _e( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( $wpl_conditional_order_item_updates, 1 ); ?>><?php _e( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you are experiencing throttling issues while processing orders from Amazon.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<label for="wpl-process_shortcodes" class="text_label">
								<?php echo __( 'Shortcode processing', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Enable this to run your product description through the usual WordPress content filters which enables you to use shortcodes in your product description on Amazon.<br>If a plugin causes trouble by adding unwanted HTML to your description on Amazon, you should try the default setting "off".') ?>
							</label>
							<select id="wpl-process_shortcodes" name="wpla_process_shortcodes" class="required-entry select">
								<option value="off"          <?php if ( $wpl_process_shortcodes == 'off' ):          ?>selected="selected"<?php endif; ?>><?php echo __( 'Off', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="the_content"  <?php if ( $wpl_process_shortcodes == 'the_content' ):  ?>selected="selected"<?php endif; ?>><?php echo __( 'Process shortcodes', 'wp-lister-for-amazon' ); ?> - the_content()</option>
								<option value="do_shortcode" <?php if ( $wpl_process_shortcodes == 'do_shortcode' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Process shortcodes', 'wp-lister-for-amazon' ); ?> - do_shortcode()</option>
								<option value="remove_all"   <?php if ( $wpl_process_shortcodes == 'remove_all' ):   ?>selected="selected"<?php endif; ?>><?php echo __( 'Remove all shortcodes from description', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you want to use or remove WordPress shortcodes in your product description.', 'wp-lister-for-amazon' ); ?><br>
							</p>

                            <label for="wpl-shortcode_do_autop" class="text_label">
                                <?php echo __( 'Convert line breaks to paragraphs', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('All line breaks in the product description are converted into paragraphs by default for a cleaner look.') ?>
                            </label>
                            <select id="wpl-shortcode_do_autop" name="wpla_shortcode_do_autop" class="required-entry select">
                                <option value="1" <?php selected( $wpl_shortcode_do_autop, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon'); ?>)</option>
                                <option value="0" <?php selected( $wpl_shortcode_do_autop, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                            </select>

							<label for="wpl-remove_links" class="text_label">
								<?php echo __( 'Link handling', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Should links within the product description be replaced with plain text?') ?>
							</label>
							<select id="wpl-remove_links" name="wpla_remove_links" class="required-entry select">
								<option value="default"   <?php if ( $wpl_remove_links == 'default'   ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Remove all links from description', 'wp-lister-for-amazon' ); ?></option>
								<option value="allow_all" <?php if ( $wpl_remove_links == 'allow_all' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Allow all links', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Links are removed from product descriptions by default.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-variation_title_mode" class="text_label">
								<?php echo __( 'Variation title', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('This option controls whether attribute values will show up in variation listing titles.') ?>
							</label>
							<select id="wpl-variation_title_mode" name="wpla_variation_title_mode" class="required-entry select">
								<option value="default"   <?php if ( $wpl_variation_title_mode == 'default'   ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Default', 'wp-lister-for-amazon' ); ?></option>
								<option value="parent"    <?php if ( $wpl_variation_title_mode == 'parent'    ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Use parent title without attributes', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-profile_editor_mode" class="text_label">
								<?php echo __( 'Profile editor mode', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Expert mode will enable you to edit quantity and image fields in your listing profile.<br><br>Please leave this option at <i>Default</i> unless told otherwise by support.') ?>
							</label>
							<select id="wpl-profile_editor_mode" name="wpla_profile_editor_mode" class="required-entry select">
								<option value="default"   <?php if ( $wpl_profile_editor_mode == 'default'   ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Default', 'wp-lister-for-amazon' ); ?></option>
								<option value="expert"    <?php if ( $wpl_profile_editor_mode == 'expert'    ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Expert Mode (show all hidden fields)', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<label for="wpl-convert_content_nl2br" class="text_label">
								<?php echo __( 'Convert newline to &lt;br/&gt; tags', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Converts all newline characters in profile shortcodes into &lt;br/&gt; elements.') ?>
							</label>
                            <select id="wpl-convert_content_nl2br" name="wpla_convert_content_nl2br" class="required-entry select">
                                <option value="1"   <?php selected( $wpl_convert_content_nl2br, 1 ); ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="0"   <?php selected( $wpl_convert_content_nl2br, 0 ); ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                            </select>

                            <label for="wpl-text-allowed_html_tags" class="text_label">
                                <?php echo __( 'Allowed HTML tags', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('These HTML tags will be allowed in the listing description sent to Amazon - all other tags will be removed.') ?>
                            </label>
                            <input type="text" name="wpla_allowed_html_tags" id="wpl-text-allowed_html_tags" value="<?php echo $wpl_allowed_html_tags; ?>" class="text_input" />
                            <p class="desc" style="display: block;">
                                For more information see <a href="https://www.amazon.com/gp/help/customer/display.html?ie=UTF8&nodeId=200441900" target="_blank">Allowed HTML Tags and CSS Attributes</a>.
                                Default: <code>&lt;b&gt;&lt;i&gt;</code>
                            </p>

                            <label for="wpl-keyword-fields-type" class="text_label">
                                <?php echo __( 'Keyword Fields', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip( '' ); ?>
                            </label>
                            <select name="wpla_keyword_fields_type" id="wpl-keyword-fields-type" class="required select">
                                <option value="single" <?php selected( $wpl_keyword_fields_type, 'single' ); ?>><?php _e( 'Single search term field', 'wp-lister-for-amazon' ); ?></option>
                                <option value="separate" <?php selected( $wpl_keyword_fields_type, 'separate' ); ?>><?php _e( 'Separate keywords fields', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                            </select>

						</div>
					</div>

                    <div class="postbox adv_options" id="ShipFromAddressBox">
                        <h3 class="hndle"><span><?php echo __( 'Ship From Address Templates', 'wp-lister-for-amazon' ); ?></span></h3>
                        <div class="inside">
                            <label for="wpl-ship-from-default-address" class="text_label">
                                <?php echo __( 'Default Ship-From address', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip( 'If one is selected, it will be the address that is selected by default in the Edit Order screen for Amazon orders.' ); ?>
                            </label>
                            <select name="wpla_ship_from_default_address" id="wpl-ship_from_default_address" class="required select">
                                <option value="" <?php selected( $wpl_ship_from_default_address, '' ); ?>><?php _e( '-- no default address --', 'wp-lister-for-amazon' ); ?></option>
                                <?php foreach ( $wpl_ship_from_addresses as $i => $address ): ?>
                                    <option value="<?php esc_attr_e( $address['name'] ); ?>" <?php selected( $wpl_ship_from_default_address, $address['name'] ); ?>><?php esc_attr_e( $address['name'] ); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <p class="x-desc" style="display: block;">
                                <?php echo __( 'List your Ship From templates below so you can easily assign them to your orders while marking them as shipped.', 'wp-lister-for-amazon' ); ?>
                            </p>

                            <table id="ship-from-addresses-table">
                                <thead>
                                <tr>
                                    <th><?php echo __( 'Address Template name*', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'Address line 1*', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'Address line 2', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'City', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'State', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'Postal Code', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'Country*', 'wp-lister-for-amazon' ); ?></th>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="ship_from_address_tbody">
                                <?php foreach ( $wpl_ship_from_addresses as $i => $address ): ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="ship_from_addresses[name][<?php echo $i; ?>]" value="<?php echo $address['name'] ?>" class="text_input address-name" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[line_1][<?php echo $i; ?>]" value="<?php echo $address['line_1'] ?>" class="text_input" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[line_2][<?php echo $i; ?>]" value="<?php echo $address['line_2'] ?>" class="text_input" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[city][<?php echo $i; ?>]" value="<?php echo $address['city'] ?>" class="text_input" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[state][<?php echo $i; ?>]" value="<?php echo $address['state'] ?>" class="text_input" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[postal][<?php echo $i; ?>]" value="<?php echo $address['postal'] ?>" class="text_input" />
                                        </td>
                                        <td>
                                            <input type="text" name="ship_from_addresses[country][<?php echo $i; ?>]" value="<?php echo $address['country'] ?>" class="text_input" />
                                        </td>
                                        <td class="delete"><button class="button button-link-delete ship_from_delete_address" data-address_id="<?php echo $i; ?>">&cross;</button></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td>
                                        <input type="text" name="ship_from_addresses[name][]" value="" class="text_input address-name" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[line_1][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[line_2][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[city][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[state][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[postal][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" name="ship_from_addresses[country][]" value="" class="text_input" size="5" maxlength="2" />
                                    </td>
                                    <td class="delete"><button class="button button-link-delete ship_from_delete_address">&cross;</button></td>
                                </tr>
                                </tbody>
                            </table>

                            <p><button id="ship_from_add_address" class="button-secondary button">Add New</button></p>

                        </div>
                    </div> <!-- postbox -->





					<div class="postbox adv_options" id="ColorMapBox">
						<h3 class="hndle"><span><?php echo __( 'Map Variation Colors', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<table id="color-map-table">
								<tr>
									<th><?php echo __( 'WooCommerce color', 'wp-lister-for-amazon' ); ?></th>
									<th><?php echo __( 'Amazon color', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php

									$wpl_colormap_woocom = array_keys  ( $wpl_variation_color_map );
									$wpl_colormap_amazon = array_values( $wpl_variation_color_map );

									$amazon_color_values = apply_filters( 'wpla_amazon_color_values', array(
										'Beige',
										'Black',
										'Blue',
										'Bronze',
										'Brown',
										'Clear',
										'Gold',
										'Green',
										'Grey',
										'Metallic',
										'Multi',
										'Off-White',
										'Orange',
										'Pink',
										'Purple',
										'Red',
										'Silver',
										'Transparent',
										'Turquoise',
										'White',
										'Yellow',
                                        'Beige', 'Blau', 'Braun', 'Elfenbein', 'Gelb', 'Gold', 'Grau',	'Grün',
                                        'Mehrfarbig', 'Orange', 'Rosa',	'Rot', 'Schwarz', 'Silber', 'Türkis', 'Violett',
                                        'Weiß',	'Bronze',	'Lila',	'Durchsichtig',	'Cremefarben',	'Metallisch',
									) );

								?>

								<?php
                                for ($i=0; $i < sizeof($wpl_variation_color_map); $i++) :
                                    //if ( !isset( $wpl_colormap_amazon[ $i ] ) ) continue;
                                ?>
								<tr>
									<td>
										<input type="text" name="colormap_woocom[]" value="<?php echo @$wpl_colormap_woocom[$i]; ?>" class="text_input" />
									</td>
									<td>
                                        <input type="text" class="text_input color-map-input" name="colormap_amazon[]" value="<?php esc_attr_e( @$wpl_colormap_amazon[$i] ); ?>" />
										<!--<select name="colormap_amazon[]" class="select">
											<option value=""      				<?php if ( @$wpl_colormap_amazon[$i] == ''      				): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select Amazon color', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($amazon_color_values as $clr) : ?>
												<option value="<?php echo $clr ?>"   <?php if ( $wpl_colormap_amazon[$i] == $clr ):   ?>selected="selected"<?php endif; ?>><?php echo $clr ?></option>
							                <?php endforeach; ?>
										</select>-->
									</td>
								</tr>
								<?php endfor; ?>
								<tr>
									<td>
										<input type="text" name="colormap_woocom[]" value="" class="text_input" />
									</td>
									<td>
                                        <input type="text" class="text_input color-map-input" name="colormap_amazon[]" value="" />
									</td>
								</tr>
							</table>

							<p class="x-desc" style="display: block;">
								<?php echo __( 'Here you can map your custom colors to standard colors expected by Amazon in the <i>color_map</i> column.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->

					<div class="postbox adv_options" id="SizeMapBox">
						<h3 class="hndle"><span><?php echo __( 'Map Variation Sizes', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">
                            <label class="text_label" for="sizemap_excluded_markets"><?php _e( 'Exclude these markets from mapping:', 'wp-lister-for-amazon' ); ?></label>
                            <select name="sizemap_excluded_markets[]" id="sizemap_excluded_markets" multiple class="selectWoo required-entry select" data-placeholder="<?php _e('Select markets', 'wp-lister-for-amazon' ); ?>">
                                <?php
                                $markets = WPLA_AmazonMarket::getAll();
                                foreach ( $markets as $market ):
                                ?>
                                <option value="<?php esc_attr_e( $market->code ); ?>" <?php selected( true, in_array( $market->code, $wpl_sizemap_excluded_markets ) ); ?> ><?php esc_html_e( $market->code ); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <script>
                                jQuery(".selectWoo").selectWoo();
                            </script>

                            <p class="x-desc" style="display: block;">
                                <?php echo __( 'Here you can map your custom sizes to standard sizes expected by Amazon in the <i>size_map</i> column.', 'wp-lister-for-amazon' ); ?>
                            </p>

							<table id="size-map-table">
								<tr>
									<th><?php echo __( 'WooCommerce size', 'wp-lister-for-amazon' ); ?></th>
									<th><?php echo __( 'Amazon size', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php

									$wpl_sizemap_woocom = array_keys  ( $wpl_variation_size_map );
									$wpl_sizemap_amazon = array_values( $wpl_variation_size_map );

									$amazon_size_values = apply_filters( 'wpla_amazon_size_values', array(
                                        '0 Months', '3 Months', '6 Months', '9 Months', '12 Months', '15 Months', '18 Months', '21 Months', '24 Months',
                                        '2 Years', '2.5 Years', '3 Years', '4 Years', '4.5 Years', '5 Years',
                                        'One Size', '1X', '2X', '3X', '4X', '5X', '6X', '7X', '9X', '10X',
                                        '6X-Small', '5X-Small', 'XXXXX-Small', '4X-Small', 'XXXX-Small', '3X-Small', 'XXX-Small',
                                        'XX-Small', 'X-Small', 'Small', 'Medium',
										'Large', 'X-Large',	'XX-Large',	'XXX-Large', '3X-Large', 'XXXX-Large', 'XXXXX-Large',
                                        '4X-Large', '5X-Large', '6X-Large', '7X-Large', '8X-Large', '9X-Large', '10X-Large',
                                        '6XS', '5XS', '4XS', '3XS',	'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL', '7XL', '8XL', '9XL', '10XL',
                                        '0', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5', '5.5', '6', '6.5', '7', '7.5',
                                        '8', '8.5', '9', '9.5', '10', '10.5', '11', '11.5', '12', '12.5', '13', '13.5', '14', '14.5',
                                        '15', '15.5', '16', '16.5', '17', '17.5', '18', '18.5', '19', '19.5', '20', '21', '22', '23', '24',
                                        '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
                                        '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58',
                                        '59', '60', '61', '62', '63', '64', '65', '66',
									) );

								?>

								<?php for ($i=0; $i < sizeof($wpl_variation_size_map); $i++) : ?>
								<tr>
									<td>
										<input type="text" name="sizemap_woocom[]" value="<?php echo @$wpl_sizemap_woocom[$i]; ?>" class="text_input" />
									</td>
									<td>
                                        <input type="text" class="text_input size-map-input" name="sizemap_amazon[]" value="<?php esc_attr_e( $wpl_sizemap_amazon[$i] ); ?>" />
									</td>
								</tr>
								<?php endfor; ?>
								<tr>
									<td>
										<input type="text" name="sizemap_woocom[]" value="" class="text_input" />
									</td>
									<td>
                                        <input type="text" class="text_input size-map-input" name="sizemap_amazon[]" value="" />
									</td>
								</tr>
							</table>

                            <h4>Custom Size Mapping</h4>

                            <p class="x-desc">
                                <?php _e( 'Create different size maps for the different size fields being used by Amazon.', 'wp-lister-for-amazon' ); ?>
                            </p>

                            <div id="custom_size_maps_container">

                                <?php
                                foreach ( $wpl_custom_size_map as $field => $map ):
                                ?>
                                <div class="size-map-block">
                                    <h4>
                                        <input type="text" name="custom_sizemap[<?php echo $field; ?>][field]" value="<?php echo esc_attr( $field ); ?>" placeholder="Size field (e.g. apparel_size)" />
                                        <a href="#" class="button btn_add_map_row" id="map-row-btn">+</a>
                                    </h4>

                                    <table id="field_<?php echo $field; ?>" data-field="<?php echo $field; ?>" class="size-map-table">
                                        <tr>
                                            <th><?php echo __( 'WooCommerce size', 'wp-lister-for-amazon' ); ?></th>
                                            <th><?php echo __( 'Amazon size', 'wp-lister-for-amazon' ); ?></th>
                                        </tr>

                                        <?php foreach ( $map as $wc_size => $amz_size ): ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="custom_sizemap[<?php echo $field; ?>][wc_sizes][]" value="<?php echo $wc_size; ?>" class="text_input" />
                                                </td>
                                                <td>
                                                    <input type="text" name="custom_sizemap[<?php echo $field; ?>][amazon_sizes][]" class="text_input size-map-input" placeholder="Select or enter a custom value" value="<?php esc_attr_e( $amz_size ); ?>" />
                                                    <a href="#" class="button delete-map-row button-link-delete">&cross;</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    </table>

                                </div>
                                <?php endforeach; ?>
                            </div>

                            <p><a href="#" id="btn_new_mapping" class="button-secondary button"><?php _e( 'Add New Mapping', 'wp-lister-for-ebay' ); ?></a></p>

						</div>
					</div> <!-- postbox -->
                    <script type="text/html" id="map_block_tpl">
                        <div class="size-map-block">
                            <h4>
                                <input type="text" name="custom_sizemap[__FIELD__][field]" value="" placeholder="Size field (e.g. apparel_size)" />
                                <a href="#" class="button btn_add_map_row" id="map-row-btn">+</a>
                            </h4>

                            <table id="field___FIELD__" data-field="__FIELD__" class="size-map-table">
                                <tr>
                                    <th><?php echo __( 'WooCommerce size', 'wp-lister-for-amazon' ); ?></th>
                                    <th><?php echo __( 'Amazon size', 'wp-lister-for-amazon' ); ?></th>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="text" name="custom_sizemap[__FIELD__][wc_sizes][]" value="" class="text_input" />
                                    </td>
                                    <td>
                                        <input type="text" class="text_input size-map-input" name="custom_sizemap[__FIELD__][amazon_sizes][]" placeholder="<?php _e( 'Select or enter a custom value', 'wp-lister-for-amazon' ); ?>" />
                                        <a href="#" class="button delete-map-row button-link-delete">&cross;</a>
                                    </td>
                                </tr>

                            </table>

                        </div>
                    </script>
                    <script type="text/html" id="map_row_tpl">
                        <tr>
                            <td>
                                <input type="text" name="custom_sizemap[__FIELD__][wc_sizes][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" class="text_input size-map-input" name="custom_sizemap[__FIELD__][amazon_sizes][]" placeholder="<?php _e( 'Select or enter a custom value', 'wp-lister-for-amazon' ); ?>" />
                                <a href="#" class="button delete-map-row button-link-delete">&cross;</a>
                            </td>
                        </tr>
                    </script>
                    <script type="text/html" id="ship_from_address_tpl">
                        <tr>
                            <td>
                                <input type="text" name="ship_from_addresses[name][]" value="" class="text_input address-name" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[line_1][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[line_2][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[city][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[state][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[postal][]" value="" class="text_input" />
                            </td>
                            <td>
                                <input type="text" name="ship_from_addresses[country][]" value="" class="text_input" size="5" maxlength="2" />
                            </td>
                            <td class="delete"><button class="button button-link-delete ship_from_delete_address">&cross;</button></td>
                        </tr>
                    </script>
                    <script>
                        jQuery(document).ready(function() {
                            jQuery( '#SizeMapBox' )
                                .on( 'click', 'a.btn_add_map_row', function( e ) {
                                    e.preventDefault();
                                    const field = jQuery(this).parents('.size-map-block').find('table.size-map-table').data('field');

                                    let tpl = jQuery('#map_row_tpl').html();
                                    tpl = tpl.replaceAll( '__FIELD__', field );


                                    //jQuery(tpl).find('.size-map-input').autocomplete({source: size_map_values});


                                    const container = jQuery(this).parents('.size-map-block').find('table');
                                    jQuery(container).append( tpl );
                                    jQuery(container).find( '.size-map-input:not(.ui-autocomplete-input)' ).autocomplete({source: size_map_values});
                                } )
                                .on( 'click', 'a.delete-map-row', function( e ) {
                                    e.preventDefault();

                                    jQuery( this ).parents( 'tr' ).remove();
                                })
                                .on( 'click', 'a#btn_new_mapping', function( e ) {
                                    e.preventDefault();
                                    let tpl = jQuery('#map_block_tpl').html();
                                    const field = generate_tmp_field();

                                    tpl = tpl.replaceAll( '__FIELD__', field );
                                    jQuery('#custom_size_maps_container').append( tpl );
                                    jQuery('#custom_size_maps_container').find( '.size-map-input:not(.ui-autocomplete-input)' ).autocomplete({source: size_map_values});
                                });

                        });

                        function generate_tmp_field() {
                            let tmp = generate_number();

                            do {
                                tmp = generate_number();
                            } while ( jQuery( 'table#field_'+ tmp).length > 0 );

                            return tmp;
                        }

                        function generate_number() {
                            return Math.floor( Math.random() * ( 1 + 100000 - 1 ) ) + 1;
                        }
                    </script>






					<div class="postbox adv_options" id="VariationAttributesBox">
						<h3 class="hndle"><span><?php echo __( 'Map Variation Attributes', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<table id="variation-attributes-table">
								<tr>
									<th><?php echo __( 'WooCommerce attribute', 'wp-lister-for-amazon' ); ?></th>
									<th><?php echo __( 'Amazon attribute', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php

									$wpl_varmap_woocom = array_keys  ( $wpl_variation_attribute_map );
									$wpl_varmap_amazon = array_values( $wpl_variation_attribute_map );

								?>

								<?php for ($i=0; $i < sizeof($wpl_variation_attribute_map); $i++) : ?>
								<tr>
									<td>
										<select name="varmap_woocom[]" class="select">
											<option value=""      <?php if ( $wpl_varmap_woocom[$i] == ''      ): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>"   <?php if ( $wpl_varmap_woocom[$i] == $attribute->label ):   ?>selected="selected"<?php endif; ?>><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td>
										<select name="varmap_amazon[]" class="select">
											<option value=""      				<?php if ( @$wpl_varmap_amazon[$i] == ''      				): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select Amazon attribute', 'wp-lister-for-amazon' ); ?> --</option>
											<option value="Size"  				<?php if ( @$wpl_varmap_amazon[$i] == 'Size'  				): ?>selected="selected"<?php endif; ?>><?php echo 'Size' ?></option>
											<option value="Color" 				<?php if ( @$wpl_varmap_amazon[$i] == 'Color' 				): ?>selected="selected"<?php endif; ?>><?php echo 'Color' ?></option>
											<option value="Material" 			<?php if ( @$wpl_varmap_amazon[$i] == 'Material' 			): ?>selected="selected"<?php endif; ?>><?php echo 'Material' ?></option>
											<option value="Flavor"				<?php if ( @$wpl_varmap_amazon[$i] == 'Flavor' 				): ?>selected="selected"<?php endif; ?>><?php echo 'Flavor' ?></option>
											<option value="Scent"				<?php if ( @$wpl_varmap_amazon[$i] == 'Scent' 				): ?>selected="selected"<?php endif; ?>><?php echo 'Scent' ?></option>
											<option value="DisplayWidth" 		<?php if ( @$wpl_varmap_amazon[$i] == 'DisplayWidth' 		): ?>selected="selected"<?php endif; ?>><?php echo 'DisplayWidth' ?></option>
											<option value="DisplayHeight" 		<?php if ( @$wpl_varmap_amazon[$i] == 'DisplayHeight' 		): ?>selected="selected"<?php endif; ?>><?php echo 'DisplayHeight' ?></option>
											<option value="DisplayLength" 		<?php if ( @$wpl_varmap_amazon[$i] == 'DisplayLength' 		): ?>selected="selected"<?php endif; ?>><?php echo 'DisplayLength' ?></option>
											<option value="DisplayWeight" 		<?php if ( @$wpl_varmap_amazon[$i] == 'DisplayWeight' 		): ?>selected="selected"<?php endif; ?>><?php echo 'DisplayWeight' ?></option>
											<option value="ItemPackageQuantity" <?php if ( @$wpl_varmap_amazon[$i] == 'ItemPackageQuantity' ): ?>selected="selected"<?php endif; ?>><?php echo 'ItemPackageQuantity' ?></option>
										</select>
									</td>
								</tr>
								<?php endfor; ?>
								<tr>
									<td>
										<select name="varmap_woocom[]" class="select">
											<option value="">-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>" ><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td>
										<select name="varmap_amazon[]" class="select">
											<option value="" >-- <?php echo __( 'Select Amazon attribute', 'wp-lister-for-amazon' ); ?> --</option>
											<option value="Size"><?php echo 'Size' ?></option>
											<option value="Color"><?php echo 'Color' ?></option>
											<option value="Material"><?php echo 'Material' ?></option>
											<option value="Flavor"><?php echo 'Flavor' ?></option>
											<option value="Scent"><?php echo 'Scent' ?></option>
											<option value="DisplayWidth"><?php echo 'DisplayWidth' ?></option>
											<option value="DisplayHeight"><?php echo 'DisplayHeight' ?></option>
											<option value="DisplayLength"><?php echo 'DisplayLength' ?></option>
											<option value="DisplayWeight"><?php echo 'DisplayWeight' ?></option>
											<option value="ItemPackageQuantity"><?php echo 'ItemPackageQuantity' ?></option>
										</select>
									</td>
								</tr>
							</table>

							<p class="x-desc" style="display: block;">
								<?php echo __( 'If you are using non-standard attributes for variations, you can map them to standard attributes supported by Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->



					<div class="postbox adv_options" id="MergeVariationAttributesBox">
						<h3 class="hndle"><span><?php echo __( 'Merge Variation Attributes', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<table id="variation-merger-table">
								<tr>
									<th><?php echo __( '1st WooCommerce attribute', 'wp-lister-for-amazon' ); ?></th>
									<th>&nbsp;</th>
									<th><?php echo __( '2nd WooCommerce attribute', 'wp-lister-for-amazon' ); ?></th>
									<th>&nbsp;</th>
									<th><?php echo __( 'Amazon attribute', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php
									// $wpl_variation_merger_map = array();

									// rebuild separate arrays
									$wpl_varmerge_woo1 = array();
									$wpl_varmerge_woo2 = array();
									$wpl_varmerge_amaz = array();
									$wpl_varmerge_glue = array();
									foreach ($wpl_variation_merger_map as $key => $row) {
										$wpl_varmerge_woo1[] = $row['woo1'];
										$wpl_varmerge_woo2[] = $row['woo2'];
										$wpl_varmerge_amaz[] = $row['amaz'];
										$wpl_varmerge_glue[] = $row['glue'];
									}

								?>

								<?php for ($i=0; $i < sizeof($wpl_variation_merger_map); $i++) : ?>
								<tr>
									<td>
										<select name="varmerge_woo1[]" class="select">
											<option value=""      <?php if ( @$wpl_varmerge_woo1[$i] == ''      ): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>"   <?php if ( @$wpl_varmerge_woo1[$i] == $attribute->label ):   ?>selected="selected"<?php endif; ?>><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td style="width:3em;">
										<input type="text" name="varmerge_glue[]" value="<?php echo @$wpl_varmerge_glue[$i]; ?>" class="text_input" />
									</td>
									<td>
										<select name="varmerge_woo2[]" class="select">
											<option value=""      <?php if ( @$wpl_varmerge_woo2[$i] == ''      ): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>"   <?php if ( @$wpl_varmerge_woo2[$i] == $attribute->label ):   ?>selected="selected"<?php endif; ?>><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td>&nbsp;</td>
									<td>
										<select name="varmerge_amaz[]" class="select">
											<option value=""      <?php if ( @$wpl_varmerge_amaz[$i] == ''      ): ?>selected="selected"<?php endif; ?>>-- <?php echo __( 'Select Amazon attribute', 'wp-lister-for-amazon' ); ?> --</option>
											<option value="Size"  <?php if ( @$wpl_varmerge_amaz[$i] == 'Size'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Size' ?></option>
											<option value="Color" <?php if ( @$wpl_varmerge_amaz[$i] == 'Color' ): ?>selected="selected"<?php endif; ?>><?php echo 'Color' ?></option>
											<option value="Material" <?php if ( @$wpl_varmerge_amaz[$i] == 'Material' ): ?>selected="selected"<?php endif; ?>><?php echo 'Material' ?></option>
											<option value="Length" <?php if ( @$wpl_varmerge_amaz[$i] == 'Length' ): ?>selected="selected"<?php endif; ?>><?php echo 'Length' ?></option>
										</select>
									</td>
								</tr>
								<?php endfor; ?>
								<tr>
									<td>
										<select name="varmerge_woo1[]" class="select">
											<option value="" >-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>"><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td style="width:3em;">
										<input type="text" name="varmerge_glue[]" value="" class="text_input" />
									</td>
									<td>
										<select name="varmerge_woo2[]" class="select">
											<option value="">-- <?php echo __( 'Select WooCommerce attribute', 'wp-lister-for-amazon' ); ?> --</option>
							                <?php foreach ($wpl_available_attributes as $attribute) : ?>
												<option value="<?php echo esc_attr( $attribute->label ) ?>"><?php echo $attribute->label ?></option>
							                <?php endforeach; ?>
										</select>
									</td>
									<td>&nbsp;</td>
									<td>
										<select name="varmerge_amaz[]" class="select">
											<option value="">-- <?php echo __( 'Select Amazon attribute', 'wp-lister-for-amazon' ); ?> --</option>
											<option value="Size"><?php echo 'Size' ?></option>
											<option value="Color"><?php echo 'Color' ?></option>
											<option value="Material"><?php echo 'Material' ?></option>
										</select>
									</td>
								</tr>
							</table>

							<p class="x-desc" style="display: block;">
								<?php echo __( 'Example: You sell blankets with Length and Width which need to be merged to Size on Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->




					<div class="postbox adv_options" id="CustomShortcodesBox">
						<h3 class="hndle"><span><?php echo __( 'Custom Shortcodes', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<table id="custom-shortcodes-table">
								<tr>
									<th><?php echo __( 'Shortcode Title', 'wp-lister-for-amazon' ); ?></th>
									<th><?php echo __( 'Shortcode Content', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php
									// add empty record
									$wpl_custom_shortcodes[] = array(
										'title'   => '',
										'slug'    => '',
										'content' => '',
									);

								?>

								<?php foreach ( $wpl_custom_shortcodes as $key => $shortcode ) : ?>
								<tr>
									<td>
										<input type="text" name="shortcode_title[]" value="<?php echo $shortcode['title']; ?>" class="text_input" placeholder="My shortcode" />
										<br>
										<input type="text" name="shortcode_slug[]" value="<?php echo $shortcode['slug']; ?>" class="text_input" placeholder="my-shortcode"/>
									</td>
									<td>
										<textarea name="shortcode_content[]" placeholder="Enter your text or copy and paste some HTML"><?php echo $shortcode['content']; ?></textarea>
									</td>
								</tr>
								<?php endforeach; ?>

							</table>

							<p class="x-desc" style="display: block;">
								<?php echo __( 'Create custom profile shortcodes from text or HTML snippets.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->


					<div class="postbox adv_options" id="CustomVariationMetaBox">
						<h3 class="hndle"><span><?php echo __( 'Custom Variation Meta', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<table id="custom-variation-fields-table">
								<tr>
									<th><?php echo __( 'Field Label', 'wp-lister-for-amazon' ); ?></th>
									<th><?php echo __( 'Meta Key', 'wp-lister-for-amazon' ); ?></th>
								</tr>

								<?php
									// add empty record
									$wpl_variation_meta_fields[] = array(
										'label'  => '',
										'key'    => '',
									);

								?>

								<?php foreach ( $wpl_variation_meta_fields as $key => $varmeta ) : ?>
								<tr>
									<td>
										<input type="text" name="varmeta_label[]" value="<?php echo $varmeta['label']; ?>" class="text_input" placeholder="My custom field" />
									</td>
									<td>
										<input type="text" name="varmeta_key[]" value="<?php echo $varmeta['key']; ?>" class="text_input" placeholder="my-custom-field"/>
									</td>
								</tr>
								<?php endforeach; ?>

							</table>

							<p class="x-desc" style="display: block;">
								<?php echo __( 'Add custom meta fields which will be editable for each variation separately.', 'wp-lister-for-amazon' ); ?>
							</p>
							<p class="x-desc" style="display: block;">
								<?php echo __( 'These meta fields will be available as product properties in the profile editor.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->




				<?php // if ( ( is_multisite() ) && ( is_main_site() ) ) : ?>
				<?php if ( false ) : ?>
				<p>
					<b>Warning:</b> Deactivating WP-Lister on a multisite network will remove all settings and data from all sites.
				</p>
				<?php endif; ?>


				</div> <!-- .meta-box-sortables -->
			</div> <!-- #postbox-container-1 -->



		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->

	</form>

    <script type="text/javascript">
        var size_map_values = <?php echo json_encode( $amazon_size_values ); ?>;
        var color_map_values = <?php echo json_encode( $amazon_color_values ); ?>;
        jQuery( document ).ready( function($) {
            $('#wpl-run_background_inventory_check').change(function () {
                if ($('#wpl-run_background_inventory_check').val() != 1) {
                    $('.show-if-inventory-check').hide();
                } else {
                    $('.show-if-inventory-check').show();
                }
            }).change();

            $("#ship_from_add_address").on( "click", function(e) {
                e.preventDefault();
                var row = $( $("#ship_from_address_tpl").html() );
                row.removeAttr('id');
                row.find("input").each(function(idx, element) {
                    //console.log(element);
                    $(element).val("");
                });
                $("#ship_from_address_tbody").append( row );

            });

            $('#ship_from_address_tbody').on("click", ".ship_from_delete_address", function(e) {
                e.preventDefault();
                $(this).parents("tr").remove();
                generate_ship_from_dropdown();
            });

            $('#wpl-enable_import_proxy').change(function() {
                $('.proxy-url-field').hide();
                if ( $(this).val() == 1 ) {
                    $('.proxy-url-field').show();
                }
            }).change();

            $('.size-map-input').autocomplete({source: size_map_values});
            $('.color-map-input').autocomplete({source: color_map_values});

            // default ship-from address dropdown
            $('#ship_from_address_tbody').on( 'change', 'input.address-name', function(obj) {
                generate_ship_from_dropdown();
            });

            function generate_ship_from_dropdown() {
                let names = [];
                $('#ship_from_address_tbody input.address-name').each(function() {
                    if ( $(this).val().length > 0 ) {
                        names.push( $(this).val() );
                    }
                });

                const select = $('#wpl-ship_from_default_address');
                const current_value = select.val();

                select.find('option').remove();

                select.append( '<option value="">-- no default address --</option>' );
                for ( let i in names ) {
                    select.append( '<option value="'+ names[i] +'">'+ names[i] +'</option>' );
                }

                // restore current selection
                select.val( current_value );
            }

        });
    </script>


</div>
