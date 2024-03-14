<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

	#AuthSettingsBox ol li {
		margin-bottom: 25px;
	}
	#AuthSettingsBox ol li > small {
		margin-left: 4px;
	}

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
						<h3 class="hndle"><span><?php echo __( 'Sync Status', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<div id="submitpost" class="submitbox">

								<div id="misc-publishing-actions">
									<div class="misc-pub-section">
									<?php /* if ( @$wpl_amazon_token_userid ): ?>
										<p>
											<!-- <b><?php echo __( 'Account Details', 'wp-lister-for-amazon' ) ?></b> -->
											<table style="width:95%">
												<tr><td><?php echo __( 'User ID', 'wp-lister-for-amazon' ) . ':</td><td>' . $wpl_amazon_token_userid ?></td></tr>
												<tr><td><?php echo __( 'Status', 'wp-lister-for-amazon' ) . ':</td><td>' . $wpl_amazon_user->Status ?></td></tr>
												<tr><td><?php echo __( 'Score', 'wp-lister-for-amazon' ) . ':</td><td>' . $wpl_amazon_user->FeedbackScore ?></td></tr>
												<tr><td><?php echo __( 'Site', 'wp-lister-for-amazon' ) . ':</td><td>' . $wpl_amazon_user->Site ?></td></tr>
												<?php if ( $wpl_amazon_user->StoreOwner ) : ?>
												<tr><td><?php echo __( 'Store', 'wp-lister-for-amazon' ) . ':</td><td>' ?><a href="<?php echo $wpl_amazon_user->StoreURL ?>" target="_blank"><?php echo __('visit store', 'wp-lister-for-amazon' ) ?></a></td></tr>
												<?php endif; ?>
											</table>
										</p>
									<?php endif; */ ?>

									<?php if ( empty( WPLA()->accounts ) ): ?>
										<p><?php echo __( 'No Amazon account has been set up yet.', 'wp-lister-for-amazon' ) ?></p>
									<?php elseif ( $wpl_option_cron_schedule && $wpl_option_sync_inventory ): ?>
										<p><?php echo __( 'Sync is enabled.', 'wp-lister-for-amazon' ) ?></p>
										<p><?php echo __( 'Sales will be synchronized between WooCommerce and Amazon.', 'wp-lister-for-amazon' ) ?></p>
									<?php elseif ( WPLA_LIGHT ): ?>
										<p><?php echo __( 'Sync is not available in WP-Lister Lite.', 'wp-lister-for-amazon' ) ?></p>
										<p><?php echo __( 'To synchronize sales across Amazon and WooCommerce you need to upgrade to WP-Lister Pro.', 'wp-lister-for-amazon' ) ?></p>
									<?php else: ?>
										<p><?php echo __( 'Sync is currently disabled.', 'wp-lister-for-amazon' ) ?></p>
										<p><?php echo __( 'Amazon and WooCommerce sales will not be synchronized!', 'wp-lister-for-amazon' ) ?></p>
									<?php endif; ?>

									</div>
								</div>

								<div id="major-publishing-actions">
									<div id="publishing-action">
										<input type="submit" value="<?php echo __( 'Update Settings', 'wp-lister-for-amazon' ); ?>" id="save_settings" class="button-primary" name="save">
									</div>
									<div class="clear"></div>
								</div>

							</div>

						</div>
					</div>

					<?php if ( $wpl_is_staging_site ) : ?>
					<div class="postbox" id="StagingSiteBox">
						<h3 class="hndle"><span><?php echo __( 'Staging Site', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">
							<p>
								<span style="color:darkred; font-weight:bold">
									Note: Automatic background updates and order creation have been disabled on this staging site.
								</span>
							</p>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $wpl_option_cron_schedule ) : ?>
					<div class="postbox" id="UpdateScheduleBox">
						<h3 class="hndle"><span><?php echo __( 'Update Schedule', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<p>
							<?php if ( wp_next_scheduled( 'wpla_update_schedule' ) ) : ?>
								<?php echo __( 'Next scheduled update', 'wp-lister-for-amazon' ); ?>:
								<?php echo human_time_diff( wp_next_scheduled( 'wpla_update_schedule' ), current_time('timestamp',1) ) ?>
								<?php echo wp_next_scheduled( 'wpla_update_schedule' ) < current_time('timestamp',1) ? 'ago' : '' ?>
							<?php elseif ( $wpl_option_cron_schedule == 'external' ) : ?>
								<?php echo __( 'Background updates are handled by an external cron job.', 'wp-lister-for-amazon' ); ?>
								<a href="#TB_inline?height=420&width=900&inlineId=cron_setup_instructions" class="thickbox">
									<?php echo __( 'Details', 'wp-lister-for-amazon' ); ?>
								</a>

								<div id="cron_setup_instructions" style="display: none;">
									<h2>
										<?php echo __( 'How to set up an external cron job', 'wp-lister-for-amazon' ); ?>
									</h2>
									<p>
										<?php echo __( 'Luckily, you don\'t have to be a server admin to set up an external cron job.', 'wp-lister-for-amazon' ); ?>
										<?php echo __( 'You can ask your server admin to set up a cron job on your own server - or use a 3rd party web based cron service, which provides a user friendly interface and additional features for a small annual fee.', 'wp-lister-for-amazon' ); ?>
									</p>

									<h3>
										<?php echo __( 'Option A: Web cron service', 'wp-lister-for-amazon' ); ?>
									</h3>
									<p>
										<?php $ec_link = '<a href="https://www.easycron.com/" target="_blank">www.easycron.com</a>' ?>
										<?php echo sprintf( __( 'The easiest way to set up a cron job is to sign up with %s and use the following URL to create a new task.', 'wp-lister-for-amazon' ), $ec_link ); ?><br>
									</p>
									<code>
										<?php echo bloginfo('url') ?>/wp-admin/admin-ajax.php?action=wplister_run_scheduled_tasks
									</code>

									<h3>
										<?php echo __( 'Option B: Server cron job', 'wp-lister-for-amazon' ); ?>
									</h3>
									<p>
										<?php echo __( 'If you prefer to set up a cron job on your own server you can create a cron job that will execute the following command:', 'wp-lister-for-amazon' ); ?>
									</p>

									<code style="font-size:0.8em;">
										wget -q -O - <?php echo bloginfo('url') ?>/wp-admin/admin-ajax.php?action=wplister_run_scheduled_tasks >/dev/null 2>&1
									</code>

									<p>
										<?php echo __( 'Note: Your cron job should run at least every 15 minutes but not more often than every 5 minutes.', 'wp-lister-for-amazon' ); ?>
									</p>
								</div>

							<?php else: ?>
								<span style="color:darkred; font-weight:bold">
									Warning: Update schedule is disabled.
								</span></p><p>
								Please click the "Save Settings" button above in order to reset the update schedule.
							<?php endif; ?>
							</p>

							<?php if ( get_option('wpla_cron_last_run') ) : ?>
							<p>
								<?php echo __( 'Last run', 'wp-lister-for-amazon' ); ?>:
								<?php echo human_time_diff( get_option('wpla_cron_last_run'), current_time('timestamp',1) ) ?> ago
							</p>
							<?php endif; ?>

                            <?php if ( get_option('wpla_orders_cron_last_run') ) : ?>
                                <p>
                                    <?php echo __( 'Orders last checked', 'wp-lister-for-amazon' ); ?>:
                                    <?php echo human_time_diff( get_option('wpla_orders_cron_last_run'), current_time('timestamp',1) ) ?> ago
                                </p>
                            <?php endif; ?>

						</div>
					</div>
					<?php endif; ?>

				</div>
			</div> <!-- #postbox-container-1 -->


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">

					<input type="hidden" name="action" value="save_wpla_settings" >
                    <?php wp_nonce_field( 'wpla_save_settings' ); ?>



					<div class="postbox" id="UpdateOptionBox">
						<h3 class="hndle"><span><?php echo __( 'Background Tasks', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">
							<!-- <p><?php echo __( 'Enable to update listings and transactions using WP-Cron.', 'wp-lister-for-amazon' ); ?></p> -->

							<label for="wpl-option-cron_schedule" class="text_label">
								<?php echo __( 'Update interval', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select how often WP-Lister should run background jobs like checking for new sales on Amazon, submitting pending feeds and checking for processing results, etc.<br><br>It is recommended to use an external cron job or set this interval to 5 - 15 minutes.<br><br>Setting the update interval <i>to manually</i> will disable all background tasks and should only be used for testing and debuging but never on a live production site.') ?>
							</label>
							<select id="wpl-option-cron_schedule" name="wpla_option_cron_schedule" class=" required-entry select">
								<option value="five_min" 	<?php if ( $wpl_option_cron_schedule == 'five_min'    ): ?>selected="selected"<?php endif; ?>><?php echo __( '5 min.', 'wp-lister-for-amazon' ) ?></option>
								<option value="ten_min" 	<?php if ( $wpl_option_cron_schedule == 'ten_min'     ): ?>selected="selected"<?php endif; ?>><?php echo __( '10 min.', 'wp-lister-for-amazon' ) ?></option>
								<option value="fifteen_min" <?php if ( $wpl_option_cron_schedule == 'fifteen_min' ): ?>selected="selected"<?php endif; ?>><?php echo __( '15 min.', 'wp-lister-for-amazon' ) ?> (<?php echo __('default', 'wp-lister-for-amazon' ) ?>)</option>
								<option value="thirty_min" 	<?php if ( $wpl_option_cron_schedule == 'thirty_min'  ): ?>selected="selected"<?php endif; ?>><?php echo __( '30 min.', 'wp-lister-for-amazon' ) ?></option>
								<option value="hourly" 		<?php if ( $wpl_option_cron_schedule == 'hourly'      ): ?>selected="selected"<?php endif; ?>><?php echo __( 'hourly', 'wp-lister-for-amazon' ) ?></option>
								<option value="daily" 		<?php if ( $wpl_option_cron_schedule == 'daily'       ): ?>selected="selected"<?php endif; ?>><?php echo __( 'daily', 'wp-lister-for-amazon' ) ?> (<?php echo __('not recommended', 'wp-lister-for-amazon' ) ?>)</option>
								<option value="" 			<?php if ( $wpl_option_cron_schedule == ''            ): ?>selected="selected"<?php endif; ?>><?php echo __( 'manually', 'wp-lister-for-amazon' ) ?> (<?php echo __('not recommended', 'wp-lister-for-amazon' ) ?>)</option>
								<option value="external" 	<?php if ( $wpl_option_cron_schedule == 'external'    ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Use external cron job', 'wp-lister-for-amazon' ) ?> (<?php echo __('recommended', 'wp-lister-for-amazon' ) ?>)</option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Select how often to run background jobs, like checking for new sales on Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

                            <label for="wpl-option-dedicated_orders_cron" class="text_label">
                                <?php echo __( 'Dedicated orders cron', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Create a dedicated cron job solely for checking and importing orders from Amazon.') ?>
                            </label>
                            <select id="wpl-option-dedicated_orders_cron" name="wpla_dedicated_orders_cron" class=" required-entry select">
                                <option value="0" <?php selected( $wpl_dedicated_orders_cron, 0 ); ?>><?php _e( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
                                <option value="1" <?php selected( $wpl_dedicated_orders_cron, 1 ); ?>><?php _e( 'Yes', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                            <p class="desc" style="display: block;">
                                <?php echo __( 'Create a dedicated cron job solely for checking and importing orders from Amazon.', 'wp-lister-for-amazon' ); ?>
                            </p>



						</div>
					</div>


					<div class="postbox" id="FBAOptionsBox">
						<h3 class="hndle"><span><?php echo __( 'Fulfillment by Amazon (FBA)', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

							<label for="wpl-fba_enabled" class="text_label">
								<?php echo __( 'Enable FBA', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Enable this if you are using FBA for any or all of your products. This will automatically generate a daily FBA inventory feed and process it to keep WP-Lister up to date with your stock levels on FBA.') ?>
							</label>
							<select id="wpl-fba_enabled" name="wpla_fba_enabled" class=" required-entry select">
								<option value="0" <?php if ( $wpl_fba_enabled != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_fba_enabled == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you are using Fulfillment by Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-fba_fulfillment_center_id" class="fba_option text_label">
								<?php echo __( 'Fulfillment Center', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('Select either Amazon US or Amazon EU.') ?>
							</label>
							<select id="wpl-fba_fulfillment_center_id" name="wpla_fba_fulfillment_center_id" class="fba_option required-entry select">
								<option value="AMAZON_NA"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_NA'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon US' ?> </option>
								<option value="AMAZON_EU"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_EU'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon EU' ?> </option>
								<option value="AMAZON_CA"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_CA'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon CA' ?> (experimental)</option>
								<option value="AMAZON_IN"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_IN'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon IN' ?> (experimental)</option>
								<option value="AMAZON_AU"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_AU'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon AU' ?> (experimental)</option>
								<option value="AMAZON_JP"  <?php if ( $wpl_fba_fulfillment_center_id == 'AMAZON_JP'  ): ?>selected="selected"<?php endif; ?>><?php echo 'Amazon JP' ?> (experimental)</option>
							</select>
							<p class="desc fba_option" style="display: block;">
								<?php echo __( 'Select your Fullfillment Center ID.', 'wp-lister-for-amazon' ); ?>
							</p>


							<label for="wpl-fba_enable_fallback" class="fba_option text_label">
								<?php echo __( 'Fallback to Seller Fulfilled', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('With this option enabled, an item will be switched from FBA to being seller-fulfilled when there is no stock in FBA but there is still stock left in WooCommerce.') ?>
							</label>
							<select id="wpl-fba_enable_fallback" name="wpla_fba_enable_fallback" class="fba_option required-entry select">
								<option value="0" <?php if ( $wpl_fba_enable_fallback != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_fba_enable_fallback == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc fba_option" style="display: block;">
								<?php echo __( 'Fall back to remaining WooCommerce stock when FBA stock reaches zero.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-fba_only_mode" class="fba_option text_label">
								<?php echo __( 'Enable FBA only mode', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('With this option enabled, WP-Lister will assume that all your items are fulfilled by Amazon, so it will enable FBA in all your product feeds automatically.<br><br>FBA Stock Sync will be enabled automatically, so when processing an FBA Inventory Report, all FBA stock levels will be synchronized to WooCommerce.<br><br>Falling back to seller fulfillment will be disabled.') ?>
							</label>
							<select id="wpl-fba_only_mode" name="wpla_fba_only_mode" class="fba_option required-entry select">
								<option value="0" <?php if ( $wpl_fba_only_mode != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_fba_only_mode == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc fba_option" style="display: block;">
								<?php echo __( 'Enable this if all your products use FBA.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-fba_stock_sync" class="fba_option text_label">
								<?php echo __( 'Enable FBA stock sync', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('When processing an FBA Inventory Report, all FBA stock levels will be synchronized to WooCommerce.<br><br>Falling back to seller fulfillment will be disabled.') ?>
							</label>
							<select id="wpl-fba_stock_sync" name="wpla_fba_stock_sync" class="fba_option required-entry select">
								<option value="0" <?php if ( $wpl_fba_stock_sync != '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'No', 'wp-lister-for-amazon' ); ?></option>
								<option value="1" <?php if ( $wpl_fba_stock_sync == '1' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Yes', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc fba_option" style="display: block;">
								<?php echo __( 'Enable this to sync FBA stock levels back to WooCommerce.', 'wp-lister-for-amazon' ); ?>
							</p>


							<label for="wpl-fba_report_schedule" class="fba_option text_label">
								<?php echo __( 'Request FBA reports', 'wp-lister-for-amazon' ) ?>
                                <?php wpla_tooltip('If you use multi-channel fulfillment with eBay orders, you should lower this option to 6 hours.') ?>
							</label>
							<select id="wpl-fba_report_schedule" name="wpla_fba_report_schedule" class="fba_option required-entry select">
								<option value="daily"        <?php if ( $wpl_fba_report_schedule == 'daily'        ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Daily', 'wp-lister-for-amazon' ) ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="twelve_hours" <?php if ( $wpl_fba_report_schedule == 'twelve_hours' ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 12 hours', 'wp-lister-for-amazon' ) ?></option>
								<option value="six_hours"    <?php if ( $wpl_fba_report_schedule == 'six_hours'    ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 6 hours', 'wp-lister-for-amazon' ) ?></option>
								<option value="three_hours"  <?php if ( $wpl_fba_report_schedule == 'three_hours'  ): ?>selected="selected"<?php endif; ?>><?php echo __( 'Every 3 hours', 'wp-lister-for-amazon' ) ?></option>
							</select>
							<p class="desc fba_option" style="display: block;">
								<?php echo __( 'Select how often FBA Shipment and Inventory Reports should be fetched from Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div>




				<?php if ( ( is_multisite() ) && ( is_main_site() ) ) : ?>
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
		jQuery( document ).ready( function($) {

			// hide FBA options if FBA is disabled
			$('#wpl-fba_enabled').change(function() {
				if ( $('#wpl-fba_enabled').val() != 1 ) {
					$('#FBAOptionsBox .fba_option').hide();
				} else {
					$('#FBAOptionsBox .fba_option').show();
				}
			}).change();

			// hide shipping provider name option unless "Other" is selected
			$('#wpl-default_shipping_provider').change(function() {
				if ( $('#wpl-default_shipping_provider').val() != 'Other' ) {
					$('#OtherSettingsBox .other_shipping_option').hide();
				} else {
					$('#OtherSettingsBox .other_shipping_option').show();
				}
			}).change();

			// Toggle Fixed Tax elements
            $('#wpl-option-orders-tax-mode').change(function() {

                // initially hide the elements
                $('.fixed_tax, .import_tax').hide();

                if ( $(this).val() == 'fixed' ) {
                    $('.fixed_tax').show();
                } else if ( $(this).val() == 'import' ) {
                    $('.import_tax').show();
                }
            }).change();

            $('#wpl-option-orders_sales_tax_action').change(function() {
                $('.show-if-sales-tax-record').hide();

                if ( $(this).val() == 'record' ) $('.show-if-sales-tax-record').show();
            }).change();

            // Toggle customer role select
            $('#wpl-option-create_customers').change(function() {
                if ( $(this).val() == 0 ) {
                    $(".show-if-create-customers").hide();
                } else {
                    $(".show-if-create-customers").show();
                }
            }).change();

            $('#wpl-orders_default_payment_method').change(function() {
                if ( $(this).val() == "" ) {
                    $(".show-if-custom-payment-gateway").show();
                } else {
                    $(".show-if-custom-payment-gateway").hide();
                }
            }).change();



		});

	</script>


</div>
