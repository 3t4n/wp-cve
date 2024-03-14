<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Plugin enqueue admin style and script 
add_action('admin_enqueue_scripts', 'wcl_admin_enqueue_scripts');
function wcl_admin_enqueue_scripts( $hook ) {
	global $wcl_options_page;
	if ( $hook == $wcl_options_page ) {
	}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wcl-admin', plugins_url('../assets/css/wcl-admin.css',__FILE__));
		wp_enqueue_script( 'wcl-admin', plugins_url('../assets/js/jquery.wcl-admin.js',__FILE__), array('jquery', 'wp-color-picker'), '1.0', true );
}

// add admin menu 
add_action('admin_menu', 'wspb_menu');
function wspb_menu() {
	global $wcl_options_page;
	$wcl_options_page = add_submenu_page('options-general.php','WP Cookie Law Info Settings', 'WP Cookie Law Info', 'manage_options', 'wp-cookie-law-settings', 'wcl_setting_page_callback' );
}

// Add Setting 
add_action('admin_init', 'register_wcl_option_settings' );
function register_wcl_option_settings() {
	register_setting( 'wcl_save_settings', 'wcl_settings','wcl_sanitize_options_validate' );	
}

// Display Setting page. 
function wcl_setting_page_callback(){
	ob_start();
	?>
	<div class="wrap">
		<div id="wcl-wrapper">
			<div class="wcl-header">
				<h3> <?php esc_html_e( 'Cookie Law Settings', WCL_TEXTDOMAIN ); ?></h3>			
			</div>
			<div id="wcl-body-wrap" class="wcl-body columns-2">
				<!-- main content -->
	    		<div id="post-body-content">
					<div class="post-body-content-inner">
						<form method="post" action="options.php">
						<?php settings_fields( 'wcl_save_settings' ); ?>
						<?php $wcl_options = get_option( 'wcl_settings' ); ?>
						<div class="postbox">
	    					<div class="inside">
								<h3>
									<div class="dashicons dashicons-admin-settings space"></div>
									<span><?php esc_html_e( 'Cookie Popup Position Setting', WCL_TEXTDOMAIN ); ?></span>
								</h3>
								<table class="form-table"> 
									<tbody>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Show Cookie Popup ', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<label><input type="checkbox" name="wcl_settings[_enable]" value="1" <?php checked( $wcl_options['_enable'], '1' ); ?>> Enable</label>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Position', WCL_TEXTDOMAIN ); ?></th>
											<td>	
												<ul>
													<li><label><input type="radio" name="wcl_settings[_position]" value="bottom" <?php checked( 'bottom', $wcl_options['_position'] ); ?>><?php _e('Banner bottom', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_position]" value="top" <?php checked( 'top', $wcl_options['_position'] ); ?>><?php _e('Banner top', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_position]" value="bottom-left" <?php checked( 'bottom-left', $wcl_options['_position'] ); ?>><?php _e('Floating Left', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_position]" value="bottom-right" <?php checked( 'bottom-right', $wcl_options['_position'] ); ?>><?php _e('Floating Right', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_position]" value="top-pushdown" <?php checked( 'top-pushdown', $wcl_options['_position'] ); ?>><?php _e('Banner top (pushdown)', WCL_TEXTDOMAIN); ?></label></li>
												</ul>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Layout', WCL_TEXTDOMAIN ); ?></th>
											<td>	
												<ul>
													<li><label><input type="radio" name="wcl_settings[_theme]" value="block" <?php checked( 'block', $wcl_options['_theme'] ); ?>><?php _e('Block', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_theme]" value="classic" <?php checked( 'classic', $wcl_options['_theme'] ); ?>><?php _e('Classic', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_theme]" value="edgeless" <?php checked( 'edgeless', $wcl_options['_theme'] ); ?>><?php _e('Edgeless', WCL_TEXTDOMAIN); ?></label></li>
													<li><label><input type="radio" name="wcl_settings[_theme]" value="wire" <?php checked( 'wire', $wcl_options['_theme'] ); ?>><?php _e('Wire', WCL_TEXTDOMAIN); ?></label></li>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
								<h3>
									<div class="dashicons dashicons-admin-appearance space"></div>
									<?php esc_html_e( 'Cookie Popup Design Setting', WCL_TEXTDOMAIN ); ?>
								</h3>
								<table class="form-table">
									<tbody>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Popup Background Color', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" class="wcl_color_field" name="wcl_settings[_popup_bgcolor]" value="<?php echo esc_html($wcl_options['_popup_bgcolor']);?>"/>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Popup Text Color', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" class="wcl_color_field" name="wcl_settings[_popup_txtcolor]" value="<?php echo esc_html($wcl_options['_popup_txtcolor']);?>"/>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Button Background Color', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" class="wcl_color_field" name="wcl_settings[_btn_bgcolor]" value="<?php echo esc_html($wcl_options['_btn_bgcolor']);?>"/>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Button Text Color', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" class="wcl_color_field" name="wcl_settings[_btn_txtcolor]" value="<?php echo esc_html($wcl_options['_btn_txtcolor']);?>"/>
											</td>
										</tr>
									</tbody>
								</table>
								<h3>
									<div class="dashicons dashicons-welcome-write-blog space"></div>
									<?php esc_html_e( 'Cookie Popup Content Setting', WCL_TEXTDOMAIN ); ?>
								</h3>
								<table class="form-table">
									<tbody>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Popup Message', WCL_TEXTDOMAIN ); ?></th>
											<td>
												<textarea name="wcl_settings[_popup_message]" class="large-text code" rows="5"><?php echo esc_textarea($wcl_options['_popup_message']); ?></textarea>
												<span class="description"><?php esc_html_e( 'Write here your custom Cookie Law Message.', WCL_TEXTDOMAIN ); ?></span>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Button Label', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" name="wcl_settings[_btn_lable]" value="<?php echo esc_html($wcl_options['_btn_lable']);?>" />
											</td>
										</tr>

										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Policy Link Label', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="text" name="wcl_settings[_policy_lable]" value="<?php echo esc_html($wcl_options['_policy_lable']);?>" />
											</td>
										</tr>

										<tr valign="top">
											<th scope="row"><?php esc_html_e( 'Policy Link URL', WCL_TEXTDOMAIN ); ?></th>
											<td>						
												<input type="url" name="wcl_settings[_policy_url]" value="<?php echo esc_html($wcl_options['_policy_url']);?>" class="large-text" />
											</td>
										</tr>
									</tbody>	
								</table>
							</div> <!-- close .inside -->
						</div> <!-- close .postbox box setting -->						
	    				<?php submit_button(); ?> 
					</form>
					</div>
					</div>
				</div>
			</div>
		</div>
	<?php
}



/* Sanitize Validate Options */
function wcl_sanitize_options_validate($input) {
	// Cookie Setting 
	$input['_enable'] 				= 	wp_filter_nohtml_kses($input['_enable']);
	$input['_position']       		= 	wp_filter_nohtml_kses($input['_position']);
	$input['_theme']       			= 	wp_filter_nohtml_kses($input['_theme']);
	$input['_popup_bgcolor']    	=  	sanitize_text_field($input['_popup_bgcolor']);
	$input['_popup_txtcolor']    	=  	sanitize_text_field($input['_popup_txtcolor']);
	$input['_btn_bgcolor']    		=  	sanitize_text_field($input['_btn_bgcolor']);
	$input['_btn_txtcolor']    		=  	sanitize_text_field($input['_btn_txtcolor']);
	$input['_popup_message'] 		=	sanitize_textarea_field($input['_popup_message']);
	$input['_btn_lable'] 			=	sanitize_text_field($input['_btn_lable']);
	$input['_policy_lable'] 		=	sanitize_text_field($input['_policy_lable']);
	$input['_policy_url'] 			=	esc_url($input['_policy_url']);
	return $input;
}
