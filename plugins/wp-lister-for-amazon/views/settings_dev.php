<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">
	
	#side-sortables .postbox input.text_input,
	#side-sortables .postbox select.select {
	    width: 50%;
	}
	#side-sortables .postbox label.text_label {
	    width: 45%;
	}
	#side-sortables .postbox p.desc {
	    margin-left: 5px;
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
										<p><?php echo __( 'This page contains some special options intended for developers and debugging.', 'wp-lister-for-amazon' ) ?></p>
										<p><?php echo sprintf( __( 'The daily maintenance ran %s ago.', 'wp-lister-for-amazon' ), human_time_diff( get_option('wpla_daily_cron_last_run') ) ) ?></p>
									</div>
								</div>

								<div id="major-publishing-actions">
									<div id="publishing-action">
										<input type="hidden" name="action" value="save_wpla_devsettings" >
                                        <?php wp_nonce_field( 'wpla_save_devsettings' ); ?>
										<input type="submit" value="<?php echo __( 'Save Settings', 'wp-lister-for-amazon' ); ?>" id="save_settings" class="button-primary" name="save">
									</div>
									<div class="clear"></div>
								</div>

							</div>

						</div>
					</div>

					<div class="postbox" id="VersionInfoBox">
						<h3 class="hndle"><span><?php echo __( 'Version Info', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<table style="width:100%">
								<tr><td>WP-Lister</td><td>	<?php echo WPLA_VERSION ?> </td></tr>
								<tr><td>Database</td><td> <?php echo get_option('wpla_db_version') ?> </td></tr>
								<tr><td>WordPress</td><td> <?php global $wp_version; echo $wp_version ?> </td></tr>
								<tr><td>WooCommerce</td><td> <?php echo defined('WC_VERSION') ? WC_VERSION : WOOCOMMERCE_VERSION ?> </td></tr>
							</table>

						</div>
					</div>

				</div>
			</div> <!-- #postbox-container-1 -->


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">

					<div class="postbox" id="DbLoggingBox">
						<h3 class="hndle"><span><?php echo __( 'Logging and Maintenance', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-option-log_to_db" class="text_label"><?php echo __( 'Log to database', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-option-log_to_db" name="wpla_option_log_to_db" title="Logging" class=" required-entry select">
								<option value="1" <?php if ( $wpl_option_log_to_db == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
								<option value="0" <?php if ( $wpl_option_log_to_db != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable to log all communication with Amazon to the database.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-option-log_record_limit" class="text_label">
								<?php echo __( 'Log entry size limit', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Limit the maximum size of a single log record. The default value is 4 kb.') ?>
							</label>
							<select id="wpl-option-log_record_limit" name="wpla_log_record_limit" class=" required-entry select">
								<option value="4096"  <?php if ( $wpl_log_record_limit == '4096' ):  ?>selected="selected"<?php endif; ?>>4 kb (default)</option>
								<option value="8192"  <?php if ( $wpl_log_record_limit == '8192' ):  ?>selected="selected"<?php endif; ?>>8 kb</option>
								<option value="64000" <?php if ( $wpl_log_record_limit == '64000' ): ?>selected="selected"<?php endif; ?>>64 kb</option>
							</select>

							<label for="wpl-option-log_days_limit" class="text_label">
								<?php echo __( 'Keep log records for', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Select how long log records should be kept. Older records are removed automatically. The default is 30 days.') ?>
							</label>
							<select id="wpl-option-log_days_limit" name="wpla_log_days_limit" class=" required-entry select">
								<option value="1"   <?php if ( $wpl_log_days_limit == '1' ):   ?>selected="selected"<?php endif; ?>> 1 day </option>
								<option value="2"   <?php if ( $wpl_log_days_limit == '2' ):   ?>selected="selected"<?php endif; ?>> 2 days</option>
								<option value="3"   <?php if ( $wpl_log_days_limit == '3' ):   ?>selected="selected"<?php endif; ?>> 3 days</option>
								<option value="7"   <?php if ( $wpl_log_days_limit == '7' ):   ?>selected="selected"<?php endif; ?>> 7 days</option>
								<option value="14"  <?php if ( $wpl_log_days_limit == '14' ):  ?>selected="selected"<?php endif; ?>>14 days</option>
								<option value="30"  <?php if ( $wpl_log_days_limit == '30' ):  ?>selected="selected"<?php endif; ?>>30 days (default)</option>
								<option value="60"  <?php if ( $wpl_log_days_limit == '60' ):  ?>selected="selected"<?php endif; ?>>60 days</option>
								<option value="90"  <?php if ( $wpl_log_days_limit == '90' ):  ?>selected="selected"<?php endif; ?>>90 days</option>
							</select>

							<label for="wpl-option-stock_days_limit" class="text_label">
								<?php echo __( 'Keep stock log for', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Select how long stock log records should be kept. Older records are removed automatically. The default is 180 days.') ?>
							</label>
							<select id="wpl-option-stock_days_limit" name="wpla_stock_days_limit" class=" required-entry select">
                                <option value="1"   <?php if ( $wpl_stock_days_limit == '1' ):   ?>selected="selected"<?php endif; ?>> 1 day</option>
                                <option value="3"   <?php if ( $wpl_stock_days_limit == '3' ):   ?>selected="selected"<?php endif; ?>> 3 days</option>
                                <option value="7"   <?php if ( $wpl_stock_days_limit == '7' ):   ?>selected="selected"<?php endif; ?>> 7 days</option>
								<option value="14"  <?php if ( $wpl_stock_days_limit == '14' ):  ?>selected="selected"<?php endif; ?>>14 days</option>
								<option value="30"  <?php if ( $wpl_stock_days_limit == '30' ):  ?>selected="selected"<?php endif; ?>>30 days</option>
								<option value="60"  <?php if ( $wpl_stock_days_limit == '60' ):  ?>selected="selected"<?php endif; ?>>60 days</option>
								<option value="90"  <?php if ( $wpl_stock_days_limit == '90' ):  ?>selected="selected"<?php endif; ?>>90 days</option>
								<option value="180" <?php if ( $wpl_stock_days_limit == '180' ): ?>selected="selected"<?php endif; ?>>180 days (default)</option>
							</select>

							<label for="wpl-option-reports_days_limit" class="text_label">
								<?php echo __( 'Keep reports for', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Select how long Amazon reports should be kept. Older reports are removed automatically. The default is 90 days.') ?>
							</label>
							<select id="wpl-option-reports_days_limit" name="wpla_reports_days_limit" class=" required-entry select">
								<option value="7"   <?php if ( $wpl_reports_days_limit == '7' ):   ?>selected="selected"<?php endif; ?>> 7 days</option>
								<option value="14"  <?php if ( $wpl_reports_days_limit == '14' ):  ?>selected="selected"<?php endif; ?>>14 days</option>
								<option value="30"  <?php if ( $wpl_reports_days_limit == '30' ):  ?>selected="selected"<?php endif; ?>>30 days</option>
								<option value="60"  <?php if ( $wpl_reports_days_limit == '60' ):  ?>selected="selected"<?php endif; ?>>60 days</option>
								<option value="90"  <?php if ( $wpl_reports_days_limit == '90' ):  ?>selected="selected"<?php endif; ?>>90 days (default)</option>
							</select>

							<label for="wpl-option-feeds_days_limit" class="text_label">
								<?php echo __( 'Keep feeds for', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Select how long Amazon feeds should be kept. Older feeds are removed automatically. The default is 90 days.') ?>
							</label>
							<select id="wpl-option-feeds_days_limit" name="wpla_feeds_days_limit" class=" required-entry select">
								<option value="7"   <?php if ( $wpl_feeds_days_limit == '7' ):   ?>selected="selected"<?php endif; ?>> 7 days</option>
								<option value="14"  <?php if ( $wpl_feeds_days_limit == '14' ):  ?>selected="selected"<?php endif; ?>>14 days</option>
								<option value="30"  <?php if ( $wpl_feeds_days_limit == '30' ):  ?>selected="selected"<?php endif; ?>>30 days</option>
								<option value="60"  <?php if ( $wpl_feeds_days_limit == '60' ):  ?>selected="selected"<?php endif; ?>>60 days</option>
								<option value="90"  <?php if ( $wpl_feeds_days_limit == '90' ):  ?>selected="selected"<?php endif; ?>>90 days (default)</option>
							</select>

							<label for="wpl-option-orders_days_limit" class="text_label">
								<?php echo __( 'Keep sales data for', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Select how long Amazon orders should be kept. Older orders are removed from WP-Lister automatically but will remain in WooCommerce. The default is forever.') ?>
							</label>
							<select id="wpl-option-orders_days_limit" name="wpla_orders_days_limit" class=" required-entry select">
								<option value=""    <?php selected( $wpl_orders_days_limit, '' ); ?>>forever (default)</option>
								<option value="90"  <?php selected( $wpl_orders_days_limit, '90'); ?>>90 days</option>
								<option value="180" <?php selected( $wpl_orders_days_limit, '180' ); ?>>180 days</option>
								<option value="365" <?php selected( $wpl_orders_days_limit, '365' ); ?>>1 year</option>
								<option value="730" <?php selected( $wpl_orders_days_limit, '730' ); ?>>2 years</option>
								<option value="1095" <?php selected( $wpl_orders_days_limit, '1095' ); ?>>3 years</option>
							</select>

						</div>
					</div>

					<!--
					<div class="postbox" id="ErrorHandlingBox">
						<h3 class="hndle"><span><?php echo __( 'Error handling', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-option-ajax_error_handling" class="text_label"><?php echo __( 'Handle 404 errors for admin-ajax.php', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-option-ajax_error_handling" name="wpla_ajax_error_handling" class=" required-entry select">
								<option value="halt" <?php if ( $wpl_ajax_error_handling == 'halt' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Halt on error', 'wp-lister-for-amazon' ); ?></option>
								<option value="skip" <?php if ( $wpl_ajax_error_handling == 'skip' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Continue with next item', 'wp-lister-for-amazon' ); ?></option>
								<option value="retry" <?php if ( $wpl_ajax_error_handling == 'retry' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Try again', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( '404 errors for admin-ajax.php should actually never happen and are generally a sign of incorrect server configuration.', 'wp-lister-for-amazon' ); ?>
								<?php echo __( 'This setting is just a workaround. You should consider moving to a proper hosting provider instead.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div>
					-->

					<div class="postbox" id="FeedsOptionsBox" style="display:block;">
						<h3 class="hndle"><span><?php echo __( 'Feeds', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-option-max_feed_size" class="text_label"><?php echo __( 'Maximum feed size', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-option-max_feed_size" name="wpla_max_feed_size" class=" required-entry select">
								<option value="10"    <?php if ( $wpl_max_feed_size == '10' ):    ?>selected="selected"<?php endif; ?>>10</option>
								<option value="20"    <?php if ( $wpl_max_feed_size == '20' ):    ?>selected="selected"<?php endif; ?>>20</option>
								<option value="50"    <?php if ( $wpl_max_feed_size == '50' ):    ?>selected="selected"<?php endif; ?>>50</option>
								<option value="100"   <?php if ( $wpl_max_feed_size == '100' ):   ?>selected="selected"<?php endif; ?>>100</option>
								<option value="200"   <?php if ( $wpl_max_feed_size == '200' ):   ?>selected="selected"<?php endif; ?>>200</option>
								<option value="500"   <?php if ( $wpl_max_feed_size == '500' ):   ?>selected="selected"<?php endif; ?>>500</option>
								<option value="1000"  <?php if ( $wpl_max_feed_size == '1000' ):  ?>selected="selected"<?php endif; ?>>1000 (Default)</option>
								<option value="2000"  <?php if ( $wpl_max_feed_size == '2000' ):  ?>selected="selected"<?php endif; ?>>2000</option>
								<option value="3000"  <?php if ( $wpl_max_feed_size == '3000' ):  ?>selected="selected"<?php endif; ?>>3000</option>
								<option value="5000"  <?php if ( $wpl_max_feed_size == '5000' ):  ?>selected="selected"<?php endif; ?>>5000</option>
								<option value="10000" <?php if ( $wpl_max_feed_size == '10000' ): ?>selected="selected"<?php endif; ?>>10000</option>
							</select>
							<p class="desc" style="display: block;">
								If you get a timeout error when opening the feeds page, please try to lower this value.
							</p>

							<label for="wpl-option-feed_encoding" class="text_label"><?php echo __( 'Feed encoding', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-option-feed_encoding" name="wpla_feed_encoding" class=" required-entry select">
								<option value="ISO-8859-1"  <?php if ( $wpl_feed_encoding == 'ISO-8859-1' ):   	?>selected="selected"<?php endif; ?>>ISO-8859-1 (Default)</option>
								<option value="UTF-8"      	<?php if ( $wpl_feed_encoding == 'UTF-8' ):    		?>selected="selected"<?php endif; ?>>UTF-8</option>
							</select>
							<p class="desc" style="display: block;">
								It is recommended to use the character set ISO-8859-1 to avoid issues with special characters.
							</p>

							<label for="wpl-option-feed_currency_format" class="text_label"><?php echo __( 'Feed currency format', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-option-feed_currency_format" name="wpla_feed_currency_format" class=" required-entry select">
								<option value="auto"         <?php if ( $wpl_feed_currency_format == 'auto' ): 			?>selected="selected"<?php endif; ?>>Use decimal comma in Price &amp; Quantity feed on EU marketplaces (DE, FR, IT, ES) (<?php _e( 'default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="legacy"       <?php if ( $wpl_feed_currency_format == 'legacy' ): 		?>selected="selected"<?php endif; ?>>Legacy mode (always use decimal point)</option>
							</select>
							<p class="desc" style="display: block;">
								Set this option to use decimal comma on EU Price &amp; Quantity feeds.
							</p>

                            <label for="wpl-option-feed_shipment_time" class="text_label"><?php echo __( 'Send Shipment Time', 'wp-lister-for-amazon' ); ?></label>
                            <select id="wpl-option-feed_shipment_time" name="wpla_feed_shipment_time" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_feed_include_shipment_time, 0 ); ?>>No (Default)</option>
                                <option value="1" <?php selected( $wpl_feed_include_shipment_time, 1 ); ?>>Yes</option>
                            </select>
                            <p class="desc" style="display: block;">
                                Enable to include the shipment time in ship-date column in Order Fulfillment Feed.
                            </p>

                            <label for="wpl-option-lilo_version" class="text_label"><?php echo __( 'ListingLoader version', 'wp-lister-for-amazon' ); ?></label>
                            <select id="wpl-option-lilo_version" name="wpla_lilo_version" class=" required-entry select">
                                <option value="0"         <?php selected( $wpl_lilo_version, '0'         ); ?>>Legacy Version 1.4</option>
                                <option value="2014.0703" <?php selected( $wpl_lilo_version, '2014.0703' ); ?>>Latest Version 2014.0703 (Default)</option>
                            </select>
                            <p class="desc" style="display: block;">
                                Select the internal ListingLoader template version which is used when no profile is assigned.
                            </p>

                            <label for="wpl-option-feed_failure_emails" class="text_label"><?php echo __( 'Feed submission failure emails', 'wp-lister-for-amazon' ); ?></label>
                            <select id="wpl-option-feed_failure_emails" name="wpla_feed_failure_emails" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_feed_failure_emails, '0' ); ?>>No (default)</option>
                                <option value="1" <?php selected( $wpl_feed_failure_emails, '1' ); ?>>Yes</option>
                            </select>
                            <p class="desc" style="display: block;">
                                Send an email to <?php bloginfo('admin_email'); ?> when WP-Lister encounters feed submission errors
                            </p>

                            <label for="wpl-option-use_feed_items_table" class="text_label"><?php echo __( 'Dedicated Order Fulfillment table (Beta)', 'wp-lister-for-amazon' ); ?></label>
                            <select id="wpl-option-use_feed_items_table" name="wpla_feed_items_table" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_feed_items_table, '0' ); ?>>No (default)</option>
                                <option value="1" <?php selected( $wpl_feed_items_table, '1' ); ?>>Yes</option>
                            </select>
                            <p class="desc" style="display: block;">
                                Enable this if you are experiencing missing orders when completing orders in bulk.
                            </p>

						</div>
					</div>

					<div class="postbox" id="StagingSiteSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Staging site', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<p>
								<?php echo __( 'If you frequently clone your WordPress installation to a staging site, you can make WP-Lister automatically disable background updates and order creation when running on the staging site.', 'wp-lister-for-amazon' ); ?>
								<?php echo __( 'Enter a unique part of your staging domain below to activate this feature.', 'wp-lister-for-amazon' ); ?><br>
							</p>
							<label for="wpl-staging_site_pattern" class="text_label">
								<?php echo __( 'Staging site pattern', 'wp-lister-for-amazon' ) ?>
								<?php $tip_msg  = __( 'You do not need to enter the full domain name of your staging site.', 'wp-lister-for-amazon' ); ?>
								<?php $tip_msg .= __( 'If your staging domain is mydomain.staging.wpengine.com enter staging.wpengine.com as a general pattern.', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip($tip_msg) ?>
							</label>
							<input type="text" name="wpla_staging_site_pattern" id="wpl-staging_site_pattern" value="<?php echo $wpl_staging_site_pattern; ?>" class="text_input" />
							<p class="desc" style="display: block;">
								Example: staging.wpengine.com
							</p>

						</div>
					</div>

					<div class="postbox" id="DebugOptionsBox">
						<h3 class="hndle"><span><?php echo __( 'Debug options', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">


							<label for="wpl-inventory_check_batch_size" class="text_label">
								<?php echo __( 'Check inventory in batches of', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If your server times out or runs out of memory when using the inventory check tool you may have to lower this setting.') ?>
							</label>
							<select id="wpl-inventory_check_batch_size" name="wpla_inventory_check_batch_size" class=" required-entry select">
								<option value="20"   <?php if ( $wpl_inventory_check_batch_size == '20'   ): ?>selected="selected"<?php endif; ?>>20 items</option>
								<option value="50"   <?php if ( $wpl_inventory_check_batch_size == '50'   ): ?>selected="selected"<?php endif; ?>>50 items</option>
								<option value="100"  <?php if ( $wpl_inventory_check_batch_size == '100'  ): ?>selected="selected"<?php endif; ?>>100 items</option>
								<option value="200"  <?php if ( $wpl_inventory_check_batch_size == '200'  ): ?>selected="selected"<?php endif; ?>>200 items (default)</option>
								<option value="500"  <?php if ( $wpl_inventory_check_batch_size == '500'  ): ?>selected="selected"<?php endif; ?>>500 items</option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Set the batch size for the inventory check tools.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-apply_profile_batch_size" class="text_label">
                                <?php echo __( 'Apply profile in batches of', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If your server times out or runs out of memory when applying a profile (or template) to a huge number of items, you may have to lower this setting.') ?>
                            </label>
                            <select id="wpl-apply_profile_batch_size" name="wpla_apply_profile_batch_size" class=" required-entry select">
                                <option value="20"   <?php if ( $wpl_apply_profile_batch_size == '20'   ): ?>selected="selected"<?php endif; ?>>20 items</option>
                                <option value="50"   <?php if ( $wpl_apply_profile_batch_size == '50'   ): ?>selected="selected"<?php endif; ?>>50 items</option>
                                <option value="100"  <?php if ( $wpl_apply_profile_batch_size == '100'  ): ?>selected="selected"<?php endif; ?>>100 items</option>
                                <option value="200"  <?php if ( $wpl_apply_profile_batch_size == '200'  ): ?>selected="selected"<?php endif; ?>>200 items</option>
                                <option value="500"  <?php if ( $wpl_apply_profile_batch_size == '500'  ): ?>selected="selected"<?php endif; ?>>500 items</option>
                                <option value="1000"  <?php if ( $wpl_apply_profile_batch_size == '1000'  ): ?>selected="selected"<?php endif; ?>>1000 items (default)</option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Set the batch size for the inventory check tools.', 'wp-lister-for-amazon' ); ?>
                            </p>

                            <label for="wpl-fba_override_query" class="text_label">
                                <?php echo __( 'Check FBA Overrides', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Counting product-level FBA overrides uses a database query that is known to cause timeouts to stores with lots of products. Please only enable this setting if you do use the FBA override option for individual products, and you need to see the exact number of FBA and Non-FBA items in the summary on top of the Listings page<br><br>Warning: Enabling this could slow down the Listings page or even cause timeout issues for stores with a larger number of products.') ?>
                            </label>
                            <select id="wpl-fba_override_query" name="wpla_fba_override_query" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_fba_override_query, 0 ); ?>><?php _e('No', 'wp-lister-for-amazon'); ?> (Default)</option>
                                <option value="1" <?php selected( $wpl_fba_override_query, 1 ); ?>><?php _e('Yes', 'wp-lister-for-amazon'); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Enable this only if you use the FBA override option and read the tooltip if you do.', 'wp-lister-for-amazon' ); ?>
                            </p>

                            <label for="wpl-option-php_error_handling" class="text_label">
                                <?php echo __( 'PHP error handling', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('This option can be helpful in order to investigate certain issues, including cases where you see nothing but an empty page.') ?>
                            </label>
                            <select id="wpl-option-php_error_handling" name="wpla_php_error_handling" class=" required-entry select">
                                <option value="0" <?php if ( $wpl_php_error_handling == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Production Mode', 'wp-lister-for-amazon' ); ?> (default)</option>
                                <option value="9" <?php if ( $wpl_php_error_handling == '9' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Production Mode', 'wp-lister-for-amazon' ); ?> (forced)</option>
                                <option value="1" <?php if ( $wpl_php_error_handling == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Show all errors inline', 'wp-lister-for-amazon' ); ?></option>
                                <option value="2" <?php if ( $wpl_php_error_handling == '2' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Show fatal errors on shutdown', 'wp-lister-for-amazon' ); ?></option>
                                <option value="3" <?php if ( $wpl_php_error_handling == '3' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Show errors inline and on shutdown', 'wp-lister-for-amazon' ); ?></option>
                                <option value="6" <?php if ( $wpl_php_error_handling == '6' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Show fatal and non-fatal errors on shutdown', 'wp-lister-for-amazon' ); ?></option>
                            </select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Please leave this at the default unless told otherwise by support.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-show_browse_node_ids" class="text_label">
                                <?php echo __( 'Show browse node ID', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Display the browse node IDs when selecting a category.') ?>
                            </label>
							<select id="wpl-show_browse_node_ids" name="wpla_show_browse_node_ids" title="Logging" class=" required-entry select">
								<option value="0" <?php if ( $wpl_show_browse_node_ids != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (Default)</option>
								<option value="1" <?php if ( $wpl_show_browse_node_ids == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Display the browse node IDs when selecting a category.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div>

					<div class="postbox dev_box" id="DeveloperToolBox" style="">
						<h3 class="hndle"><span><?php echo __( 'Developer options', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-option-enable_item_edit_link" class="text_label">
								<?php echo __( 'Allow direct editing', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Shows an additional "Edit" link on the listing page, which allows you to edit the listing database fields directly.<br><br>It is not recommended to use this option at all - all your changes will be overwritten when the linked product is updated in WooCommerce!') ?>
							</label>
							<select id="wpl-option-enable_item_edit_link" name="wpla_enable_item_edit_link" class=" required-entry select">
								<option value="0" <?php if ( $wpl_enable_item_edit_link == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (default)</option>
								<option value="1" <?php if ( $wpl_enable_item_edit_link == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable editing listing records directly. Not recommended.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-option-enable_stock_log_backtrace" class="text_label">
                                <?php echo __( 'Store stock log backtrace', 'wp-lister-for-amazon' ); ?>
                            </label>
                            <select id="wpl-option-stock_log_backtrace" name="wpla_stock_log_backtrace" class=" required-entry select">
                                <option value="0" <?php if ( $wpl_stock_log_backtrace == '0' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
                                <option value="1" <?php if ( $wpl_stock_log_backtrace == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?> (default)</option>
                            </select>

							<label for="wpl-text-log_level" class="text_label"><?php echo __( 'Log to logfile', 'wp-lister-for-amazon' ); ?></label>
							<select id="wpl-text-log_level" name="wpla_text_log_level" title="Logging" class=" required-entry select">
								<option value=""> -- <?php echo __( 'no logfile', 'wp-lister-for-amazon' ); ?> -- </option>
								<option value="2" <?php if ( $wpl_text_log_level == '2' ): ?>selected="selected"<?php endif; ?>>Error</option>
								<option value="3" <?php if ( $wpl_text_log_level == '3' ): ?>selected="selected"<?php endif; ?>>Critical</option>
								<option value="4" <?php if ( $wpl_text_log_level == '4' ): ?>selected="selected"<?php endif; ?>>Warning</option>
								<option value="5" <?php if ( $wpl_text_log_level == '5' ): ?>selected="selected"<?php endif; ?>>Notice</option>
								<option value="6" <?php if ( $wpl_text_log_level == '6' ): ?>selected="selected"<?php endif; ?>>Info</option>
								<option value="7" <?php if ( $wpl_text_log_level == '7' ): ?>selected="selected"<?php endif; ?>>Debug</option>
								<option value="9" <?php if ( $wpl_text_log_level == '9' ): ?>selected="selected"<?php endif; ?>>All</option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Write debug information to logfile.', 'wp-lister-for-amazon' ); ?>
							</p>


						</div>
					</div>

                    <div class="postbox dev_box" id="DeveloperLogs">
                        <h3 class="hndle"><span><?php echo __( 'Logs', 'wp-lister-for-amazon' ) ?></span></h3>
                        <div class="inside">
                            <label for="wpl-text-log_file" class="text_label"><?php echo __( 'Log file', 'wp-lister-for-amazon' ); ?></label>
                            <select id="log_file" class=" required-entry select" style="width: auto;">
                                <option value="" selected>-- Select a log file --</option>
								<?php foreach ( $wpl_log_files as $file ): ?>
                                    <option name="<?php esc_attr_e( basename( $file ) ); ?>"><?php echo basename( $file ); ?></option>
								<?php endforeach; ?>
                            </select>
                            <button type="button" class="button" id="view_log"><?php _e( 'Download Log', 'wp-lister-for-amazon' ); ?></button>
<!--                            <button type="button" class="button" id="delete_log">--><?php //_e( 'Delete', 'wp-lister-for-amazon' ); ?><!--</button>-->
                        </div>
                    </div>

					<!--
					<div class="submit" style="padding-top: 0; float: right;">
						<input type="submit" value="<?php echo __( 'Save Settings', 'wp-lister-for-amazon' ) ?>" name="submit" class="button-primary">
					</div>
					-->


				</div> <!-- .meta-box-sortables -->
			</div> <!-- #postbox-container-1 -->



		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->

	</form>
    <script>
        jQuery( document ).on("ready", function() {
            jQuery('#view_log').on('click', function() {
                const log_file = jQuery('#log_file').val();
                document.location = ajaxurl + "?action=wpla_download_log_file&file="+ log_file +"&_wpnonce=<?php echo wp_create_nonce('wpla_download_log_file'); ?>";
            });
        });
    </script>


</div>