<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('GOOGLE', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check11" data-target="play11" type="checkbox" name="foxtool_settings[goo]" value="1" <?php if ( isset($foxtool_options['goo']) && 1 == $foxtool_options['goo'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play11" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-arrow-right-to-arc"></i> <?php _e('Sign in with Google account', 'foxtool') ?></h3>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[goo-log1]" value="1" <?php if ( isset($foxtool_options['goo-log1']) && 1 == $foxtool_options['goo-log1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable to use', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable and configure the functions below to enable Google sign-in to work', 'foxtool'); ?></p>
	<p>
	<input class="ft-input-big ft-view-in" type="text" value="<?php echo home_url(); ?>/wp-admin/admin-ajax.php?action=foxtool_login_google"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Copy the link below to add it to the Authorized redirect URLs in your Google Developers project', 'foxtool'); ?><br>
	<a target="_blank" href="https://console.developers.google.com">Google Developers Console</a>
	</p>
	<h4><?php _e('Registration role options', 'foxtool'); ?></h4>
	<?php
	$roles = get_editable_roles();
	echo '<p><select name="foxtool_settings[goo-role1]">';
	echo '<option value="">Default</option>';
	foreach ($roles as $role_name => $role_info) {
		if ($role_name != 'administrator' && $role_name != 'editor') {
		if(isset($foxtool_options['goo-role1']) && $foxtool_options['goo-role1'] == $role_name) { $selected = 'selected="selected"'; } else { $selected = NULL; }
		echo '<option value="'. $role_name .'" '. $selected .'>'. $role_name .'</option>';
		}
	}
	echo '</select></p>';
	?>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can customize the role of successful registrants, with the default role being "subscriber"', 'foxtool'); ?></p>
	<h4><?php _e('Add Google API', 'foxtool'); ?></h4>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Client ID', 'foxtool'); ?>" name="foxtool_settings[goo-log11]" type="text" value="<?php if(!empty($foxtool_options['goo-log11'])){echo sanitize_text_field($foxtool_options['goo-log11']);} ?>"/>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Client Secret', 'foxtool'); ?>" name="foxtool_settings[goo-log12]" type="text" value="<?php if(!empty($foxtool_options['goo-log12'])){echo sanitize_text_field($foxtool_options['goo-log12']);} ?>"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Retrieve the API Client ID and Client Secret from your Google Developers project and add them to the two fields above', 'foxtool'); ?></p>
	
	<h4><?php _e('Display options', 'foxtool'); ?></h4>
	<p>
	<input class="ft-input-big ft-view-in" type="text" value="[google-login]"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can paste the shortcode into the position where you want the login button to appear', 'foxtool'); ?></p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[goo-log13]" value="1" <?php if ( isset($foxtool_options['goo-log13']) && 1 == $foxtool_options['goo-log13'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Display on the default login form', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable to display the Google login button on the default WordPress login form', 'foxtool'); ?></p>
	
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[goo-log14]" value="1" <?php if ( isset($foxtool_options['goo-log14']) && 1 == $foxtool_options['goo-log14'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Display on the WooCommerce login form', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable to display the Google login button on the WooCommerce login form', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-arrow-right-to-arc"></i> <?php _e('Block login spam with Google reCAPTCHA', 'foxtool') ?></h3>
	<p>
	<?php $styles = array('None', 'V2', 'V3'); ?>
	<select name="foxtool_settings[goo-cap1]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['goo-cap1']) && $foxtool_options['goo-cap1'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Site key', 'foxtool'); ?>" name="foxtool_settings[goo-cap11]" type="text" value="<?php if(!empty($foxtool_options['goo-cap11'])){echo sanitize_text_field($foxtool_options['goo-cap11']);} ?>"/>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Secret key', 'foxtool'); ?>" name="foxtool_settings[goo-cap12]" type="text" value="<?php if(!empty($foxtool_options['goo-cap12'])){echo sanitize_text_field($foxtool_options['goo-cap12']);} ?>"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Retrieve the Site Key and Secret Key from your Google reCAPTCHA project and add them to the two fields above', 'foxtool'); ?><br>
	<a target="_blank" href="https://www.google.com/recaptcha">Google reCAPTCHA</a>
	</p>
</div>		