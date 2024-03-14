<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

	.postbox h3 {
	    cursor: default;
	}

</style>

<div class="wrap wpla-page">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<?php if ( $wpl_account->id ): ?>
	<h2><?php echo __( 'Edit Account', 'wp-lister-for-amazon' ) ?></h2>
	<?php else: ?>
	<h2><?php echo __( 'New Account', 'wp-lister-for-amazon' ) ?></h2>
	<?php endif; ?>
	
	<?php echo $wpl_message ?>

	<form method="post" action="<?php echo $wpl_form_action; ?>">

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box">
					<?php include('account_edit_sidebar.php') ?>
				</div>
			</div> <!-- #postbox-container-1 -->


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					

					<div class="postbox" id="GeneralSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Account settings', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<div id="titlediv" style="margin-bottom:5px;">
								<div id="titlewrap">
									<label for="title" class="text_label"><?php echo __( 'Title', 'wp-lister-for-amazon' ); ?></label>
									<input type="text" name="wpla_title" size="30" value="<?php echo $wpl_account->title; ?>" id="title" autocomplete="off" style="width:65%;">
								</div>
							</div>
							<p class="desc" style="display: block;">
								<?php echo __( 'If you are using multiple accounts or sites, each one should have a descriptive title.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-account_is_reg_brand" class="text_label">
								<?php echo __( 'Brand Registry', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If you registered your brand with Amazon, you are allowed to create new catalog products without providing a UPC/EAN.<br><br>With this option enabled, new variations will always be added to automatically to WP-Lister if the product has a listing profile assigned, even without UPC, EAN or ASIN.<br><br>It will also suppress some warning messages that you might see otherwise - which only apply to sellers who are required to provide UPCs or EANs.') ?>
							</label>
							<select id="wpl-account_is_reg_brand" name="wpla_account_is_reg_brand" title="Type" class=" required-entry select">
								<option value="0" <?php if ( $wpl_account->is_reg_brand == 0 ) echo 'selected' ?> ><?php echo __( 'No', 'wp-lister-for-amazon' ); ?> (<?php _e('default', 'wp-lister-for-amazon' ); ?>)</option>
								<option value="1" <?php if ( $wpl_account->is_reg_brand == 1 ) echo 'selected' ?> ><?php echo __( 'Yes, this account is registered as a brand with Amazon.', 'wp-lister-for-amazon' ); ?></option>
							</select>
							<p class="desc" style="display: block;">
								<?php echo __( 'Enable this if you do not need to provide UPCs or EANs.', 'wp-lister-for-amazon' ); ?>
							</p>

							<label for="wpl-account_is_active" class="text_label">
								<?php echo __( 'Active', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('If you deactivate an account, WP-Lister will stop sending feeds and fetching reports for this account.') ?>
							</label>
							<select id="wpl-account_is_active" name="wpla_account_is_active" title="Type" class=" required-entry select">
								<option value="1" <?php if ( $wpl_account->active == 1 ) echo 'selected' ?> ><?php echo __( 'Active', 'wp-lister-for-amazon' ); ?></option>
								<option value="0" <?php if ( $wpl_account->active == 0 ) echo 'selected' ?> ><?php echo __( 'Inactive', 'wp-lister-for-amazon' ); ?></option>
							</select>

							<p class="desc" style="display: block;">
								<?php echo __( 'Please do not change any of the fields below', 'wp-lister-for-amazon' ); ?>:
							</p>
	
							<label for="wpl-market_id" class="text_label">
								<?php echo __( 'Amazon site', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('This defines which Amazon marketplace this account is linked with.<br><br>To work with multiple markets you need to add one account for each market.') ?>
							</label>
							<select id="wpl-market_id" name="wpla_market_id" title="Type" class=" required-entry select">
								<option value="">-- <?php echo __( 'Please select', 'wp-lister-for-amazon' ); ?> --</option>
								<?php foreach ($wpl_amazon_markets as $market) : ?>
									<option value="<?php echo $market->id ?>" 
										<?php if ( $wpl_account->market_id == $market->id ) : ?>
											selected="selected"
										<?php endif; ?>
										><?php echo $market->title ?> <?php if ( in_array( $market->code, array('IN','JP','CN','BR') ) ): ?>(not supported)<?php endif; ?></option>
								<?php endforeach; ?>
							</select>

							<label for="wpl-marketplace_id" class="text_label">
								<?php echo __( 'Marketplace ID', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('The Marketplace ID is set when the account is created and should match the selected Amazon site above.<br><br>To fix an incorrect Marketplace ID click the "Select" button next to the correct marketplace below and hit "Update".') ?>
							</label>
							<input type="text" name="wpla_marketplace_id" id="wpl-marketplace_id" value="<?php echo str_replace('"','&quot;', $wpl_account->marketplace_id ); ?>" class="text_input" />
							<br class="clear" />

							<label for="wpl-merchant_id" class="text_label">
								<?php echo __( 'Seller ID', 'wp-lister-for-amazon' ); ?>
                                <?php wpla_tooltip('Your Seller ID, as shown on seller central.') ?>
							</label>
							<input type="text" name="wpla_merchant_id" id="wpl-merchant_id" value="<?php echo str_replace('"','&quot;', $wpl_account->merchant_id ); ?>" class="text_input" />
							<br class="clear" />

                            <label for="wpl-sp_access_token" class="text_label">
								<?php echo __( 'Access Token', 'wp-lister-for-amazon' ); ?>
							</label>
                            <textarea name="wpla_sp_access_token" id="wpl-sp_access_token"><?php echo $wpl_account->sp_access_token; ?></textarea>
							<br class="clear" />

							<label for="wpl-sp_refresh_token" class="text_label">
								<?php echo __( 'Refresh Token', 'wp-lister-for-amazon' ); ?>
							</label>
                            <textarea name="wpla_sp_refresh_token" id="wpl-sp_refresh_token"><?php echo $wpl_account->sp_refresh_token; ?></textarea>
							<br class="clear" />
						</div>
					</div>


					<div class="postbox" id="OtherMarketsBox">
						<h3 class="hndle"><span><?php echo __( 'Marketplaces', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<?php if ( is_array( $wpl_account->allowed_markets ) ) : ?>

								<p>
									This account has permission to access the following marketplaces:
								</p>

								<table style="width:100%">
								<?php foreach ($wpl_account->allowed_markets as $market) : ?>
									<tr>
										<td>
											<?php echo $market->Name ?>
										</td><td>
											<?php echo $market->DefaultCountryCode ?>
										</td><td>
											<?php echo $market->DefaultCurrencyCode ?>
										</td><td>
											<a href="http://<?php echo $market->DomainName ?>" target="_blank"><?php echo $market->DomainName ?></a>
										</td><td>
											<?php echo $market->MarketplaceId ?>
										</td><td>
											<a href="#" onclick="jQuery('#wpl-marketplace_id').prop('value','<?php echo $market->MarketplaceId ?>');return false;" class="button button-small">Select</a>
										</td>
									</tr>
								<?php endforeach; ?>
								</table>

							<?php else : ?>

								<p>
									Error: This account does not seem to be valid. Please check your MWS credentials and update the account details.
								</p>

							<?php endif; ?>
	
						</div>
					</div>


						
				</div> <!-- .meta-box-sortables -->
			</div> <!-- #postbox-container-1 -->



		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->

	</form>


	<?php if ( get_option('wpla_log_level') > 8 ): ?>
	<pre><?php print_r($wpl_account); ?></pre>
	<?php endif; ?>


	<script type="text/javascript">

		jQuery( document ).ready( function () {

		});	
	
	</script>

</div>



	
