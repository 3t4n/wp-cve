<div class="wrap">
	<h2>Goolytics - Simple Google Analytics</h2>
	
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<?php settings_fields(self::_NAMESPACE); ?>
		
		<div id="poststuff">
			<div class="postbox">
				<div class="inside">
					<h3><?php _e('Settings', 'goolytics-simple-google-analytics'); ?></h3>
					<table class="form-table">
						
					<tr valign="top">
						<th scope="row"><label for="goolytics_web_property_id"><strong><?php _e('Google Analytics ID', 'goolytics-simple-google-analytics'); ?>:</strong><br /><small><a href="https://www.google.com/analytics/web/" target="_blank"><?php _e('(My Analytics accounts)', 'goolytics-simple-google-analytics'); ?></a></small></label></th>
						<td><input type="text" style="width:130px;" id="goolytics_web_property_id" name="goolytics_web_property_id" value="<?php echo get_option('goolytics_web_property_id'); ?>" /> <small><?php _e('Example: UA-0000000-0', 'goolytics-simple-google-analytics'); ?></small></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="goolytics_anonymize_ip"><strong><?php _e('Anonymize IP?', 'goolytics-simple-google-analytics'); ?>:</strong><br /><small><?php _e('(Recommended for german users)', 'goolytics-simple-google-analytics'); ?></small></label></th>
						<td>
							<select id="goolytics_anonymize_ip" name="goolytics_anonymize_ip" style="width:130px;">
								<option value="0" <?php selected(get_option('goolytics_anonymize_ip'), 0); ?>><?php _e('No', 'goolytics-simple-google-analytics') ; ?></option>
								<option value="1" <?php selected(get_option('goolytics_anonymize_ip'), 1); ?>><?php _e('Yes', 'goolytics-simple-google-analytics') ; ?></option>
							</select>
							<small><?php _e('Tells Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'goolytics-simple-google-analytics'); ?></small>
							<?php
							if( get_locale() == 'de_DE' ) {
								echo "<p style='padding:3px 6px;background:#ffffe0;border:solid 1px #e6db55;font-size:small;'>" . __("According to your backend locale, you seem to be a german user. It is recommended to set 'Anonymize IP' to Yes in order to respect german data protection rules!", 'goolytics-simple-google-analytics') . "</p>";
							}
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="goolytics_usercentrics_support"><strong><?php _e('Enable Usercentrics Mode?', 'goolytics-simple-google-analytics'); ?>:</strong><br /><small><?php _e('(Consent Management Platform)', 'goolytics-simple-google-analytics'); ?></small></label></th>
						<td>
							<select id="goolytics_usercentrics_support" name="goolytics_usercentrics_support" style="width:130px;">
								<option value="0" <?php selected(get_option('goolytics_usercentrics_support'), 0); ?>><?php _e('No', 'goolytics-simple-google-analytics') ; ?></option>
								<option value="1" <?php selected(get_option('goolytics_usercentrics_support'), 1); ?>><?php _e('Yes', 'goolytics-simple-google-analytics') ; ?></option>
							</select>
							<small><?php echo __('<strong>Usercentrics subscription required!</strong> Alters the Universal Analytics code to be working with Usercentrics Consent Management Platform.', 'goolytics-simple-google-analytics') . ' ' . __('<a href="https://usercentrics.com/knowledge/direct-integration-usercentrics-script-website/" target="_blank">Additional steps required (see step 1)</a>!', 'goolytics-simple-google-analytics'); ?></small>
							<?php
							if( get_locale() == 'de_DE' ) {
								echo "<p style='padding:3px 6px;background:#ffffe0;border:solid 1px #e6db55;font-size:small;'>" . __("According to your backend locale, you seem to be a german user. If you're using Usercentrics it is recommended to set 'Enable Usercentrics Mode' to Yes in order to respect german data protection rules!", 'goolytics-simple-google-analytics') . "</p>";
							}
							?>
						</td>
					</tr>
					
					</table>
				
					<p>
						<input type="submit" class="button button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
				</div>
			</div>
			
			<div id="ridwpa_plugins_box" class="postbox">
				<div class="inside">
			      	<h3><?php _e('More of my WordPress plugins', 'goolytics-simple-google-analytics'); ?></h3>
					<table class="form-table">
			 		<tr>
			 			<td>
			 				<?php _e('You may also be interested in some of my other plugins:', 'goolytics-simple-google-analytics'); ?>
							<p id="authorplugins-wrap"><input id="authorplugins-start" value="<?php _e('Show other plugins by this author inline &raquo;', 'goolytics-simple-google-analytics'); ?>" class="button-secondary" type="button"></p>
							<div id="authorplugins-wrap">
								<div id='authorplugins'>
									<div class='authorplugins-holder full' id='authorplugins_secondary'>
										<div class='authorplugins-content'>
											<ul id="authorpluginsul">
												
											</ul>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
			 				<?php _e('More plugins at: <a class="button rbutton" href="http://www.schloebe.de/portfolio/" target="_blank">www.schloebe.de</a>', 'goolytics-simple-google-analytics'); ?>
			 			</td>
			 		</tr>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>