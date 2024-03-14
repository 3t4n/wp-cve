<?php

//only enqueue all the admin stuff if is_admin
if ( is_admin() ){
  add_action( 'admin_menu', 'sl_add_options_menu' );
  add_action( 'admin_init', 'sl_register_settings' );
  add_action( 'admin_init', 'sl_register_meta_boxes' );
  add_action( 'admin_init', 'sl_update_db' );
  /**
   * @todo Add an action to run database update here. Add
   * a version number so we don't run every time.
   */
}

function sl_update_db() {
	if (!get_option('sl_db_version') || get_option('sl_db_version') < 1.0) {
		// Do update
		update_option( 'sl_append_disclosure_badge', get_option('sl_add_disclosure_badge'));
		add_option( 'sl_db_version', '1.0' );
	}
}

function sl_add_options_menu() {
 	add_options_page('Skimlinks Settings', 'Skimlinks', 'manage_options', 'skimlinks-options', 'sl_options_page');
}

/**
 * Register all the settings used on the Skimlinks settings page (accessed cia admin_init hook)
 * 
 */
function sl_register_settings() {
	register_setting( 'sl-settings', 'sl_publisher_id' );
	register_setting( 'sl-settings', 'sl_enable_subdomain' );
	register_setting( 'sl-settings', 'sl_subdomain' );
	register_setting( 'sl-settings', 'sl_enable_rss_filtering' );
	register_setting( 'sl-settings', 'sl_add_disclosure_badge' );
	register_setting( 'sl-settings', 'sl_append_disclosure_badge' );
}

function sl_enqueue_admin_scripts() {
	wp_enqueue_script( 'sl_admin', SL_PLUGIN_URL . '/assets/skimlinks.js', array( 'jquery' ) );
}
add_action( 'admin_print_scripts-settings_page_skimlinks-options', 'sl_enqueue_admin_scripts' );

/**
 * Validates the Skimlinks options (via update_option() hook)
 */
function sl_validate_settings( $option_name, $old_value, $new_value ) {
	switch ( $option_name ) : 
		
		case 'sl_publisher_id' :
			
			//convert the value to uppercase for 1234x5678
			if( $new_value !== strtoupper( $new_value ) ) {
				$new_value = strtoupper( $new_value );
				update_option( $option_name, $new_value );
			}

			if( $new_value > '' && !sl_validate_publisher_id( $new_value ) ) {
				update_option( $option_name, ( sl_validate_publisher_id( $old_value ) ? $old_value : '' ) );
				sl_add_setting_validation_error( 'sl_validation', $option_name, __( 'The Publisher ID you entered was not valid.', 'skimlinks' ) );
			} 
			break;
		
		case 'sl_subdomain' :
			// Protocol vaidation
			$server_protocol = sl_get_protocol();
			$secure = $server_protocol === 'https://' ? true : false;
			$protocol_error = __( 'The protocol (http://, https://) must be the same on both this site and your custom subdomain. This option has been adjusted automatically.', 'skimlinks' );
			if ($secure && strpos($new_value, 'http://') === 0) {
				sl_add_setting_validation_error( 'sl_validation', $option_name, $protocol_error );
				update_option( $option_name, str_replace('http://', '', $new_value) );
			}
			else if (!$secure && strpos($new_value, 'https://') === 0) {
				sl_add_setting_validation_error( 'sl_validation', $option_name, $protocol_error );
				update_option( $option_name, str_replace('https://', '', $new_value) );
			}
			else if (strpos($new_value, $server_protocol) === 0) {
				update_option( $option_name, str_replace($server_protocol, '', $new_value) );
			}

			// Other validations
			$new_value = get_option($option_name);
			$subdomain_error_message = __( 'The subdomain information you entered was not valid', 'skimlinks' );
			// If they haven't added a subdomain but custom redirect is enabled
			if ( $new_value == '' && sl_is_subdomain_enabled() ) {
				sl_add_setting_validation_error( 'sl_validation', $option_name, $subdomain_error_message );
				update_option( 'sl_enable_subdomain', '' );
			}
			// If they have added a subdomain but if doesn't validate
			else if ( $new_value > '' && sl_is_subdomain_enabled() && !sl_validate_subdomain( $new_value ) ) {
				sl_add_setting_validation_error( 'sl_validation', $option_name, $subdomain_error_message );
				// Set back to the old value if available
				if ( $old_value > '' ) {
					// To prevent an infinite loop, we remove this hook and re-add it after we've
					// updated the option.
					remove_action( 'update_option_' . $option_name, 'sl_update_option_' . $option_name, 10 );
					update_option( $option_name, $old_value );
					add_action( 'update_option_' . $option_name, 'sl_update_option_' . $option_name, 10, 2 );
				}
				// Otherwise empty the field
				else {
					update_option( $option_name, '' );
				}
			}
			break;
			
	endswitch;
	
}

// use legacy hooks for update_option / add_option (because 'updated_option' was only introduced in 2.9
add_action( 'update_option_sl_publisher_id', 'sl_update_option_sl_publisher_id', 10, 2 );
function sl_update_option_sl_publisher_id( $old_value, $new_value ) {
	sl_validate_settings( 'sl_publisher_id', $old_value, $new_value );
}

add_action( 'update_option_sl_subdomain', 'sl_update_option_sl_subdomain', 10, 2 );
function sl_update_option_sl_subdomain( $old_value, $new_value ) {
	sl_validate_settings( 'sl_subdomain', $old_value, $new_value );
}

add_action( 'add_option_sl_publisher_id', 'sl_update_option_sl_publisher_id', 10, 2 );
function add_option_sl_publisher_id( $name, $new_value ) {
	sl_validate_settings( 'sl_publisher_id', null, $new_value );
}

add_action( 'add_option_sl_subdomain', 'sl_update_option_sl_subdomain', 10, 2 );
function add_option_sl_subdomain( $name, $new_value ) {
	sl_validate_settings( 'sl_subdomain', null, $new_value );
}


function sl_add_setting_validation_error( $option_name, $slug, $message ) {
	$option = array_filter( (array) get_option( $option_name ) );
	$option[$slug] = $message;
	update_option( $option_name, $option );
}
  
function sl_settings_display_errors( $setting ) {
	$option = get_option( $setting );
	
	if( !sl_is_footer_js_verified() ) {
		$option['not-verified'] = __( 'There appears to be an error in calling the Skimlinks Plugin from your theme\'s footer.php file, please ensure your footer.php file includes the standard WordPress function "wp_footer()" above the closing body tag.', 'skimlinks' );
	}
	
	if( sl_is_subdomain_enabled() && !sl_get_subdomain() ) {
		$option['no-subdomain'] = __( 'Please enter your custom subdomain or unselect "Enable Custom Subdomain"', 'skimlinks');
	}
	
	if ( is_array( $option ) ) :
		foreach ( $option as $slug => $message) :
			echo "<div id='sl_messages' class='error fade $slug'><p>$message</p></div>";
			unset( $option[$slug] );
		endforeach;
		update_option( $setting, $option );
	endif;
		
}
/**
 * Page display function for Skimlinks Settings
 *
 * Defined during sl_add_options_mena()
 */
function sl_options_page() {

	// run the footer check on every view of the skimlinks settings page
	sl_verify_footer_js();

	?>
	
	<div class="wrap">
		
		<h2><img style="width: 180px; float: left; margin: -3px 39px 0 0;" src="<?php echo SL_PLUGIN_URL ?>/assets/skimlinks-logo.png" /><?php esc_html_e( 'Skimlinks Settings', 'skimlinks'); ?></h2>
		
		<?php sl_settings_display_errors('sl_validation'); ?>

		<form method="post" action="options.php">
			<table class="form-table">
				
				<tr valign="top">
					<th scope="row"><strong><?php esc_html_e( 'Skimlinks Publisher ID', 'skimlinks'); ?></strong></th>
					<td>
						<input type="text" name="sl_publisher_id" value="<?php echo get_option('sl_publisher_id'); ?>" placeholder="000000X000000"/>
						<span class="description">
							<?php 
								printf(
									wp_kses(
										__( 'Copy your ID from your javascript code available <a href="%s" target="_blank">here</a>.', 'skimlinks' ),
										array(
											'a' => array(
												'href' => array(),
				                'target' => array('_blank'),
											)
										)
									), 
									esc_url( 'https://hub.skimlinks.com/setup/install' ) 
								); 
							?>
						</span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><strong><?php esc_html_e( 'Custom Redirect', 'skimlinks'); ?></strong></th>
					<td>
						<label for="sl_enable_subdomain">
							<input type="checkbox" name="sl_enable_subdomain" id="sl_enable_subdomain" <?php echo get_option('sl_enable_subdomain') ? ' checked="checked" ' : '' ?> />
							<?php esc_html_e( 'Make Skimlinks redirect through your own custom subdomain rather than our default domain.', 'skimlinks'); ?>
						</label>
					</td>
				</tr>
				
				<tr valign="top" id="subdomain-options" class="<?php echo !get_option('sl_enable_subdomain') ? 'hide-if-js' : '' ?>">
					<th scope="row" style="vertical-align:bottom"><strong>
						<?php esc_html_e( 'Your Custom Subdomain', 'skimlinks'); ?>
					</strong></th>
					<td>
						<span class="notice notice-warning inline" style="padding-top:.5em;padding-bottom:.5em;margin-bottom:1em;display:block;">
							<?php 
								printf(
									wp_kses(
										__( '<strong>Warning:</strong> You must setup your CNAME record before you enable this setting. Visit <a href="%s" target="_blank">Skimlinks Advanced Settings</a> page to learn how.', 'skimlinks' ),
										array(
											'strong' => array(),
											'a' => array(
												'href' => array(),
				                'target' => array('_blank'),
											)
										)
									), 
									esc_url( 'https://hub.skimlinks.com/setup/settings/no-domain' ) 
								); 
							?>
						</span>

						<?php echo sl_get_protocol(); ?> <input type="text" name="sl_subdomain" class="regular-text" value="<?php echo get_option('sl_subdomain'); ?>" placeholder="e.g. go.yourdomain.com" />

						<span class="description">
							<?php esc_html_e( 'Enter CNAME record.', 'skimlinks'); ?>
						</span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><strong>
						<?php esc_html_e( 'RSS Monetization', 'skimlinks'); ?>
					</strong></th>
					<td>
						<label for="sl_enable_rss_filtering">
							<input type="checkbox" name="sl_enable_rss_filtering" id="sl_enable_rss_filtering" <?php echo get_option('sl_enable_rss_filtering') ? ' checked="checked" ' : '' ?> />
							<?php esc_html_e( 'Enable Skimlinks on your RSS feed.', 'skimlinks'); ?>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><strong>
						<?php esc_html_e( 'Disclosure/Referral Badge', 'skimlinks'); ?>
					</strong></th>
					<td>
						<label for="sl_add_disclosure_badge">
							<input type="checkbox" name="sl_add_disclosure_badge" id="sl_add_disclosure_badge" <?php echo get_option('sl_add_disclosure_badge') ? ' checked="checked" ' : '' ?> />
							<?php esc_html_e( 'Enable the Disclosure/Referral Badge.', 'skimlinks'); ?>
						</label><br>
						<p class="description">
							<?php 
								printf(
									wp_kses(
										__( 'A Disclosure Disclosure/Referral Badge will appear in Appearance > Widgets to place wherever you want.<br>
											<strong>Note:</strong> please make sure you have accepted T&amp;Cs first to implement... <a target="_blank" href="%s">click here</a>.', 'skimlinks' ),
										array(
											'br' => array(),
											'strong' => array(),
											'a' => array(
												'href' => array(),
												'target' => array('_blank')
											)
										)
									), 
									esc_url( 'https://hub.skimlinks.com/toolbox/referral' ) 
								); 
							?>
						</p>
					</td>
				</tr>
				
				<tr valign="top" id="badge-options" class="<?php echo !get_option('sl_add_disclosure_badge') ? 'hide-if-js' : '' ?>">
					<th scope="row"><strong>
						<?php esc_html_e( 'Append Badge to Posts', 'skimlinks'); ?>
					</strong></th>
					<td>
						<label for="sl_append_disclosure_badge">
							<input type="checkbox" name="sl_append_disclosure_badge" id="sl_append_disclosure_badge" <?php echo get_option('sl_append_disclosure_badge') ? ' checked="checked" ' : '' ?> />
							<?php esc_html_e( 'Append disclosure badge to the posts.', 'skimlinks'); ?>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><strong>
						<?php esc_html_e( 'Skimlinks Status', 'skimlinks'); ?>
					</strong></th>
					<td>
						<?php if( sl_is_plugin_active() ) : ?>
							<span style="color: green">
								<?php 
									printf(
										wp_kses(
											__( 'Skimlinks plugin is working correctly. Click <a href="%s" target="_blank">here</a> to learn how to test if your links are being affiliated.', 'skimlinks' ),
											array(
												'a' => array(
													'href' => array(),
					                'target' => array('_blank'),
												)
											)
										), 
										esc_url( 'https://support.skimlinks.com/hc/en-us/articles/223835608-How-to-check-the-Skimlinks-code-is-installed-properly-' ) 
									); 
								?>
							</span>
						<?php else: ?>
							<span style="color: red">
								<?php esc_html_e( 'Skimlinks is not configured, please adjust the settings above.', 'skimlinks'); ?>
							</span>
						<?php endif; ?>
						
					</td>
				</tr>
				
			</table>
			
			<input type="hidden" name="action" value="update" />
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			
			<?php 
			settings_fields( 'sl-settings' );
			
			// Output any sections defined for page sl-settings
			do_settings_sections('sl-settings'); 
			?>
		</form>
	</div>
	<?php
}

function sl_admin_message_notification() {
	
	if( ( isset( $_GET['page'] ) && $_GET['page'] == 'skimlinks-options' ) || sl_is_plugin_active() )
		return;
	
	?>
	<div id="message" class="updated fade" style="">
		<p>
			<?php 
				printf(
					wp_kses(
						__( 'Skimlinks is not configured, please update the Skimlinks settings to enable Skimlinks <a class="button" href="%s">Update Options</a>', 'skimlinks' ),
						array(
							'a' => array(
								'href' => array(),
                'class' => array('button'),
							)
						)
					), 
					esc_url( SL_ADMIN_URL ) 
				); 
			?>
		</p>
	</div>
	<?php
	 
}
add_action( 'admin_notices', 'sl_admin_message_notification' );

/*
 * Edit Post / Edit Page meta box 
 */

/**
 * Registers the meta boxes for add post / add page
 * 
 * @return void
 */
function sl_register_meta_boxes() {
	add_meta_box( 'sl-post-meta-box', 'Skimlinks', 'sl_post_meta_box', 'post', 'side', 'default' );
	add_meta_box( 'sl-post-meta-box', 'Skimlinks', 'sl_post_meta_box', 'page', 'side', 'default' );
}

/**
 * Outputs the content for the meta boxes for add post / add page
 * 
 * @return void
 */
function sl_post_meta_box() {
	
	if( sl_is_plugin_active() ) : ?>
	<p>
		<?php esc_html_e( 'Congratulations! Skimlinks is successfully installed and is monetising the links on your blog.', 'skimlinks'); ?>
	</p>
	
	<?php else : ?>
	<p>
		<?php 
			printf(
				wp_kses(
					__( 'Skimlinks is not configured - please <a href="%s">modify settings</a>.', 'skimlinks' ),
					array(
						'a' => array(
							'href' => array()
						)
					)
				), 
				esc_url( SL_ADMIN_URL ) 
			); 
		?>
	</p>
	<?php endif;
} 
