<?php


// Misc Settings

add_action( 'ucd_settings_content', 'ucd_misc_page' );
function ucd_misc_page() {
		global $ucd_active_tab;
		if ( 'misc' != $ucd_active_tab )
		return;
?>

  	<h3><?php _e( 'Misc Settings', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-misc' );
				do_settings_sections( 'ultimate-client-dash-misc' );
				?>

						<!-- Misc Option Section -->

						<div class="ucd-inner-wrapper settings-misc">
						<p class="ucd-settings-desc">Rebrand your WordPress theme and disable WordPress notifications and nags, PHP errors, and auto update emails.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>

                <tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Rebrand Theme', 'ultimate-client-dash' ) ?></h2></th>
								</tr>

			                <tr class="ucd-pro-version">
			                <th><?php _e( 'Theme Name', 'ultimate-client-dash' ) ?>
			                <p>
			                Replace any use of the theme name in the WordPress dashboard.
			                </p></th>
			                <td><input class="regular-text" type="text" placeholder="WP Codeus" name="ucd_pro_misc_theme_name" value="<?php echo esc_attr( get_option('ucd_pro_misc_theme_name') ); ?>" size="70" />
			                <p>
			                Enter theme name you wish to replace. Works with most themes...
			                </p>
			                </td>
			                </tr>

			                <tr class="ucd-pro-version">
			                <th><?php _e( 'Rebrand Name', 'ultimate-client-dash' ) ?>
											<p>
											Name which will replace the theme name.
											</p></th>
			                <td>
			                <input class="regular-text" type="text" placeholder="WPC" name="ucd_pro_misc_custom_name" value="<?php echo esc_attr( get_option('ucd_pro_misc_custom_name') ); ?>" size="70" />
			                <p>
			                Enter the rebrand name you wish to use. Works with most themes...
			                </p>
			                </td>
			                </tr>

                <tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Notifications', 'ultimate-client-dash' ) ?></h2></th>
								</tr>

                      <tr class="ucd-pro-version">
                      <th><?php _e( 'Disable Client Nags & Notices', 'ultimate-client-dash' ) ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_pro_misc_client_nags" value="ucd_pro_misc_client_nags" <?php checked( 'ucd_pro_misc_client_nags', get_option( 'ucd_pro_misc_client_nags' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>Toggle to disable all WordPress Core update notifications and nags in the WordPress dashboard for user role 'Client'.</p>
                      </td>
                      </tr>

                      <tr class="ucd-pro-version">
                      <th><?php _e( 'Disable Fatal Error Protection', 'ultimate-client-dash' ) ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_pro_misc_fatal_error" value="ucd_pro_misc_fatal_error" <?php checked( 'ucd_pro_misc_fatal_error', get_option( 'ucd_pro_misc_fatal_error' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>Toggle to disable fatal PHP error protection email notifications. Added in WordPress 5.2.</p>
                      </td>
                      </tr>

                      <tr class="ucd-pro-version">
                      <th><?php _e( 'Disable Auto Update Email', 'ultimate-client-dash' ) ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_pro_misc_update_email" value="ucd_pro_misc_update_email" <?php checked( 'ucd_pro_misc_update_email', get_option( 'ucd_pro_misc_update_email' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>Toggle to disable WordPress auto update email notification.</p>
                      </td>
                      </tr>

                  <tr class="ucd-title-holder">
  								<th><h2 class="ucd-inner-title"><?php _e( 'Extra Settings', 'ultimate-client-dash' ) ?></h2></th>
  								</tr>

												<tr>
												<th><?php _e( 'Frontend Admin Bar Styling', 'ultimate-client-dash' ) ?></th>
												<td><label class="ucd-switch"><input type="checkbox" name="ucd_misc_admin_bar_frontend_styling" value="ucd_misc_admin_bar_frontend_styling" <?php checked( 'ucd_misc_admin_bar_frontend_styling', get_option( 'ucd_misc_admin_bar_frontend_styling' ) ); ?> /><span class="ucd-slider round"></span></label>Disable
												<p>Disable the frontend admin bar color styling.</p>
												</td>
												</tr>

												<tr class="ucd-pro-version">
												<th><?php _e( 'Hide UCD Plugin', 'ultimate-client-dash' ) ?></th>
												<td><label class="ucd-switch"><input type="checkbox" name="ucd_pro_misc_hide_plugin" value="ucd_pro_misc_hide_plugin" <?php checked( 'ucd_pro_misc_hide_plugin', get_option( 'ucd_pro_misc_hide_plugin' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
												<p>Toggle to hide Ultimate Client Dash from the plugin list for user role 'Client'.</p>
												</td>
												</tr>

									<tr class="ucd-title-holder">
									<th><h2 class="ucd-inner-title"><?php _e( 'Customization', 'ultimate-client-dash' ) ?></h2></th>
									</tr>

											<tr>
											<td style="padding-left: 0px!important; padding-top: 10  px!important;"><p>Add your own customization options here. Click here to view our online <a href="https://ultimateclientdash.com/hooks/" target="_blank">documention</a>.</p></td>
											</tr>

											<!-- Action hook to allow developers to add their own settings to Ultimate Client Dash -->
											<?php do_action( 'ucd_hook_misc_customization' ); ?>

											<tr class="ucd-float-option">
											<th class="ucd-save-section">
											<?php submit_button(); ?>
											</th>
											</tr>

								</tbody>
								</table>
						</div>
      </form>
<?php }
