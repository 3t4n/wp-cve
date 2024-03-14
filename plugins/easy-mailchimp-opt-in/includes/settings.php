<?php

// register the plugin settings
function pmc_campaign_monitor_register_settings() {
	// register our option
	register_setting( 'pmc_mc_settings_group', 'pmc_mc_settings' );
}
add_action( 'admin_init', 'pmc_campaign_monitor_register_settings', 100 );

function pmc_campaign_monitor_settings_menu() {
	// add settings page
	add_options_page(__('Mailchimp', 'pmc'), __('Mailchimp', 'pmc'),'manage_options', 'mailchimp-optin', 'pmc_settings_page');
}
add_action('admin_menu', 'pmc_campaign_monitor_settings_menu', 100);

function pmc_settings_page() {
	
	global $pmc_options;
		
	?>
	<div class="wrap">
	
	<table width="100%" style="    background: #52BAD5;"><tbody>
	<tr>
	<td width="50%" style="text-align: center;"><img src="http://mahfuzar.info/wp-content/uploads/2014/08/mailchimp.png"></td>
	<td><h1 style="  color: white;"><?php _e('Mailchimp Settings', 'pmc'); ?></h1></td>
	</tr></tbody></table>


 
		<p class="mc-a">Don't have Mailchimp account? <a href="http://eepurl.com/04xUb" target="_blank">Sign Up Free !</a>		</p>
		<?php
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') { 
			echo '<div class="updated"><p>' . __('Settings saved', 'pmc') . '</p></div>';
		}
		?>
		<form method="post" action="options.php" class="ppmc_options_form">
			<?php settings_fields( 'pmc_mc_settings_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scop="row">
						<label for="pmc_mc_settings[api_key]"><?php _e( 'Mailchimp API Key', 'pmc' ); ?></label>	
					</th>		
					<td>		
						<input class="regular-text" type="text" id="pmc_mc_settings[api_key]" style="width: 300px;" name="pmc_mc_settings[api_key]" value="<?php if(isset($pmc_options['api_key'])) { echo esc_attr($pmc_options['api_key']); } ?>"/>
						<p class="description"><?php _e('Enter Mailchimp API key to enable Opt-in option with the registration form.', 'pmc'); ?></p>
						<p>Don't know where to find ? <a href="https://us5.admin.mailchimp.com/account/api/">just click here to get your API</a></p>
					</td>			
				</tr>
				<tr>
					<th scop="row">
						<span><?php _e( 'Email Lists', 'pmc' ); ?></span>	
					</th>	
					<td>
						<?php $lists = pmc_get_lists(); ?>
						<ul>
							<?php
								if($lists) :
									$i = 1;
									foreach($lists as $id => $list_name) :
										echo '<li>' . $list_name . ' - <strong>[mailchimp Optin="' . $i . '"]</strong></li>';
										$i++;									
									endforeach;
								else : ?>
							<li><?php _e('You must enter Mailchimp API and Client ID keys before lists are shown.', 'pmc'); ?></li>
						<?php endif; ?>
						</ul>
						<p class="description"><?php _e('Place the short code shown beside any list in a post or page to display the signup form, or use the dedicated widget.', 'pmc'); ?></p>
					</td>
				</tr>	
				<tr valign="top">
					<th scop="row">
						<label for="pmc_mc_settings[double_opt_in]"><?php _e( 'Double Opt In', 'pmc' ); ?></label>	
					</th>		
					<td>		
						<input class="checkbox" type="checkbox" id="pmc_mc_settings[double_opt_in]" name="pmc_mc_settings[double_opt_in]" value="1" <?php checked(1, $pmc_options['double_opt_in']); ?>/>
						<span class="description"><?php _e('Require Double Opt-in?', 'pmc'); ?></span>
					</td>			
				</tr>
				<tr valign="top">
					<th scop="row">
						<label for="pmc_mc_settings[disable_names]"><?php _e( 'Disable Names', 'pmc' ); ?></label>	
					</th>		
					<td>		
						<input class="checkbox" type="checkbox" id="pmc_mc_settings[disable_names]" name="pmc_mc_settings[disable_names]" value="1" <?php checked(1, $pmc_options['disable_names']); ?>/>
						<span class="description"><?php _e('Disable the Name fields?', 'pmc'); ?></span>
					</td>			
				</tr>
			</table>
			<?php submit_button(); ?>
			
		</form>
	</div><!--end .wrap-->
	<?php
}


?>