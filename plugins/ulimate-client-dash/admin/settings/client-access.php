<?php


// Client Access Settings

add_action( 'ucd_settings_content', 'ucd_client_access_page' );
function ucd_client_access_page() {
		global $ucd_active_tab;
		if ( 'client-access' != $ucd_active_tab ) {
				return;
		}
?>

	<h3><?php _e( 'Assign Client Capabilities', 'ultimate-client-dash' ); ?></h3>

	<!-- Begin settings form -->
	<form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-client' );
				do_settings_sections( 'ultimate-client-dash-client' );
				?>

						<!-- Client Option Section -->

						<div class="ucd-inner-wrapper settings-client-access">
						<p class="ucd-settings-desc" style="padding-bottom: 6px;">Limiting user or client capabilities on a WordPress website can be very useful. To limit a clients capabilities, assign their account to the new role "Client" and select the options below. Clients will no longer be able to access or see these features and menu items. Select which capabilities you would like to give your client. To learn more view our online <a href="https://ultimateclientdash.com/client-access/" target="_blank">documentation</a>.</p>

						<div class="ucd-form-message"><?php settings_errors( 'ucd-notices' ); ?></div>

								<table class="form-table">
										<tbody>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Appearance', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_appearance" value="ucd_role_apperearance" <?php checked( 'ucd_role_apperearance', get_option( 'ucd_client_appearance' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>

												<div class="ucd-dynamic-item ucd-dynamic-pro">
														<div class="ucd-item-name"><?php _e( 'Settings', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_settings" value="ucd_client_settings" <?php checked( 'ucd_client_settings', get_option( 'ucd_client_settings' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>

												<div class="ucd-dynamic-item ui-state-default top-menu">
														<a class="ucd-item-toggle" href="#"></a>
														<div class="ucd-item-name"><?php _e( 'Manage Users', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_manage_users" value="ucd_client_manage_users" <?php checked( 'ucd_client_manage_users', get_option( 'ucd_client_manage_users' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>

												<div class="ucd-dynamic-subitems-wrap ucd-dynamic-capabilities ucd-item-hidden">
														<div class="ucd-dynamic-subitems-flex">
																<div class="ucd-dynamic-item ui-state-default submenu">
																<div class="ucd-item-name"><?php _e( 'Manage Administrators', 'ultimate-client-dash' ); ?><span style="opacity: 0.45; font-size: 12px; padding-left: 7px"> - Manage users must be enabled</span></div>
																<div class="ucd-item-capabilities">
																		<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_manage_administrators" value="ucd_client_manage_administrators" <?php checked( 'ucd_client_manage_administrators', get_option( 'ucd_client_manage_administrators' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
																</div>
																</div>
														</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Manage Plugins', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_manage_plugins" value="ucd_client_manage_plugins" <?php checked( 'ucd_client_manage_plugins', get_option( 'ucd_client_manage_plugins' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Manage Themes', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_manage_themes" value="ucd_client_manage_themes" <?php checked( 'ucd_client_manage_themes', get_option( 'ucd_client_manage_themes' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Update WordPress', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_update_capability" value="ucd_client_update_capability" <?php checked( 'ucd_client_update_capability', get_option( 'ucd_client_update_capability' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
													 	</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Edit Code Files', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_edit_files" value="ucd_client_edit_files" <?php checked( 'ucd_client_edit_files', get_option( 'ucd_client_edit_files' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
													 	</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Import', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_import" value="ucd_client_import" <?php checked( 'ucd_client_import', get_option( 'ucd_client_import' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
												 		</div>
												</div>

												<div class="ucd-dynamic-item">
														<div class="ucd-item-name"><?php _e( 'Export', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_export" value="ucd_client_export" <?php checked( 'ucd_client_export', get_option( 'ucd_client_export' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
													 	</div>
												</div>

												<?php if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) { ?>
												<div class="ucd-dynamic-item ucd-dynamic-pro">
														<div class="ucd-item-name"><?php _e( 'Gravity Forms', 'ultimate-client-dash' ); ?></div>
														<div class="ucd-item-capabilities">
																<div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_client_gravity_forms" value="ucd_client_gravity_forms" <?php checked( 'ucd_client_gravity_forms', get_option( 'ucd_client_gravity_forms' ) ); ?> /><span class="ucd-slider round"></span></label>Authorize</div>
														</div>
												</div>
												<?php } ?>

												<!-- Add foreach plugin capability items here -->
												<?php
												if ( class_exists( 'UCDProCapabilities' ) ) :
														global $ucd_capabilities;
														echo $ucd_capabilities->getSettingsMarkup();
												else : ?>

												<p style="padding-top: 10px; padding-bottom: 5px;">Below is an example of the pro feature <strong>Extend Client Capabitilies</strong>. This feature will dynamically populate all WordPress core, theme, and plugin capabilities and allow you to assign them to the user role 'Client'. <strong><a href="admin.php?page=ultimate-client-dash&tab=upgrade">Purchase Pro version to unlock this feature.</a></strong></p>

												<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
														<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
														<span class="ucd-item-name"><?php _e( 'Extend Client Capabilities', 'ultimate-client-dash' ) ?></span>
														<span class="ucd-item-capabilities"></span>
												</div>

												<img class="ucd-pro-extend-cap-screenshot" src="<?php echo plugins_url( 'assets/Ultimate-Client-Dash-Pro-Extend-Capabilities.jpeg', __FILE__ ); ?>" alt="Ultimate Client Dash" height="auto" width="" />

												<?php endif; ?>

												<!-- End foreach menu item here -->

												<tr class="ucd-float-option">
														<th class="ucd-save-section dashboard">
														<?php submit_button(); ?>
														</th>
												</tr>

											</tbody>
								</table>
						</div>
				</form>
<?php
}
