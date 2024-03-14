<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

#postbox-container-2 .postbox {
/*	float: left;
	margin-right: 1em;
	width: 290px;
	min-width: 255px;
	max-width: 315px;
*/}

div#TemplateUploader .inside {
    margin: 0 !important;
    padding: 0;
}
    div.secondary-actions {
        padding: 10px;
        clear: both;
        border-top: 1px solid #ddd;
        background: #f5f5f5;
    }

    div.secondary-action {
        text-align: right;
        float: right;
        line-height: 23px;
    }

</style>

<div class="wrap wpla-page">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
          
	<?php include_once( dirname(__FILE__).'/settings_tabs.php' ); ?>		
	<?php echo $wpl_message ?>

	<form method="post" id="settingsForm" action="<?php echo $wpl_form_action; ?>" enctype="multipart/form-data">

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
										<p>
											<?php echo __( 'Select the categories you want to use and click "Update".', 'wp-lister-for-amazon' ); ?>
										</p>
										<p>
											<?php echo __( 'Note: Category feed templates should be updated from time to time to reflect the latest changes on Amazon.', 'wp-lister-for-amazon' ); ?>
										</p>
									</div>
								</div>

								<div id="major-publishing-actions">
									<div id="publishing-action">
										<a href="#" onclick="jQuery('.wpla_categories input').prop('checked',false);return false;" class="button button-secondary"><?php echo __( 'Deselect all', 'wp-lister-for-amazon' ); ?></a>
										<input type="hidden" name="action" value="save_wpla_tpl_btg_settings" >
                                        <?php wp_nonce_field( 'wpla_save_tpl_settings' ); ?>
										<input type="submit" value="<?php echo __( 'Update', 'wp-lister-for-amazon' ); ?>" id="save_settings" class="button-primary" name="save">
									</div>
									<div class="clear"></div>
								</div>

							</div>

						</div>
					</div>

                    <div class="postbox" id="TemplateUploader">
                        <h3 class="hndle"><span><?php echo __( 'Upload Custom Feed Template', 'wp-lister-for-amazon' ); ?></span></h3>
                        <div class="inside">
                            <div class="misc-pub-section">
                                <!-- <p><?php _e( 'Upload your custom XLSM file', 'wp-lister-for-amazon' ); ?></p> -->

                                <p><?php _e( 'Here you can upload your own XLSM feed template, generated on Seller Central.', 'wp-lister-for-amazon' ); ?></p>
                                <p><small><?php _e( 'To generate your own feed templates, log in to Seller Central, visit Inventory / Add Products via Upload and open the "Download an Inventory file" tab.', 'wp-lister-for-amazon' ); ?></small></p>

                                <label for="feed_template" class="text_label"><?php _e( 'Template File', 'wp-lister-for-amazon' ); ?></label>
                                <input type="file" name="feed_template" id="feed_template" class="required" />

                                <label for="template_marketplace" class="text_label"><?php _e( 'Marketplace', 'wp-lister-for-amazon' ); ?></label>
                                <select name="template_marketplace" id="template_marketplace" class="required select">
                                    <?php foreach ( $wpl_amazon_markets as $market ) : ?>
                                        <?php if ( in_array( $market->code, array('IN','JP','CN','BR') ) ) { continue; } ?>
                                        <option value="<?php echo $market->code ?>"><?php echo $market->title ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <p><small><?php _e( 'Note: Do not rename your XLSM file because the category and title are extracted from its filename.', 'wp-lister-for-amazon' ); ?></small></p>
                            </div>
                            <div class="secondary-actions">
                                <div class="secondary-action">
                                    <input type="submit" value="<?php echo __( 'Install template', 'wp-lister-for-amazon' ); ?>" id="upload_template" class="button-primary" name="upload">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

					<div class="postbox" id="TemplateVersionsBox">
						<h3 class="hndle"><span><?php echo __( 'Installed Feed Templates', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">


							<!-- <h4>Categories</h4> -->
							<ul>
							<?php foreach ( $wpl_installed_templates as $template ) : ?>
								<?php 
									$profile_count = WPLA_AmazonProfile::countProfilesUsingTemplate( $template->id );
									$remove_link   = 'admin.php?page=wpla-settings&tab=categories&action=wpla_remove_tpl&tpl_id='.$template->id .'&_wpnonce='. wp_create_nonce( 'wpla_remove_tpl' );
								?>
								
								<li>
									<hr>
									<?php if ( $profile_count == 0 ) : ?>
										<a href="<?php echo $remove_link ?>" style="float:right; margin-top:1.3em;" class="button button-small"><?php echo __( 'Remove', 'wp-lister-for-amazon' ) ?></a>
									<?php endif; ?>

									<?php echo $template->title == 'Offer' ? 'Listing Loader' : $template->title ?>
									(<?php echo WPLA_AmazonMarket::getMarketCode( $template->site_id )?>)<br>

									<?php if ( $template->version ) : ?>
										<small>Version <?php echo $template->version ?></small> <br>
									<?php endif; ?>

									<?php if ( $profile_count ) : ?>
										<small>Currently used in <?php echo $profile_count ?> profile(s)</small> <br>
									<?php else : ?>
										<small>Not used in any profile</small> <br>
									<?php endif; ?>

									<?php if ( 'fpt' == substr( $template->name, 0, 3 ) && $template->signature == '' ) : ?>
										<small style="color:darkred;">Signature is missing. Please update this template!</small> <br>
									<?php endif; ?>

								</li>

							<?php endforeach; ?>
							</ul>

							<?php #echo "<pre>";print_r($wpl_installed_templates);echo"</pre>";#die(); ?>
						</div>
					</div>


				</div>
			</div> <!-- #postbox-container-1 -->


			<!-- #postbox-container-2 -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					

					<?php foreach ( $wpl_file_index as $site_code => $amazon_site ) : ?>

					<?php
						$site = WPLA_AmazonMarket::getMarketByCountyCode( $site_code );
						$site_id = $site->id;
					?>

					<div class="postbox wpla_categories" id="CategoriesBox-<?php echo $site_code ?>">
						<h3 class="hndle"><span><?php echo $amazon_site['site'] ?></span></h3>
						<div class="inside">

							<!-- <h4>Categories</h4> -->
							<ul>
							<?php foreach ( $amazon_site['categories'] as $category_name => $category ) : ?>
								
								<?php
									$field_name = 'wpla_cat-'.$site_code.'-'.$category_name;
									$category_file_name = $category_name == 'CE'   ? 'ConsumerElectronics' : $category_name; // template name is ConsumerElectronics, but file name is CE :-(
									$category_file_name = $category_name == 'SWVG' ? 'SoftwareVideoGames'  : $category_name; // template name is SoftwareVideoGames, but file name is SWVG
									$checked = in_array( $site_id.$category_file_name, $wpl_active_templates ) ? 'checked' : '';
									// some templates have a lower case name, like clothing UK or entertainmentcollectibles US
									if ( ! $checked ) $checked = in_array( strtolower( $site_id.$category_file_name ), $wpl_active_templates ) ? 'checked' : '';
									// newer templates are now prefixed with "fptcustom"
                                    if ( ! $checked ) $checked = in_array( $site_id.'fptcustom-'. strtolower( $category_file_name ), $wpl_active_templates ) ? 'checked' : '';
								?>
								
								<li style="float:left;width:49%;">
									<input type="checkbox" name="<?php echo $field_name ?>" id="<?php echo $field_name ?>" <?php echo $checked ?> value="1">
									<label for="<?php echo $field_name ?>">
										<?php echo $category['title'] ?>
									</label>
								</li>

							<?php endforeach; ?>
							</ul>
							
							<br style="clear:both;"/>
							<?php #echo "<pre>";print_r($amazon_site);echo"</pre>"; ?>
						</div>
					</div>

					<?php endforeach; ?>

				</div> <!-- .meta-box-sortables -->
			</div> <!-- #postbox-container-1 -->



		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->

	</form>

	<?php #echo "<pre>";print_r($wpl_active_templates);echo"</pre>"; ?>




	<script type="text/javascript">
		jQuery( document ).ready(
			function () {

				jQuery('.wpla_categories input').on('click', function(event) {
					// event.preventDefault();
					var clicked_input_id = this.id;
					var listingloader_id = clicked_input_id.substr(0,12) + 'ListingLoader';

					console.log(this.id);
					console.log(listingloader_id);					

					if ( clicked_input_id.indexOf('ListingLoader') > 0 ) return;

					jQuery('#'+listingloader_id).prop('checked', 'checked');
				});		

			}
		);
	
	</script>


</div>