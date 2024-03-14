<div class="wrap">
	<h1><?php echo __('Easy Pixels by <b>JEVNET</b>','easy-pixels-by-jevnet'); ?></h1>
	<p><?php echo __('Track easily the visits on your webpage and the user actions! Plugin info at <a href="https://es.wordpress.org/plugins/easy-pixels-by-jevnet/">https://es.wordpress.org/plugins/easy-pixels-by-jevnet/</a>','easy-pixels-by-jevnet');?><br/><br/></p>

	<?php
		echo '<h2 class="nav-tab-wrapper">';
		do_action('easypixels_admintabs');
		echo '</h2>';
	?>

	<div id="poststuff" >
		<div id="post-body" class="metabox-holder columns-2">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<div id="postbox-container-1" class="postbox-container">
					<?php 
						$easyPixels->putAdminOptions();
						if (( class_exists( 'WPCF7_ContactForm' ) )&&(!class_exists( 'jn_CF7tracking' ))) {echo CF7banner();}
						if (( class_exists( 'WooCommerce' ) )&&(!class_exists( 'jn_easyGAdsWC' ))) {echo WCbanner();}
					?>

					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="submitdiv" class="postbox">
							<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"><?php  __('Save Settings','easy-pixels-by-jevnet'); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
							<h2 class="hndle ui-sortable-handle"><span> <?php echo __('Save Settings'); ?></span></h2>
							<div class="inside">
								<p id="someChangesAdvise" style="color:#a00;padding:1em;display:none"><?php  echo __('Some options had been changed. Remember to save settings or your changes will be lost.<br/>And flush all caches.','easy-pixels-by-jevnet'); ?></p>
								<p id="noChangesAdvise" style="padding:1em;"><?php echo __('There are no changes...','easy-pixels-by-jevnet'); ?></p>
								<div id="submitpost" class="submitbox">
									<div id="major-publishing-actions">
										<?php submit_button( __('Save Settings','easy-pixels-by-jevnet'), 'primary', 'wpdocs-save-settings',false ); ?>
									</div>
								</div>
							<script>
								function jn_ep_isChanged (el) {document.getElementById("noChangesAdvise").style.display="none";document.getElementById("someChangesAdvise").style.display="block";}

								function jn_ep_inputHandler() {
									if(!document.getElementsByTagName) return;
									var inputs, selects, textareas, i;
									
									inputs = document.getElementsByTagName('input');
									for(i=0;i<inputs.length;i++) {
										if(!(/submit/).test(inputs[i].getAttribute("type"))) {
											if((/jn_/).test(inputs[i].name))
											{
												inputs[i].onchange = function(){jn_ep_isChanged(this)};
											}
										}
									}
									
									selects = document.getElementsByTagName('select');
									for(i=0;i<selects.length;i++) {
										selects[i].onchange = function(){jn_ep_isChanged(this)};
									}
								}

								window.addEventListener('load',function(){jn_ep_inputHandler()})
							</script>
						</div>
					</div>
				</div>
			</div>



			<div id="postbox-container-2" class="postbox-container">
				<?php
					settings_fields('jnEasyPixelsSettings-group');
					echo '<h2 class="title"><br/><img src="'.JN_EasyPixels_URL.'/img/google.png" alt="Google" width="20px" style="margin-right:1em">'.__('Google tracking','easy-pixels-by-jevnet').'</h2>';
					$easyPixels->trackingOptions->analytics->putAdminOptions();
					$easyPixels->trackingOptions->gtm->putAdminOptions();
					$easyPixels->trackingOptions->gads->putAdminOptions();

					echo '<h2 class="title"><br/><img src="'.JN_EasyPixels_URL.'/img/msadv-ico.svg" alt="Microsoft Advertising" width="20px" style="margin-right:1em">'.__('Microsoft Advertising','easy-pixels-by-jevnet').'</h2>';
					$easyPixels->trackingOptions->bing->putAdminOptions();

					echo '<h2 class="title"><br/><img src="'.JN_EasyPixels_URL.'/img/share.png" alt="Social" width="20px" style="margin-right:1em">'.__('Social tracking','easy-pixels-by-jevnet').'</h2>
					          <table class="form-table">
          <tr>
				<td style="width:12em">&nbsp;</td><td style="width:3em"><b>'.__('Enable','easy-pixels-by-jevnet').'</b></td><td><b>'.__('Tracking Code','easy-pixels-by-jevnet').'</b></td>
          </tr>';
					$easyPixels->trackingOptions->facebook->putAdminOptions();
					$easyPixels->trackingOptions->twitter->putAdminOptions();
					$easyPixels->trackingOptions->linkedin->putAdminOptions();
					echo '<h2 class="title" style="text-decoration:underline"><br/>'.__('Other tracking','easy-pixels-by-jevnet').'</h2>';
					$easyPixels->trackingOptions->yandex->putAdminOptions();
				?>
			</div>
			<center>
				<?php submit_button( __('Save Settings','easy-pixels-by-jevnet'), 'primary', 'wpdocs-save-settings',false ); ?>
			</center>
		</form>

		</div>
	</div>
</div>

<?php

function CF7banner()
{
	return '<table style="width:100%;margin: 2% 0;border:1px solid #aaa;background:#fefefe;"><tr><td style="padding:1em;font-size:12px">'.__('Do you know you can track your forms created with Contact Form 7?','easy-pixels-by-jevnet').' <br/><br/><a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-contact-form-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button">'.__('Download','easy-pixels-by-jevnet').'</a></td><td style="width:75px"><img src="'.JN_EasyPixels_URL.'/img/contactForm.png" style="float:right;"></td></tr></table>';
}
function WCbanner()
{
	return '<table style="width:100%;margin: 2% 0;border:1px solid #aaa;background:#fefefe;"><tr><td style="padding:1em;font-size:12px">'.__('Do you know you can track your sales from WooCommerce?','easy-pixels-by-jevnet').' <br/><br/><a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-ecommerce-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button">'.__('Download','easy-pixels-by-jevnet').'</a></td><td style="width:75px"><img src="'.JN_EasyPixels_URL.'/img/woocommerce.png" style="float:right;"></td></tr></table>';
}
