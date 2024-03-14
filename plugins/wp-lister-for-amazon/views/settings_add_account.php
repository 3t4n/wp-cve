<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">
	
	#AuthSettingsBox ol li {
		margin-bottom: 25px;
	}

</style>



				<form method="post" id="addAccountForm" action="<?php echo $wpl_form_action; ?>">
					<input type="hidden" name="action" value="wpla_add_oauth_account" >
                    <?php wp_nonce_field( 'wpla_add_account' ); ?>
					<input type="hidden" name="wpla_amazon_market_code" id="wpla_amazon_market_code" value="" >

					<div class="postbox" id="AddAccountBox">
						<h3 class="hndle"><span><?php echo __( 'Add Amazon Account', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

                            <p>In order to add a new Amazon account to WP-Lister, please select the region of your account and click on the Authorize button that gets generated.</p>

							<label for="wpla-amazon_market_id" class="text_label"><?php echo __( 'Amazon Marketplace', 'wp-lister-for-amazon' ); ?>:</label>
							<select id="wpla-amazon_market_id" name="wpla_amazon_market_id" title="Site" class=" required-entry select">
								<option value="">-- <?php echo __( 'Please select', 'wp-lister-for-amazon' ); ?> --</option>
								<?php foreach ( $wpl_amazon_markets as $market ) : ?>
									<?php if ( in_array( $market->code, array('IN','JP','CN','BR') ) ) { continue; } ?>
									<option 
										value="<?php echo $market->id ?>" 
										<?php if ( isset($wpl_text_amazon_market_id) && $wpl_text_amazon_market_id == $market->id ): ?>selected="selected"<?php endif; ?>
										<?php if ( ! $market->enabled ): ?>disabled="disabled"<?php endif; ?>
										><?php echo $market->title ?> 
										<?php if ( in_array( $market->code, array('IN','JP','CN','BR') ) ): ?>(not supported yet)<?php endif; ?>
									</option>					
								<?php endforeach; ?>
							</select>

							<div id="wpla_loading_account_fields_spinner" style="display:none; text-align: center; height: 100px; padding-top: 100px;">
								<img src="<?php echo WPLA_URL ?>/img/ajax-loader.gif"/>
							</div>

							<div id="wrap_account_details" style="display:none;">
                                <div id="wrap_account_instructions">
                                    <p><?php _e('In order to add a new Amazon account to WP-Lister, you need to:', 'wp-lister-for-amazon'); ?></p>
                                    <ol>
                                        <li>
                                            <a href="#" id="wpla_btn_authorize" target="_blank" class="button-primary" style="float:right;"><?php _e( 'Authorize WP-Lister', 'wp-lister-for-amazon'); ?></a>
                                            Click the <b>Authorize WP-Lister</b> button to sign in and authorize the WP-Lister application.
                                            <br/>
                                            <small>
                                                This will open the Amazon sign in page in a new window.
                                            </small>
                                            <br/>
                                            <small>Please sign in, grant access for WP-Lister and close the new window to come back here.</small>
                                        </li>
                                        <li>
                                            <input style="float:right;" type="submit" value="Fetch Token" name="submit" class="button">
                                            After linking WP-Lister with your Amazon account, click here to fetch your token.
                                            <br/>
                                            <small>
                                                After retrieving your token, we will proceed with the first time set up.
                                            </small>
                                        </li>
                                    </ol>
                                    <p>

                                    </p>
                                </div>
                                <div class="clear"></div>

                                <?php if ( isset( $_REQUEST['selling_partner_id'], $_REQUEST['spapi_oauth_code'] ) ): ?>
                                    <label for="wpla_account_title" class="text_label"><?php echo __( 'Title', 'wp-lister-for-amazon' ); ?>:</label>
                                    <input type="text" name="wpla_account_title" value="<?php echo @$wpla_account_title ?>" class="text_input" placeholder="Enter any name you like - for example 'Amazon US'"/>

                                    <label for="wpla_merchant_id" class="text_label"><?php echo __( 'Seller ID', 'wp-lister-for-amazon' ); ?>:</label>
                                    <input type="text" name="wpla_merchant_id" id="wpla_merchant_id" value="<?php echo esc_attr($_REQUEST['selling_partner_id']); ?>" class="text_input" placeholder="Your Seller ID should look like 'A123456BCDEFGH'" />

                                    <div id="wrap_account_fields_DEV" style="display:none">
                                        <label for="wpla_access_key_id" class="text_label"><?php echo __( 'AWS Access Key ID', 'wp-lister-for-amazon' ); ?>:</label>
                                        <input type="text" name="wpla_access_key_id" id="wpla_access_key_id" value="<?php echo @$wpla_access_key_id ?>" class="text_input" />

                                        <label for="wpla_secret_key" class="text_label"><?php echo __( 'Secret Key', 'wp-lister-for-amazon' ); ?>:</label>
                                        <input type="text" name="wpla_secret_key" id="wpla_secret_key" value="<?php echo @$wpla_secret_key ?>" class="text_input" />
                                    </div>

                                    <input type="hidden" name="wpla_marketplace_id" id="wpla_marketplace_id" value="<?php echo @$wpla_marketplace_id ?>" class="" />

                                    <p>
                                        <a href="#" id="wpla_btn_add_account" class="button-primary" style="float:left;">Add new account</a>
                                    </p>
                                    <br style="clear:both" />
                                <?php endif; ?>

							</div>

						</div>
					</div>


				</form>


	<div id="debug_output" style="display:none">
		<?php // echo "<pre>";print_r($wpl_amazon_accounts);echo"</pre>"; ?>
	</div>

	<script type="text/javascript">

		function wpla_load_market_details( market_id ) {
	
	        // load market details
	        var params = {
	            action: 'wpla_load_market_details',
	            market_id: market_id,
	            _wpnonce: wpla_JobRunner_i18n.wpla_ajax_nonce
	        };
	        var jqxhr = jQuery.getJSON(
	            ajaxurl,
                params,
                function( response ) {

                    jQuery('#wpla_amazon_market_code').prop( 'value', response.code );
                    jQuery('#wpla_marketplace_id'    ).prop( 'value', response.marketplace_id );
                    jQuery('#wpla_btn_authorize').prop('href', response.oauth_url);

                    // // show fields and instructions depending on region
                    // if ( response.region_code == 'NA' ){
                    //     jQuery('#wrap_account_instructions_US').show();	// America
                    //     jQuery('#wrap_account_instructions_EU').hide();
                    //     jQuery('#wrap_account_instructions_AU').hide();
                    //     jQuery('#wrap_account_instructions_AS').hide();
                    //     jQuery('#wrap_account_fields_DEV'     ).hide();
                    //     jQuery('#wrap_account_fields_NONDEV'  ).show();
                    // } else if ( response.region_code == 'EU' ){
                    //     jQuery('#wrap_account_instructions_US').hide(); // Europe
                    //     jQuery('#wrap_account_instructions_EU').show();
                    //     jQuery('#wrap_account_instructions_AU').hide();
                    //     jQuery('#wrap_account_instructions_AS').hide();
                    //     jQuery('#wrap_account_fields_DEV'     ).hide();
                    //     jQuery('#wrap_account_fields_NONDEV'  ).show();
                    // } else if ( response.code == 'AU' ){
                    //     jQuery('#wrap_account_instructions_US').hide(); // Australia
                    //     jQuery('#wrap_account_instructions_EU').hide();
                    //     jQuery('#wrap_account_instructions_AU').show();
                    //     jQuery('#wrap_account_instructions_AS').hide();
                    //     jQuery('#wrap_account_fields_DEV'     ).hide();
                    //     jQuery('#wrap_account_fields_NONDEV'  ).show();
                    // } else {
                    //     jQuery('#wrap_account_instructions_US').hide();	// unsupported sites - Asia/Pacific, India, Brazil
                    //     jQuery('#wrap_account_instructions_EU').hide();
                    //     jQuery('#wrap_account_instructions_AU').hide();
                    //     jQuery('#wrap_account_instructions_AS').show();
                    //     jQuery('#wrap_account_fields_DEV'     ).hide();
                    //     jQuery('#wrap_account_fields_NONDEV'  ).hide();
                    // }

                    jQuery('#wpla_loading_account_fields_spinner').slideUp(300);
                    jQuery('#wrap_account_details').slideDown(300);

                }).fail(function(e,xhr,error) {
                    // alert( "There was a problem fetching the job list. The server responded:\n\n" + e.responseText );
                    console.log( "error", xhr, error );
                    console.log( e.responseText );
                    jQuery('#debug_output').html( e.responseText );
                });
	        /*.success( )
	        .error( function(e,xhr,error) { 
	            // alert( "There was a problem fetching the job list. The server responded:\n\n" + e.responseText ); 
	            console.log( "error", xhr, error ); 
	            console.log( e.responseText ); 
	            jQuery('#debug_output').html( e.responseText );
	        });*/

		}

		jQuery( document ).ready(
			function () {
		
				// amazon site selector during install: submit form on selection
				jQuery('#AddAccountBox #wpla-amazon_market_id').change( function(event, a, b) {					

					var market_id = event.target.value;
					if ( market_id ) {

						jQuery('#wrap_account_details').slideUp(300);						
						jQuery('#wpla_loading_account_fields_spinner').slideDown(300);						

						wpla_load_market_details( market_id );

					} else {
						jQuery('#wrap_account_details').slideUp(300);						
					}
					
				});

				// add new account button
				jQuery('#wpla_btn_add_account').click( function() {					
					jQuery('#addAccountForm').first().submit();
					return false;
				});

				// confirm delete
				// jQuery('#delete_account').click( function() {					
				// 	return confirm('Do you really want to do this?');				
				// });

			}
		);
	
	</script>
