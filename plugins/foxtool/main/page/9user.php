<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('USER', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check9" data-target="play9" type="checkbox" name="foxtool_settings[user]" value="1" <?php if ( isset($foxtool_options['user']) && 1 == $foxtool_options['user'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play9" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-lock"></i> <?php _e('Set access and viewing permissions', 'foxtool') ?></h3>
	<!-- set quyen truy cap 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[user-post1]" value="1" <?php if ( isset($foxtool_options['user-post1']) && 1 == $foxtool_options['user-post1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Filter posts and images', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('With this feature, regular users can only view their own posts and images they uploaded, while the admin can view all of them', 'foxtool'); ?></p>
	
	<!-- set quyen truy cap 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[user-wp1]" value="1" <?php if (isset($foxtool_options['user-wp1']) && 1 == $foxtool_options['user-wp1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Only admin has access to the admin page', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('With this feature, regular users cannot access the WordPress admin page', 'foxtool'); ?></p>
	
	<!-- set quyen truy cap 3 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[user-id1]" value="1" <?php if (isset($foxtool_options['user-id1']) && 1 == $foxtool_options['user-id1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Display ID in the management page', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Allow displaying member IDs on the profile management page', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-list-dropdown"></i> <?php _e('Option to display the Admin bar', 'foxtool') ?></h3>				  
	<!-- admin bar -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[user-bar1]" value="1" <?php if ( isset($foxtool_options['user-bar1']) && 1 == $foxtool_options['user-bar1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable the Admin Bar', 'foxtool'); ?></label>
	
	<p>
	<?php $styles = array('All', 'User'); ?>
	<select name="foxtool_settings[user-bar11]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['user-bar11']) && $foxtool_options['user-bar11'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you find the Admin Bar distracting every time you view the website, you can turn it off (there is an option for you to turn off all or only turn off for users)', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-user"></i> <?php _e('Add avatar upload functionality', 'foxtool') ?></h3>
	<!-- set quyen truy cap 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[user-upav1]" value="1" <?php if ( isset($foxtool_options['user-upav1']) && 1 == $foxtool_options['user-upav1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Allow avatar upload', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('With this feature, there will be an additional button in the profile section allowing users to upload avatars', 'foxtool'); ?></p>
</div>	