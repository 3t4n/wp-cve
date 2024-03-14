<?php


// Landing Page Settings

add_action( 'ucd_settings_content', 'ucd_landing_page' );
function ucd_landing_page() {
		global $ucd_active_tab;
		if ( 'landing-page' != $ucd_active_tab )
		return;
?>

  	<h3><?php _e( 'Landing Page', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-under_construction' );
				do_settings_sections( 'ultimate-client-dash-under_construction' );
				$ucd_under_construction_body = get_option( 'ucd_under_construction_body' );
				?>

						<!-- Under Construction Mode Option Section -->

						<div class="ucd-inner-wrapper settings-landing-page">
						<p class="ucd-settings-desc">The landing page can be used as a coming soon or under construction page while working on the website. The landing page is only visible to logged out users.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

						<table class="form-table">
						<tbody>

						<!-- Under Construction Mode Option Section -->

						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Status', 'ultimate-client-dash' ) ?></h2></th>
						</tr>

                  <tr>
                  <th><?php _e( 'Landing Page Mode', 'ultimate-client-dash' ) ?><p>Only visible to logged out users.</p></th>
                  <td><label class="ucd-switch"><input type="checkbox" name="ucd_under_construction_disable" value=".ucd-under-construction-wrapper" <?php checked( '.ucd-under-construction-wrapper', get_option( 'ucd_under_construction_disable' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                  <p>
                  Enable to activate landing page mode.
                  </p>
                  </td>
                  </tr>

						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Content', 'ultimate-client-dash' ) ?></h2></th>
						</tr>

									<tr>
									<th><?php _e( 'Logo', 'ultimate-client-dash' ) ?></th>
									<td>
									<div>
									<label for="image_url_three"></label>
									<input type="text" name="ucd_under_construction_login_logo" id="image_url_three" value="<?php echo esc_attr( get_option('ucd_under_construction_login_logo') ); ?>" class="regular-text">
									<input type="button" name="upload-btn-three" id="upload-btn-three" class="button-secondary" value="Upload Image">
									</div>
									<p>
									Add a logo to the landing page.
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Logo Width', 'ultimate-client-dash' ) ?></th>
									<td><input class="input-short" type="text" placeholder="320px" name="ucd_under_construction_login_logo_width" value="<?php echo esc_attr( get_option('ucd_under_construction_login_logo_width') ); ?>" size="8" />
									<p>
									Set the width of the logo. (default auto)
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Logo Padding Bottom', 'ultimate-client-dash' ) ?></th>
									<td><input class="input-short" type="text" placeholder="" name="ucd_construction_logo_padding_bottom" value="<?php echo esc_attr( get_option('ucd_construction_logo_padding_bottom') ?: '10px' ); ?>" size="8" />
									<p>
									Spacing between the logo and title. (default 10px)
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Title', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="" name="ucd_under_construction_title" value="<?php echo esc_attr( get_option('ucd_under_construction_title') ); ?>" size="70" />
									<p>
									Title for the landing page.
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Title Padding Bottom', 'ultimate-client-dash' ) ?></th>
									<td><input class="input-short" type="text" placeholder="" name="ucd_under_construction_title_padding_bottom" value="<?php echo esc_attr( get_option('ucd_under_construction_title_padding_bottom') ?: '20px' ); ?>" size="8" />
									<p>
									Spacing between the title and content. (default 20px)
									</p>
									</td>
									</tr>

									<tr>
									<th>
									<?php _e( 'Content', 'ultimate-client-dash' ) ?>
									<p>
									Customize the landing page by using the content editor to add your own text. HTML markup can be used.
									</p>
									</th>

									<td class="ucd-custom-content">
									<?php
									wp_editor( $ucd_under_construction_body , 'ucd_under_construction_body', array(
									'wpautop'       => false,
									'media_buttons' => true,
									'textarea_name' => 'ucd_under_construction_body',
									'editor_class'  => 'my_custom_class',
									'textarea_rows' => 12
									) );
									?>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Button Text', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="Contact Us" name="ucd_under_construction_button_text" value="<?php echo esc_attr( get_option('ucd_under_construction_button_text') ); ?>" size="70" />
									<p>
									To hide the button, leave this field empty. (default hidden)
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Button Link', 'ultimate-client-dash' ) ?><p>Opens in a new tab.</p></th>
									<td>
									<input type="text" placeholder="https://wpcodeus.com/" name="ucd_under_construction_button_link" value="<?php echo esc_attr( get_option('ucd_under_construction_button_link') ); ?>" size="70" />
									<p>
									You can use <b>mailto:name@email.com</b> to have your button open up visitors preferred mail.
									</p>
									</td>
									</tr>

						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Styling', 'ultimate-client-dash' ) ?></h2></th>
						</tr>

									<tr>
									<th><?php _e( 'Text', 'ultimate-client-dash' ) ?></th>
									<td>
									<div class="ucd-inline-option">
									<input type="text" placeholder="" class="color-field" name="ucd_under_construction_text_color" value="<?php echo esc_attr( get_option('ucd_under_construction_text_color') ); ?>" size="50" />
									<p>
									Text color
									</p>
									</div>
									<div class="ucd-inline-option">
									<select name="ucd_under_construction_font_family">
									<option value="Lato" <?php selected(get_option('ucd_under_construction_font_family'), "Lato"); ?>>Lato</option>
									<option value="Montserrat" <?php selected(get_option('ucd_under_construction_font_family'), "Montserrat"); ?>>Montserrat</option>
									<option value="Muli" <?php selected(get_option('ucd_under_construction_font_family'), "Muli"); ?>>Muli</option>
									<option value="Open Sans" <?php selected(get_option('ucd_under_construction_font_family'), "Open Sans"); ?>>Open Sans</option>
									<option value="Oswald" <?php selected(get_option('ucd_under_construction_font_family'), "Oswald"); ?>>Oswald</option>
									<option value="Poppins" <?php selected(get_option('ucd_under_construction_font_family'), "Poppins"); ?>>Poppins</option>
									<option value="Raleway" <?php selected(get_option('ucd_under_construction_font_family'), "Raleway"); ?>>Raleway</option>
									<option value="Roboto" <?php selected(get_option('ucd_under_construction_font_family'), "Roboto"); ?>>Roboto</option>
									<option value="Ubuntu" <?php selected(get_option('ucd_under_construction_font_family'), "Ubuntu"); ?>>Ubuntu</option>
									<option value="Source Sans Pro" <?php selected(get_option('ucd_under_construction_font_family'), "Source Sans Pro"); ?>>Source Sans Pro</option>
									</select>
									<p>
									Font family
									</p>
									</div>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Button', 'ultimate-client-dash' ) ?></th>
									<td>
									<div class="ucd-inline-option">
								  <input type="text" placeholder="" class="color-field" name="ucd_under_construction_button_text_color" value="<?php echo esc_attr( get_option('ucd_under_construction_button_text_color') ); ?>" size="50" />
									<p>
									Text color
									</p>
									</div>
									<div class="ucd-inline-option">
									<input type="text" placeholder="" class="color-field" name="ucd_under_construction_button_color" value="<?php echo esc_attr( get_option('ucd_under_construction_button_color') ); ?>" size="50" />
									<p>
									Background color
									</p>
									</div>
			            <div class="ucd-inline-option">
									<input class="input-short" type="text" placeholder="" name="ucd_under_construction_button_radius" value="<?php echo esc_attr( get_option('ucd_under_construction_button_radius') ?: '3px' ); ?>" size="8" />
									<p>
									Border radius (default 3px)
									</p>
									</div>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Background Color', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="" class="color-field" name="ucd_under_construction_background_color" value="<?php echo esc_attr( get_option('ucd_under_construction_background_color') ); ?>" size="50" />
									<p>
									Choose a background color for the landing page.
									</p>
									</td>
									</tr>

									<tr class="ucd-pro-version">
									<th><?php _e( 'Background Image', 'ultimate-client-dash' ) ?></th>
									<td>
									<div>
									<label for="image_url_four"></label>
									<input type="text" name="ucd_under_construction_background_image" id="image_url_four" value="<?php echo esc_attr( get_option('ucd_under_construction_background_image') ); ?>" class="regular-text">
									<input type="button" name="upload-btn-four" id="upload-btn-four" class="button-secondary" value="Upload Image">
									</div>
									<p>
									Add a background image to the landing page.
									</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Background Overlay', 'ultimate-client-dash' ) ?>
									<p>Add a colored overlay over the background image.</p>
								  </th>
									<td>
									<div class="ucd-inline-option">
									<input type="text" placeholder="" class="color-field" name="ucd_under_construction_overlay_color" value="<?php echo esc_attr( get_option('ucd_under_construction_overlay_color') ); ?>" size="50" />
									<p>
									Overlay Color
									</p>
									</div>
									<div class="ucd-inline-option">
									<input class="input-short" type="text" placeholder="" name="ucd_under_construction_overlay_opacity" value="<?php echo esc_attr( get_option('ucd_under_construction_overlay_opacity') ?: '0.5' ); ?>" size="8" />
									<p>
									Overlay Opacity (default 0.5)
									</p>
									</div>
									</td>
									</tr>


						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Social Settings', 'ultimate-client-dash' ) ?></h2></th>
						</tr>

                  <tr>
                  <th><?php _e( 'Social Title', 'ultimate-client-dash' ) ?><p>Default: Connect With Us.</p></th>
                  <td><input type="text" placeholder="Connect With Us" name="ucd_under_construction_social_title" value="<?php echo esc_attr( get_option('ucd_under_construction_social_title') ); ?>" size="70" />
                  <p>Change the social title text.</p>
                  </td>
                  </tr>

                  <tr>
                  <th><?php _e( 'Title Padding Top', 'ultimate-client-dash' ) ?></th>
                  <td><input class="input-short" type="text" placeholder="" name="ucd_under_construction_social_padding" value="<?php echo esc_attr( get_option('ucd_under_construction_social_padding') ?: '60px' ); ?>" size="8" />
                  <p>Spacing between the content and social links on the landing page. (default 60px)</p>
                  </td>
                  </tr>

									<tr>
									<th><?php _e( 'Facebook', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="Facebook business or personal page URL" name="ucd_under_construction_facebook" value="<?php echo esc_attr( get_option('ucd_under_construction_facebook') ); ?>" size="70" />
									<p>Link to your personal or business Facebook page.</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Instagram', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="Instagram Profile URL" name="ucd_under_construction_instagram" value="<?php echo esc_attr( get_option('ucd_under_construction_instagram') ); ?>" size="70" />
									<p>Link to your personal or business Instagram account.</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'Twitter', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="Twitter Profile URL" name="ucd_under_construction_twitter" value="<?php echo esc_attr( get_option('ucd_under_construction_twitter') ); ?>" size="70" />
									<p>Link to your personal or business Twitter account.</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'LinkedIn', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="LinkedIn business or personal page URL" name="ucd_under_construction_linkedin" value="<?php echo esc_attr( get_option('ucd_under_construction_linkedin') ); ?>" size="70" />
									<p>Link to your personal or business LinkedIn account.</p>
									</td>
									</tr>

									<tr>
									<th><?php _e( 'YouTube', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" placeholder="Youtube business or personal page URL" name="ucd_under_construction_youtube" value="<?php echo esc_attr( get_option('ucd_under_construction_youtube') ); ?>" size="70" />
									<p>Link to your personal or business YouTube account.</p>
									</td>
									</tr>

						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Tracking & SEO', 'ultimate-client-dash' ) ?></h2></th>
						</tr>

									<tr style="padding-bottom: 25px;">
									<th><?php _e( 'Google Analytics ID', 'ultimate-client-dash' ) ?></th>
									<td><a style="position: relative!important; top: 5px;" href="/wp-admin/admin.php?page=ultimate-client-dash&tab=tracking-and-custom-code">Manage Tracking Code</a>
									</td>
									</tr>

									<tr class="ucd-pro-version">
									<th><?php _e( 'Meta Title', 'ultimate-client-dash' ) ?></th>
									<td><input type="text" name="ucd_under_construction_meta_title" value="<?php echo esc_attr( get_option('ucd_under_construction_meta_title') ); ?>" size="70" />
									<p>Customize the meta title for the landing page. This is the title that displays on seach engine results.</p>
									</td>
									</tr>

									<tr class="ucd-pro-version">
									<th><?php _e( 'Meta Description', 'ultimate-client-dash' ) ?></th>
									<td><textarea rows="3" cols="90" type="textarea" placeholder="" name="ucd_under_construction_meta_description" value="<?php echo esc_attr( get_option('ucd_under_construction_meta_description') ); ?>" ><?php echo esc_attr( get_option('ucd_under_construction_meta_description') ); ?></textarea>
									<p>Customize the meta description for the landing page. This is the description that displays on seach engine results.</p>
									</td>
									</tr>

						<tr class="ucd-title-holder">
						<th><h2 class="ucd-inner-title"><?php _e( 'Advanced Settings', 'ultimate-client-dash' ) ?></h2></th>
						</tr>


									<tr class="ucd-pro-version">
									<th><?php _e( 'Landing Page Custom CSS', 'ultimate-client-dash' ) ?>
									<p>Enter your landing page custom CSS here.</p>
									</th>
									<td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_pro_landing_page_custom_css" value="<?php echo esc_attr( get_option('ucd_pro_landing_page_custom_css') ); ?>" ><?php echo esc_attr( get_option('ucd_pro_landing_page_custom_css') ); ?></textarea>
									<p>In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important;</p>
									</td>
									</tr>


						<tr class="ucd-float-option">
						<th class="ucd-save-section">
						<?php submit_button(); ?>
						<a class="ucd-preview-button" href="<?php echo site_url(); ?>/?ucd-preview-landing-page" target="_blank">Preview Landing Page</a>
						</th>
						</tr>

						</tbody>
						</table>
						</div>
      </form>
<?php }
