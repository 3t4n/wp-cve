<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

	.inside p {
		width: 70%;
	}

	a.right,
	input.button {
		float: right;
	}

</style>

<div class="wrap">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<!-- <h2><?php echo __( 'Tools', 'wp-lister-for-amazon' ) ?></h2> -->

	<?php include_once( dirname(__FILE__).'/tools_tabs.php' ); ?>
	<?php echo $wpl_message ?>


	<div style="width:640px;" class="postbox-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				
				<div class="postbox" id="AutoMatchToolBox">
					<h3 class="hndle"><span><?php echo __( 'Bulk Listing', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_match_all_unlisted_with_asin" />
								<input type="submit" value="<?php echo __( 'Match all ASINs', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p>
									<?php echo __( 'This will find products with ASINs which do not exist on the Listings page. It will then create new listings with the status "matched".', 'wp-lister-for-amazon' ); ?>
								</p>
								<p>
									<?php echo __( 'If you have added ASINs to your WooCommerce products using a CSV import tool, use this to add them to WP-Lister automatically.', 'wp-lister-for-amazon' ); ?>
								</p>
								<p>
									<?php 
										$default_account_id = get_option( 'wpla_default_account_id', 1 );
										$account = WPLA_AmazonAccount::getAccount( $default_account_id );
									?>
									<i>Default Account: <?php echo $account->title ?> (<?php echo $account->market_code ?>)</i> <br>
									<i>Maximum batch size: 1000 items</i> <br>
								</p>
						</form>
						<!br style="clear:both;"/>

					</div>
				</div> <!-- postbox -->


				<div class="postbox" id="DatabaseToolBox">
					<h3 class="hndle"><span><?php echo __( 'Database', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_refresh_minmax_prices_from_wc" />
								<input type="submit" value="<?php echo __( 'Refresh Min./Max. Prices', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Update minimum and maxmimum prices from WooCommerce and submit Price and Quantity feed to Amazon.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_fix_stale_postmeta" />
								<input type="submit" value="<?php echo __( 'Fix stale postmeta records', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Clear wp_postmeta table from stale records without posts.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_fix_orphan_child_products" />
								<input type="submit" value="<?php echo __( 'Fix orphaned child products', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Clear wp_post table from child variations without parent product.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_fix_deleted_products" />
								<input type="submit" value="<?php echo __( 'Remove listings without product', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Clear wp_amazon_listings table from listings where the WooCommerce product has been deleted.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_remove_all_imported_products" />
								<input type="submit" value="<?php echo __( 'Remove all imported products', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p>
									<?php echo __( 'This will remove all products and listings that have been imported from Amazon.', 'wp-lister-for-amazon' ); ?>
									<?php echo __( 'Only use this if you want to start from scratch!', 'wp-lister-for-amazon' ); ?>
								</p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_upgrade_tables_to_utf8mb4" />
								<input type="submit" value="<?php echo __( 'Convert tables to utf8mb4', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p>
									<?php echo __( 'Manually upgrade tables that were skipped by the WordPress updater.', 'wp-lister-for-amazon' ); ?>
									<?php echo __( 'Please backup your database. Only applicable for WordPress 4.2+.', 'wp-lister-for-amazon' ); ?>
								</p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_repair_crashed_tables" />
								<input type="submit" value="<?php echo __( 'Repair crashed tables', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p>
									<?php echo __( 'Check and repair MySQL tables.', 'wp-lister-for-amazon' ); ?>
								</p>
						</form>
						<!-- <br style="clear:both;"/> -->

					</div>
				</div> <!-- postbox -->

				<div class="postbox" id="UpdateToolsBox">
					<h3 class="hndle"><span><?php echo __( 'Tools', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">

						<!--
						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="update_amazon_orders_30" />
								<input type="hidden" name="days" value="30" />
								<input type="submit" value="<?php echo __( 'Update Amazon orders', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Load all orders within 30 days from Amazon.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>
						-->

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_run_daily_schedule" />
								<input type="submit" value="<?php echo __( 'Run daily schedule', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p>
									<?php echo __( 'Manually trigger the daily task schedule.', 'wp-lister-for-amazon' ); ?>
									(<?php echo sprintf( __( 'Last run: %s ago', 'wp-lister-for-amazon' ), human_time_diff( get_option('wpla_daily_cron_last_run') ) ) ?>)
								</p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_run_update_schedule" />
								<input type="submit" value="<?php echo __( 'Run update schedule', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Manually run scheduled background tasks.', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_run_autosubmit_fba_orders" />
								<input type="submit" value="<?php echo __( 'Run FBA autosubmission', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Submit recent matching WC orders (24h) to FBA.', 'wp-lister-for-amazon' ); ?></p>
						</form>
                        <br style="clear:both;"/>

                        <form method="post" action="<?php echo $wpl_form_action; ?>">
                            <?php wp_nonce_field( 'wpla_tools_page' ); ?>
                            <input type="hidden" name="action" value="wpla_import_wple_product_ids" />
                            <input type="submit" value="<?php echo __( 'Import WPLE Product IDs', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
                            <p><?php echo __( 'Import UPC/EAN from WP-Lister for eBay', 'wp-lister-for-amazon' ); ?></p>
                        </form>


					</div>
				</div> <!-- postbox -->


				<?php if ( get_option('wpla_log_level') > 1 ): ?>
				<div class="postbox" id="DebuggingToolBox">
					<h3 class="hndle"><span><?php echo __( 'Debug Log', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">

						<form method="post" action="admin-ajax.php" target="_blank">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_tail_log" />
                                <?php wp_nonce_field( 'wpla_tail_log' ); ?>
								<input type="submit" value="<?php echo __( 'View debug log', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Open logfile viewer in new tab', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="wpla_clear_log" />
								<input type="submit" value="<?php echo __( 'Clear debug log', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Current log file size', 'wp-lister-for-amazon' ); ?>: <?php echo round($wpl_log_size/1024/1024,1) ?> mb</p>
						</form>
						<!-- <br style="clear:both;"/> -->

					</div>
				</div> <!-- postbox -->
				<?php endif; ?>

				<div class="postbox dev_box" id="DeveloperToolBox" style="display:none;">
					<h3 class="hndle"><span><?php echo __( 'Debug', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="update_amazon_time_offset" />
								<input type="submit" value="<?php echo __( 'Test Amazon connection', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Test connection to Amazon API', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

						<form method="post" action="<?php echo $wpl_form_action; ?>">
								<?php wp_nonce_field( 'wpla_tools_page' ); ?>
								<input type="hidden" name="action" value="test_curl" />
								<input type="submit" value="<?php echo __( 'Test Curl / PHP connection', 'wp-lister-for-amazon' ); ?>" name="submit" class="button button-primary">
								<p><?php echo __( 'Check availability of CURL php extension and show phpinfo()', 'wp-lister-for-amazon' ); ?></p>
						</form>
						<br style="clear:both;"/>

					</div>
				</div> <!-- postbox -->

			</div>
		</div>
	</div>

	<br style="clear:both;"/>

	<?php if ( get_option('wpla_log_level') > 5 ): ?>
		<pre><?php print_r($wpl_debug); ?></pre>
	<?php endif; ?>

	<?php if ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'test_curl' ): ?>
		
		<?php if( extension_loaded('curl') ) : ?>
			cURL extension is loaded
			<pre>
				<?php $curl_version = curl_version(); print_r($curl_version) ?>
			</pre>

		<?php else: ?>
			cURL extension is not installed!
		<?php endif; ?>
		<br style="clear:both;"/>

		<?php
			// test for command line app
			echo "cURL command line version:<br><pre>";
			echo `curl --version`;
			echo "</pre>";
		?>
		<br style="clear:both;"/>

		<?php phpinfo() ?>
	<?php endif; ?>


</div>