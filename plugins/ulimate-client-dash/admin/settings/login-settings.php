<?php


// Login Page Content

add_action( 'ucd_settings_content', 'ucd_login_settings_page' );
function ucd_login_settings_page() {
global $ucd_active_tab;
		if ( 'login-settings' != $ucd_active_tab )
		return;
?>

	  <h3><?php _e( 'Login Page Branding', 'ultimate-client-dash' ); ?></h3>

    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-login' );
				do_settings_sections( 'ultimate-client-dash-login' );
				$ucd_login_custom_content = get_option( 'ucd_login_custom_content' );
				$ucd_widget_body = get_option( 'ucd_widget_body' );
				?>

						<!-- Dashboard Styling Option Section -->

						<div class="ucd-inner-wrapper settings-login">
						<p class="ucd-settings-desc">Login page branding allows you to personalize the WordPress login page by adding your own logo, colors, background image, and text.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>


								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( '', 'ultimate-client-dash' ); ?>Logo</h2></th>
								</tr>

											<!-- Login Styling Option Section -->
											<tr>
											<th><?php _e( 'Login Logo', 'ultimate-client-dash' ); ?></th>
											<td>
											<div>
											<input type="text" name="ucd_login_logo" id="image_url" value="<?php echo esc_attr( get_option('ucd_login_logo') ); ?>" class="regular-text">
											<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
											</div>
											<p>
											Replace the default WordPress logo. Max width: 320px.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Logo Size', 'ultimate-client-dash' ); ?>
											<p>Max width: 320px</p></th>
											<td>
											<div class="ucd-inline-option">
											<input class="input-short" type="text" placeholder="320px" name="ucd_logo_width" value="<?php echo esc_attr( get_option('ucd_logo_width') ); ?>" size="8" />
											<p>Width (px)</p>
											</div>
											<div class="ucd-inline-option">
											<input class="input-short" type="text" name="ucd_logo_height" value="<?php echo esc_attr( get_option('ucd_logo_height') ); ?>" size="8" />
											<p>Height (px)</p>
											</div>
											<div class="ucd-inline-option">
											<input class="input-short" type="text" name="ucd_logo_padding_bottom" value="<?php echo esc_attr( get_option('ucd_logo_padding_bottom') ?: '5px' ); ?>" size="8" />
											<p>Margin Bottom</p>
											</div>
											</td>
											</tr>

								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Colors & Background', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th><?php _e( 'Text & Link Color', 'ultimate-client-dash' ); ?>
											</th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_login_body_text_color" value="<?php echo esc_attr( get_option('ucd_login_body_text_color') ); ?>" size="50" />
											<p>Select a text color for the login page. Note: This does not affect the login form.</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Background Color', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="" class="color-field" name="ucd_login_background_color" value="<?php echo esc_attr( get_option('ucd_login_background_color') ); ?>" size="50" />
											<p>Select a background color for the login page.</p>
											</td>
											</tr>

											<tr class="ucd-pro-version">
											<th><?php _e( 'Background Image', 'ultimate-client-dash' ); ?></th>
											<td>
											<div>
											<input type="text" name="ucd_login_background_image" id="image_url_two" value="<?php echo esc_attr( get_option('ucd_login_background_image') ); ?>" class="regular-text">
											<input type="button" name="upload-btn-two" id="upload-btn-two" class="button-secondary" value="Upload Image">
											</div>
											<p>Add a background image to the login page.</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Background Overlay', 'ultimate-client-dash' ); ?>
											<p>Add a colored overlay to the background image.</p>
											</th>
											<td>
											<div class="ucd-inline-option">
											<input type="text" placeholder="" class="color-field" name="ucd_login_overlay_color" value="<?php echo esc_attr( get_option('ucd_login_overlay_color') ); ?>" size="50" />
											<p>
											Overlay Color
											</p>
											</div>
											<div class="ucd-inline-option">
											<input class="input-short" type="text" placeholder="" name="ucd_login_overlay_opacity" value="<?php echo esc_attr( get_option('ucd_login_overlay_opacity') ?: '0.5' ); ?>" size="8" />
											<p>
											Overylay Opacity (default 0.5)
											</p>
											</div>
											</td>
											</tr>

								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Extra Settings', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th><?php _e( 'Lost Password Link', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_login_hide_password_link" value="hide_password_link" <?php checked( 'hide_password_link', get_option( 'ucd_login_hide_password_link' ) ); ?> /><span class="ucd-slider round"></span></label>Hide
											<p>
											Hide the lost password link from the login page.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Back To Link', 'ultimate-client-dash' ); ?></th>
											<td><label class="ucd-switch"><input type="checkbox" name="ucd_login_hide_main_site_link" value="hide_main_site_link" <?php checked( 'hide_main_site_link', get_option( 'ucd_login_hide_main_site_link' ) ); ?> /><span class="ucd-slider round"></span></label>Hide
											<p>
											Hide the back to link from the login page.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Replace Logo URL', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="https://" name="ucd_login_logo_url" value="<?php echo esc_attr( get_option('ucd_login_logo_url') ); ?>" size="70" />
											<p>
											When login logo is clicked, the user will be redirected to this url. Default home url.
											</p>
											</td>
											</tr>

											<tr>
											<th><?php _e( 'Replace Logo Alt Text', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="" name="ucd_login_logo_title" value="<?php echo esc_attr( get_option('ucd_login_logo_title') ); ?>" size="70" />
											<p>
											Alt text shown when mouse is hovered over the login logo.
											</p>
											</td>
											</tr>

								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Login Footer', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

											<tr>
											<th>
											<?php _e( 'Custom Footer Text', 'ultimate-client-dash' ); ?>
											<p>Add custom text to the login page footer.<br>e.g. Built by company name</p>
											</th>

											<td class="ucd-custom-content">
											<?php
											wp_editor( $ucd_login_custom_content , 'ucd_login_custom_content', array(
											'wpautop'       => false,
											'media_buttons' => true,
											'textarea_name' => 'ucd_login_custom_content',
											'editor_class'  => 'my_custom_class',
											'textarea_rows' => 5
											) );
											?>
											<p>Tip: You can adjust the alignment of the footer text by using the align tool on the toolbar.</p>
											</td>
											</tr>

									<tr class="ucd-title-holder">
									<th><h2 class="ucd-inner-title"><?php _e( 'Advanced Settings', 'ultimate-client-dash' ); ?></h2></th>
									</tr>


												<tr class="ucd-pro-version">
												<th><?php _e( 'Login Page Custom CSS', 'ultimate-client-dash' ); ?>
												<p>Enter your login page custom CSS here.</p>
												</th>
												<td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_pro_login_custom_css" value="<?php echo esc_attr( get_option('ucd_pro_login_custom_css') ); ?>" ><?php echo esc_attr( get_option('ucd_pro_login_custom_css') ); ?></textarea>
												<p>In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important;</p>
												</td>
												</tr>


								<tr class="ucd-float-option">
								<th class="ucd-save-section">
								<?php submit_button(); ?>
								<a class="ucd-preview-button" href="<?php echo site_url(); ?>/?ucd-preview-login-page" target="_blank">Preview Login Page</a>
								</th>
								</tr>

								</tbody>
								</table>
						</div>
      </form>
<?php }
