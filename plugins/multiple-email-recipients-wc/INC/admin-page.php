<?php

function sunarcwoome_options_page() {

	global $sunarcwoome_options;

	ob_start(); ?>
	<div class="wrap">
		<h2>Multiple Recipients for E-Mail</h2>
		
		<form method="post" action="options.php">
		
			<?php settings_fields('sunarcwoome_settings_group'); ?>
		
		
			
			<div class="sun_core">
			<h4><?php _e('Select the WooCommerce Mails you want to have multiple recipients', 'woome_domain_sunarc'); ?></h4>
			<h5><?php _e('WooCommerce Core', 'woome_domain_sunarc'); ?></h5>
			<p> 
				<input name="woome_settings_sunarc[enable_new]" value="0" type="hidden">
				<input type="checkbox" name="woome_settings_sunarc[enable_new]" value="1"<?php checked( 1 == $sunarcwoome_options['enable_new'] ); ?> />
				<label class="description" for="woome_settings_sunarc[enable_new]"><?php _e('WooCommerce New Order Mail', 'woome_domain_sunarc'); ?></label>				
			</p>

			<p> 
				<input name="woome_settings_sunarc[enable_cancelled]" value="0" type="hidden">
				<input type="checkbox" name="woome_settings_sunarc[enable_cancelled]" value="1"<?php checked( 1 == $sunarcwoome_options['enable_cancelled'] ); ?> />
				<label class="description" for="woome_settings_sunarc[enable_cancelled]"><?php _e('WooCommerce Cancelled Order Mail', 'woome_domain_sunarc'); ?></label>				
			</p>
			</div>
			
			
			<div class="sun_recipients">
    			<h4><?php _e('Enter additional E-Mail recipients upto 2. <br><p>Enter one E-Mail ID per field.</p>', 'woome_domain_sunarc'); ?></h4>
    			<p>
    				<input size="70" id="woome_settings_sunarc[email_1]" placeholder="Email ID 1" name="woome_settings_sunarc[email_1]" type="text" value="<?php echo $sunarcwoome_options['email_1']; ?>"/><br>
    				<label class="description" for="woome_settings_sunarc[email_1]"><?php _e('', 'woome_domain_sunarc'); ?></label>
    			</p>
    
    			<p>
    				<input size="70" id="woome_settings_sunarc[email_2]" placeholder="Email ID 2" name="woome_settings_sunarc[email_2]" type="text" value="<?php echo $sunarcwoome_options['email_2']; ?>"/><br>
    				<label class="description" for="woome_settings_sunarc[email_1]"><?php _e('', 'woome_domain_sunarc'); ?></label>
    			</p>
			</div>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options', 'woome_domain_sunarc'); ?>" />
			</p>
		
		</form>
		
	</div>
	<?php
	echo ob_get_clean();
}

 



function sunarcwoome_add_options_link() {
	add_options_page('Multiple Recipients for Email', 'Multiple Email Recipients', 'manage_options', 'woome_options', 'sunarcwoome_options_page');
}
add_action('admin_menu', 'sunarcwoome_add_options_link');

function sunarcwoome_register_settings() {
	// Creates settings in the options table
	register_setting('sunarcwoome_settings_group', 'woome_settings_sunarc');
}
add_action('admin_init', 'sunarcwoome_register_settings');