<?php


// Welcome Message Page Content

add_action( 'ucd_settings_content', 'ucd_welcome_message_page' );
function ucd_welcome_message_page() {
		global $ucd_active_tab;
		if ( 'welcome-message' != $ucd_active_tab )
		return;
?>

  	<h3><?php _e( 'Welcome Message', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">
				<?php
				settings_fields( 'ultimate-client-dash-message' );
				do_settings_sections( 'ultimate-client-dash-message' );
				$ucd_message_body = get_option( 'ucd_message_body' );
				$ucd_widget_body = get_option( 'ucd_widget_body' );
				?>

						<!-- Dashboard Styling Option Section -->

						<div class="ucd-inner-wrapper settings-welcome">
						<p class="ucd-settings-desc">Create your own welcome message panel to display on the WordPress dashboard. It's a great way to say thank you or ask a client for a testimonial.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>

								<!-- Welcome Message Option Section -->
								<tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Status', 'ultimate-client-dash' ); ?></h2></th>
								</tr>

                      <tr>
                      <th>Welcome Panel<p>Replaces the default WordPress welcome panel.</p></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_message_disable" value=".ucd-client-welcome" <?php checked( '.ucd-client-welcome', get_option( 'ucd_message_disable' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>
                      Enable to display your welcome message on the WordPress dashboard.
                      </p>
                      </td>
                      </tr>

											<tr class="ucd-title-holder">
											<th><h2 class="ucd-inner-title"><?php _e( 'Content', 'ultimate-client-dash' ); ?></h2></th>
											</tr>

											<tr>
											<th><?php _e( 'Title', 'ultimate-client-dash' ); ?></th>
											<td><input type="text" placeholder="" name="ucd_message_title" value="<?php echo esc_attr( get_option('ucd_message_title') ?: 'Welcome to your new website.' ); ?>" size="70" /></td>
											</tr>

											<tr>
											<th>
											Content
											<p>Customize your welcome message. You can upload images and links using the content editor. HTML markup can be used.</p>
											</th>

											<td class="ucd-custom-content">
											<?php
											wp_editor( $ucd_message_body , 'ucd_message_body', array(
											'wpautop'       => false,
											'media_buttons' => true,
											'textarea_name' => 'ucd_message_body',
											'editor_class'  => 'my_custom_class',
											'textarea_rows' => 15
											) );
											?>
											<p class="ucd-content-tip">Tip: If you would like to have an image be full width of the body. Click the "Text" tab on the top right corner and change the image width to 100% and the height to auto.</p>
											</td>
											</tr>

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
