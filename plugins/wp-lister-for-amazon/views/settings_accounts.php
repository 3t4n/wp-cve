<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">
	
	div.tablenav.top { display: none; }

	th.column-site {
		width: 20%;
	}
	th.column-status {
		width: 15%;
	}

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



    div.sandbox_label {
        position: absolute;
        top: 0;
        right: 0;
        border: 1px solid red;
        border-radius: 0px 0 0 4px;
        padding: 0 3px;
        background: red;
        color: #fff;
        font-size: 10px;
    }

    table.accounts tbody tr {
        position: relative;
    }

</style>

<div class="wrap wpla-page">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
          
	<?php include_once( dirname(__FILE__).'/settings_tabs.php' ); ?>		
	<?php echo $wpl_message ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box">


					<!-- first sidebox -->
					<div class="postbox" id="submitdiv">
						<!--<div title="Click to toggle" class="handlediv"><br></div>-->
						<h3 class="hndle"><span><?php echo __( 'Account Status', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<div id="submitpost" class="submitbox">

								<div id="misc-publishing-actions">
									<div class="misc-pub-section">
										<?php if ( sizeof( $wpl_amazon_accounts ) == 0 ) : ?>
											<p><?php echo __( 'WP-Lister is not linked to your Amazon account yet.', 'wp-lister-for-amazon' ) ?></p>
										<?php else : ?>
											<p><?php echo __( 'Great, you have added at least one account.', 'wp-lister-for-amazon' ) ?></p>
										<?php endif; ?>

										<?php if ( ! $wpl_default_account ) : ?>
											<p><?php echo __( 'You need to select a default account.', 'wp-lister-for-amazon' ) ?></p>
										<?php endif; ?>

									</div>
								</div>

								<div id="major-publishing-actions">
									<div id="publishing-action">
										<!-- <input type="submit" value="<?php echo __( 'Save Settings', 'wp-lister-for-amazon' ); ?>" id="save_settings" class="button-primary" name="save"> -->
									</div>
									<div class="clear"></div>
								</div>

							</div>

						</div>
					</div>

					<div class="postbox" id="HelpInfoBox">
						<h3 class="hndle"><span><?php echo __( 'Help', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">
							
							<p>
								<h4><?php _e( 'Adding your Amazon account', 'wp-lister-for-amazon' ) ?></h4>
								<ol>
									<li><?php _e( 'Enter a short account title', 'wp-lister-for-amazon' ) ?></li>
									<li><?php _e( 'Select an Amazon marketplace', 'wp-lister-for-amazon' ) ?></li>
									<li><?php _e( 'Follow the step-by-step instructions which will appear below', 'wp-lister-for-amazon' ) ?></li>
								</ol>
							</p>

						</div>
					</div>

				</div>
			</div> <!-- #postbox-container-1 -->


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					
				<?php if ( sizeof( $wpl_amazon_accounts ) == 0 ) : ?>
				
					<div class="postbox" id="AuthSettingsBox">
						<h3 class="hndle"><span><?php echo __( 'Amazon authorization', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">
							<p><strong><?php echo __( 'Follow these steps to link WP-Lister with your Amazon account', 'wp-lister-for-amazon' ) ?></strong></p>

							<p>
								<?php _e( 'The Amazon Marketplace Web Service (MWS) allows WP-Lister to communicate with your Amazon Seller Account.', 'wp-lister-for-amazon' ) ?>
								<?php _e( 'Before you can start repricing, you will need to sign up for MWS, and then grant WP-Lister access to your account.', 'wp-lister-for-amazon' ) ?>
								<?php _e( 'In addition, once you sign up for MWS, you will be given your Seller ID, Marketplace ID, AWS Access Key ID, and Secret Key.', 'wp-lister-for-amazon' ) ?>
							</p>

						</div>
					</div>

				<?php else: ?>

					<!-- show accounts table -->
				    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
				    <form id="accounts-filter" method="post" action="<?php echo $wpl_form_action; ?>" >
				        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
				        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ) ?>" />
				        <!-- Now we can render the completed list table -->
				        <?php $wpl_accountsTable->display() ?>
				    </form>

					<div class="postbox" id="AccountsBox" style="display:none">
						<h3 class="hndle"><span><?php echo __( 'Accounts', 'wp-lister-for-amazon' ) ?></span></h3>
						<div class="inside">

						</div>
					</div>

				<?php endif; // $wpl_amazon_accounts ?>

				<?php require_once('settings_add_account.php') ?>

				</div> <!-- .meta-box-sortables -->
			</div> <!-- #postbox-container-1 -->


		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->


	<script type="text/javascript">
		jQuery( document ).ready(
			function () {

				// account details button
				jQuery('.wpla_btn_edit_account').click( function( ) {					
					jQuery( this ).nextAll('.amazon_account_details').slideToggle(300);
					return false;
				});

				// ask again before deleting items
				jQuery('a.delete').on('click', function() {
					return confirm("<?php echo __( 'Are you sure you want to remove this account from WP-Lister?', 'wp-lister-for-amazon' ) ?>");
				})
				// ask again before deleting items
				jQuery('.row-actions .delete_account a').on('click', function() {
					return confirm("<?php echo __( 'Are you sure you want to remove this account from WP-Lister?', 'wp-lister-for-amazon' ) ?>");
				})

			}
		);
	
	</script>


</div>