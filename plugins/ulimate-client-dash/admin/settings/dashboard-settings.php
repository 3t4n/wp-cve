<?php


// Dashboard Settings

add_action( 'ucd_settings_content', 'ucd_dashboard_settings_page' );
function ucd_dashboard_settings_page() {
		global $ucd_active_tab;
		if ( 'dashboard-settings' != $ucd_active_tab )
		return;
?>

	  <h3><?php _e( 'Dashboard Branding', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-settings' );
				do_settings_sections( 'ultimate-client-dash-settings' );
				$ucd_admin_footer_text = get_option( 'ucd_admin_footer_text' );
				?>

						<!-- Dashboard Styling Option Section -->

						<div class="ucd-inner-wrapper settings-dashboard">
						<p class="ucd-settings-desc">Dashboard branding gives you the ability to customize, white label, and rebrand the WordPress dashboard to create a personalized experience.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>

								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Theme', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th><?php _e( 'Modern Dashboard', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_modern_theme" value="disable_modern_theme" <?php checked( 'disable_modern_theme', get_option( 'ucd_dashboard_modern_theme' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
											<p>
											Give the WordPress dashboard a modern look and feel.
											</p>
											</td>
											</tr>


								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Admin Bar', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th><?php _e( 'Text Color', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_dashboard_admin_bar_text_color" value="<?php echo esc_attr( get_option('ucd_dashboard_admin_bar_text_color') ); ?>" size="50" />
											<p>
											Select a text color for the admin bar and active menu items.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Background Color', 'ultimate-client-dash' ); ?><p>Commonly used as an accent color.</p></th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_dashboard_accent" value="<?php echo esc_attr( get_option('ucd_dashboard_accent') ); ?>" size="50" />
											<p>
											Select a background color for the admin bar and active menu items.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Admin Bar Menu', 'ultimate-client-dash' ); ?>
											<p>Hide default links from admin bar menu for user role 'Client'.</p></th>
											<td>
											<div class="ucd-inline-option ucd-admin-bar-menu">
											<label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_admin_bar_updates_link" value="#wp-admin-bar-updates" <?php checked( '#wp-admin-bar-updates', get_option( 'ucd_dashboard_admin_bar_updates_link' ) ); ?> /><span class="ucd-slider round"></span></label><?php _e( 'Hide', 'ultimate-client-dash' ); ?>
											<p><?php _e( 'Updates', 'ultimate-client-dash' ); ?></p>
											</div>
											<div class="ucd-inline-option ucd-admin-bar-menu">
											<label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_admin_bar_comments_link" value="#wp-admin-bar-comments" <?php checked( '#wp-admin-bar-comments', get_option( 'ucd_dashboard_admin_bar_comments_link' ) ); ?> /><span class="ucd-slider round"></span></label><?php _e( 'Hide', 'ultimate-client-dash' ); ?>
											<p><?php _e( 'Comments', 'ultimate-client-dash' ); ?></p>
											</div>
											<div class="ucd-inline-option ucd-admin-bar-menu">
											<label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_admin_bar_add_new_Menu" value="#wp-admin-bar-new-content" <?php checked( '#wp-admin-bar-new-content', get_option( 'ucd_dashboard_admin_bar_add_new_Menu' ) ); ?> /><span class="ucd-slider round"></span></label><?php _e( 'Hide', 'ultimate-client-dash' ); ?>
											<p><?php _e( 'New Content', 'ultimate-client-dash' ); ?></p>
											</div>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Screen Options', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_admin_bar_screen_options" value="ucd_dashboard_admin_bar_screen_options" <?php checked( 'ucd_dashboard_admin_bar_screen_options', get_option( 'ucd_dashboard_admin_bar_screen_options' ) ); ?> /><span class="ucd-slider round"></span></label>Hide
											<p>
											Hide screen options tab from the admin bar for user role 'Client'.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Help Box', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_admin_bar_help" value="ucd_dashboard_admin_bar_help" <?php checked( 'ucd_dashboard_admin_bar_help', get_option( 'ucd_dashboard_admin_bar_help' ) ); ?> /><span class="ucd-slider round"></span></label>Hide
											<p>
											Hide help box tab from the admin bar for user role 'Client'.
											</p>
											</td>
											</tr>


								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Admin Menu', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr class="ucd-pro-version">
											<th><?php _e( 'Dashboard Logo', 'ultimate-client-dash' ); ?></th>
											<td>
											<div>
											<input type="text" name="ucd_admin_menu_logo" id="admin_logo_image_url" value="<?php echo esc_attr( get_option('ucd_admin_menu_logo') ); ?>" class="regular-text">
											<input type="button" name="upload-btn-admin-logo" id="upload-btn-admin-logo" class="button-secondary" value="Upload Image">
											</div>
											<p>
											Logo will appear at the top of the admin menu. Max width: 140px.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Main Menu', 'ultimate-client-dash' ); ?></th>
											<td>
											<div class="ucd-inline-option">
											<input type="text" placeholder="" class="color-field" name="ucd_dashboard_text_color" value="<?php echo esc_attr( get_option('ucd_dashboard_text_color') ); ?>" size="50" />
											<p><?php _e( 'Text', 'ultimate-client-dash' ); ?></p>
											</div>
											<div class="ucd-inline-option">
											<input type="text" placeholder="" class="color-field" name="ucd_dashboard_background_dark" value="<?php echo esc_attr( get_option('ucd_dashboard_background_dark') ); ?>" size="50" />
											<p><?php _e( 'Background', 'ultimate-client-dash' ); ?></p>
											</div>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Submenu', 'ultimate-client-dash' ); ?></th>
											<td>
											<div class="ucd-inline-option ucd-admin-bar-menu">
											<input type="text" placeholder="" class="color-field" name="ucd_dashboard_submenu_text_color" value="<?php echo esc_attr( get_option('ucd_dashboard_submenu_text_color') ); ?>" size="50" />
											<p><?php _e( 'Submenu Text', 'ultimate-client-dash' ); ?></p>
											</div>
											<div class="ucd-inline-option ucd-admin-bar-menu">
											<input type="text" placeholder="" class="color-field" name="ucd_dashboard_background_light" value="<?php echo esc_attr( get_option('ucd_dashboard_background_light') ); ?>" size="50" />
											<p><?php _e( 'Submenu Background', 'ultimate-client-dash' ); ?></p>
											</div>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Menu Item Seperator', 'ultimate-client-dash' ); ?>
											<p>Only applicable if modern dashboard is enabled.</p></th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_dashboard_border_color" value="<?php echo esc_attr( get_option('ucd_dashboard_border_color') ?: 'rgba(255, 255, 255, 0.075)' ); ?>" size="50" />
											<p>
											Select a color for the horizontal separators between menu items.
											</p>
											</td>
											</tr>


								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Extra Settings', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th><?php _e( 'Link & Button Colors', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_dashboard_link_text_color" value="<?php echo esc_attr( get_option('ucd_dashboard_link_text_color') ); ?>" size="50" />
											<p>
											Change the link and button colors throughout the WordPress dashboard.
											</p>
											</td>
											</tr>

											<tr>
											<th>Replace Howdy Text</th>
											<td><input class="regular-text" type="text" placeholder="Hello" name="ucd_howdy_text" value="<?php echo esc_attr( get_option('ucd_howdy_text') ); ?>" size="30" />
											<p>
											Customize the "Howdy" text for logged in users.
											</p>
											</td>
											</tr>

											<tr class="ucd-pro-version">
											<th><?php _e( 'White Label WordPress', 'ultimate-client-dash' ); ?></th>
											<td>
											<p><label class="ucd-switch"><input type="checkbox" name="ucd_dashboard_hide_wp_logo" value="#wpadminbar #wp-admin-bar-wp-logo>.ab-item"<?php checked( '#wpadminbar #wp-admin-bar-wp-logo>.ab-item', get_option( 'ucd_dashboard_hide_wp_logo' ) ); ?> /><span class="ucd-slider round"></span></label>Enable</p>
											<p>
											Remove the WordPress reference in the admin bar and dashboard footer.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Footer WP Version', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_admin_footer_version" value="#footer-upgrade" <?php checked( '#footer-upgrade', get_option( 'ucd_admin_footer_version' ) ); ?> /><span class="ucd-slider round"></span></label>Hide
											<p>
											Hide the current WordPress version from dashboard footer.
											</p>
											</td>
											</tr>

											<tr>
											<th>
											<?php _e( 'Custom Footer Text', 'ultimate-client-dash' ); ?>
											<p>
											Enter the text you would like to display in the dashboard footer by replacing the default text. HTML markup can be used.
											</p>
											</th>

											<td class="ucd-custom-content">
											<?php
											wp_editor( $ucd_admin_footer_text , 'ucd_admin_footer_text', array(
											'wpautop'       => false,
											'media_buttons' => true,
											'textarea_name' => 'ucd_admin_footer_text',
											'editor_class'  => 'ucd-custom-footer',
											'textarea_rows' => 5
											) );
											?>
											</td>
											</tr>


								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Advanced Settings', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr class="ucd-pro-version">
											<th><?php _e( 'Dashboard Custom CSS - All Roles', 'ultimate-client-dash' ); ?>
											<p>Enter your dashboard custom CSS here that applies to all user roles.</p>
											</th>
											<td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_pro_dashboard_custom_css" value="<?php echo esc_attr( get_option('ucd_pro_dashboard_custom_css') ); ?>" ><?php echo esc_attr( get_option('ucd_pro_dashboard_custom_css') ); ?></textarea>
											<p>In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important;</p>
											</td>
											</tr>

											<tr class="ucd-pro-version">
											<th><?php _e( 'Dashboard Custom CSS - Clients Only', 'ultimate-client-dash' ); ?>
											<p>Enter your dashboard custom CSS here that only applies to the 'Client' user role.</p>
											</th>
											<td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_pro_dashboard_client_custom_css" value="<?php echo esc_attr( get_option('ucd_pro_dashboard_client_custom_css') ); ?>" ><?php echo esc_attr( get_option('ucd_pro_dashboard_client_custom_css') ); ?></textarea>
											<p>In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important;</p>
											</td>
											</tr>


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
