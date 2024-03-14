<?php


// Menu Items Settings

add_action( 'ucd_settings_content', 'ucd_menu_items_page' );
function ucd_menu_items_page() {
		global $ucd_active_tab;
		if ( 'menu-items' != $ucd_active_tab )
		return;
?>

	  <h3><?php _e( 'Remove Menu Items', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-menu' );
				do_settings_sections( 'ultimate-client-dash-menu' );
				?>

						<!-- Client Option Section -->

						<div class="ucd-inner-wrapper settings-menu">
						<p class="ucd-settings-desc" style="padding-bottom: 6px;">Simplify the WordPress dashboard by removing your choice of menu items. Clients will still be able to use these features, the menu items will just be removed from the dashboard menu. To restrict client capabilities, use the 'Client Access' feature. To learn more view our online <a href="https://ultimateclientdash.com/menu/" target="_blank">documentation</a>.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>

							  <!-- Add foreach menu item here -->
							  <?php
								if ( class_exists( 'UCDProMenu' ) ) :
										global $ucd_menu;
										echo $ucd_menu->getSettingsMarkup();
								else : ?>

											<div><h3><?php _e( 'Example', 'ultimate-client-dash' ); ?></h3>
											<p>Ultimate Client Dash will collect all menu items giving you the ability to remove menu items from the WordPress dashboard. <strong><a href="admin.php?page=ultimate-client-dash&tab=upgrade">Purchase Pro version to unlock this feature.</a></strong></p></div>

                      <div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
                          <span class="ucd-item-name"><?php _e( 'Dashboard', 'ultimate-client-dash' ) ?></span>
                          <span class="ucd-item-capabilities">
                              <span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
                              <span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
                          </span>
					  					</div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default submenu">
													<span class="ucd-item-name"><?php _e( 'Home', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default submenu">
													<span class="ucd-item-name"><?php _e( 'Updates', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>


                      <div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Post', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
                      </div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Pages', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'WooCommerce', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>

                      <div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Tools', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
                      </div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Plugins', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>


                      <div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Profile', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
					  					</div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Settings', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>

											<div class="ucd-dynamic-item ucd-dynamic-pro ui-state-default">
													<a class="ucd-item-toggle" href="#"></a> <!-- Display Toggle if menu item has submenu -->
													<span class="ucd-item-name"><?php _e( 'Yoast SEO', 'ultimate-client-dash' ) ?></span>
													<span class="ucd-item-capabilities">
															<span class="ucd-admin"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>All Roles</span>
															<span class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="" value="" /><span class="ucd-slider round"></span></label>Client</span>
													</span>
											</div>

						<?php endif; ?>

								<tr class="ucd-float-option">
								<th class="ucd-save-section dashboard">
								<?php submit_button(); ?>
								</th>
								</tr>

								</tbody>
								</table>
						</div>
      </form>
<?php }
