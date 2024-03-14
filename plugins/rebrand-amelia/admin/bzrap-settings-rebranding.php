<div class="ame-wl-settings-header">
	<h3><?php _e('Rebranding', 'bzrap'); ?></h3>
</div>
<?php
//~ echo '<pre/>';
//~ print_r($branding);
?>
<div class="ame-wl-settings-wrap">

	<div class="ame-wl-settings">
		<form method="post" id="-form" enctype="multipart/form-data">

			<?php wp_nonce_field( 'ame_wl_nonce', 'ame_wl_nonce' ); ?>

			<div class="ame-wl-setting-tabs">
				<a href="#ame-wl-branding" class="ame-wl-tab active"><?php _e('Branding', 'bzrap'); ?></a>
				<a href="#ame-wl-branding-settings" class="ame-wl-tab"><?php _e('Settings', 'bzrap'); ?></a>
			</div>

			<div class="ame-wl-setting-tabs-content">

				<div id="ame-wl-branding" class="ame-wl-setting-tab-content active">
					<h3 class="bzrap-section-title"><?php esc_html_e('Branding', 'bzrap'); ?></h3>
					<p><?php esc_html_e('You can white label the plugin as per your requirement.', 'bzrap'); ?></p>
					<table class="form-table ame-wl-fields">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_plugin_name"><?php esc_html_e('Plugin Name', 'bzrap'); ?></label>
								</th>
								<td>
									<input id="ame_wl_plugin_name" name="ame_wl_plugin_name" type="text" class="regular-text" value="<?php echo esc_attr($branding['plugin_name']); ?>" placeholder="" />
									
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_plugin_desc"><?php esc_html_e('Plugin Description', 'bzrap'); ?></label>
								</th>
								<td>
									<input id="ame_wl_plugin_desc" name="ame_wl_plugin_desc" type="text" class="regular-text" value="<?php echo esc_attr($branding['plugin_desc']); ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_plugin_author"><?php esc_html_e('Developer / Agency', 'bzrap'); ?></label>
								</th>
								<td>
									<input id="ame_wl_plugin_author" name="ame_wl_plugin_author" type="text" class="regular-text" value="<?php echo esc_attr($branding['plugin_author']); ?>"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_plugin_uri"><?php esc_html_e('Website URL', 'bzrap'); ?></label>
								</th>
								<td>
									<input id="ame_wl_plugin_uri" name="ame_wl_plugin_uri" type="text" class="regular-text" value="<?php echo esc_attr($branding['plugin_uri']); ?>"/>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_primary_color"><?php esc_html_e('Primary Color', 'bzrap'); ?></label>
								</th>
								<td>
									<input id="ame_wl_primary_color" name="ame_wl_primary_color" type="text" class="ame-wl-color-picker" value="" disabled />
									<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_logo"><?php esc_html_e('Logo', 'bzrap'); ?></label>
								</th>
								<td>
								<?php 
									
									$default_image = plugins_url('uploads/NoImage.png', __FILE__);

									if ( isset( $branding['ame_logo'] )  && $branding['ame_logo'] != '' ) {
										$image_attributes = wp_get_attachment_image_src( $branding['ame_logo'] );
										$src = $image_attributes[0];
										$value = $branding['ame_logo'];
									} else {
										$src = $default_image;
										$value = '';
									}
								?>
									<div class="bzrap upload">
										<img data-src="<?php echo esc_attr($default_image); ?>" src="<?php echo esc_attr($src); ?>" />
										<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
										<div class="btns">
											<input type="hidden" name="ame_wl_logo" id="ame_wl_logo" value="<?php echo esc_attr($value);?>" />
											<button type="button" class="bzrap_upload_image_button button" disabled><?php _e('Upload','bzrap'); ?></button>
											<button type="button" class="bzrap_remove_image_button button" disabled>&times;</button>
										</div>
									</div>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row" valign="top">
									<label for="ame_wl_loader"><?php esc_html_e('Loader Image', 'bzrap'); ?></label>
								</th>
								<td>
								<?php 
									$default_image1 = plugins_url('uploads/NoImage.png', __FILE__);
									if ( isset( $branding['ame_loader'] ) && $branding['ame_loader'] != '' ) {
										$image_attributes1 = wp_get_attachment_image_src( $branding['ame_loader'] );
										$src1 = $image_attributes1[0];
										$value1 = $branding['ame_loader'];
									} else {
										$src1 = $default_image1;
										$value1 = '';
									}
								?>
									<div class="bzrap upload">
										<img data-src="<?php echo esc_attr($default_image1); ?>" src="<?php echo esc_attr($src1); ?>" />
										<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
										<div class="btns">
											<input type="hidden" name="ame_wl_loader" id="ame_wl_loader" value="<?php echo esc_attr($value1);?>" />
											<button type="button" class="bzrap_upload_image_button button" disabled><?php _e('Upload','bzrap'); ?></button>
											<button type="button" class="bzrap_remove_image_button button" disabled>&times;</button>
										</div>
									</div>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>


				<div id="ame-wl-branding-settings" class="ame-wl-setting-tab-content">
					
					<table class="form-table ame-wl-fields">
			
						<tr valign="top">
							<th scope="row" valign="top">
								<label for="ame_wl_hide_external_links">Hide Amelia External Links</label>
							</th>
							<td>
								<input id="ame_wl_hide_external_links" name="ame_wl_hide_external_links" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_general_settings">Hide General Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_general_settings" name="ame_wl_hide_general_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_company_settings">Hide Company Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_company_settings" name="ame_wl_hide_company_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_notification_settings">Hide Notifications Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_notification_settings" name="ame_wl_hide_notification_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_whdo_settings">Hide Working Hours & Days Off Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_whdo_settings" name="ame_wl_hide_whdo_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_payment_settings">Hide Payments Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_payment_settings" name="ame_wl_hide_payment_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_googlecalender_settings">Hide Integrations Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_googlecalender_settings" name="ame_wl_hide_googlecalender_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_appointment_settings">Hide Appointments Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_appointment_settings" name="ame_wl_hide_appointment_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_labels_settings">Hide Labels Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_labels_settings" name="ame_wl_hide_labels_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_roles_settings">Hide Roles Settings</label>
							</th>
							<td>
								<input id="ame_wl_hide_roles_settings" name="ame_wl_hide_roles_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>  
						</tr>
							
						<tr valign="top">
							<th scope="row" valign="top">
									<label for="ame_wl_hide_activation_settings"><?php esc_html_e('Hide Activation Settings', 'bzrap'); ?></label>
							</th>
							<td>
								<input id="ame_wl_hide_activation_settings" name="ame_wl_hide_activation_settings" type="checkbox" class="" value="on" disabled />
								<p><a href="https://rebrandpress.com/pricing" target="_blank">Get Pro</a> to use this feature.</p>
							</td>
						</tr>
															
					 </table>
				
				</div>
				
				<div class="ame-wl-setting-footer">
					<p class="submit">
						<input type="submit" name="submit" id="ame_save_branding" class="button button-primary bzrap-save-button" value="<?php esc_html_e('Save Settings', 'bzrap'); ?>" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
