<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('TOOL', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check3" data-target="play3" type="checkbox" name="foxtool_settings[tool]" value="1" <?php if ( isset($foxtool_options['tool']) && 1 == $foxtool_options['tool'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play3" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-pen-to-square"></i> <?php _e('Text editor tool', 'foxtool') ?></h3>
	<!-- tool class 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-edit1]" value="1" <?php if ( isset($foxtool_options['tool-edit1']) && 1 == $foxtool_options['tool-edit1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Classic Editor', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you find the new editor too difficult to use, then revert it to the Classic Editor version', 'foxtool'); ?></p>
	<!-- tool class 11 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-edit11]" value="1" <?php if ( isset($foxtool_options['tool-edit11']) && 1 == $foxtool_options['tool-edit11'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enhance features for Classic Editor', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want to add additional functionalities to the Classic Editor to enhance professional editing', 'foxtool'); ?></p>
	<!-- tool class 11 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-edit12]" value="1" <?php if ( isset($foxtool_options['tool-edit12']) && 1 == $foxtool_options['tool-edit12'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Add Write and Edit buttons to the Classic Editor', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want to add Write or Edit buttons for the Classic Editor in the post and page management interface. With this feature, you dont need to set Classic Editor as default but can use it alongside', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-box"></i> <?php _e('Optimize Widgets', 'foxtool') ?></h3>
	<!-- tool class 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-widget1]" value="1" <?php if ( isset($foxtool_options['tool-widget1']) && 1 == $foxtool_options['tool-widget1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Classic Widget', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you find the new Widget Manager too difficult to use, then revert it to the Classic Widget version', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-gear"></i> <?php _e('Disable automatic updates', 'foxtool') ?></h3>
	<!-- tool off upload 1 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-upload1]" value="1" <?php if ( isset($foxtool_options['tool-upload1']) && 1 == $foxtool_options['tool-upload1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable core updates', 'foxtool'); ?></label>
	</p>
	<p>
	<!-- tool off upload 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-upload2]" value="1" <?php if ( isset($foxtool_options['tool-upload2']) && 1 == $foxtool_options['tool-upload2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable language pack updates', 'foxtool'); ?></label>
	</p>
	<p>
	<!-- tool off upload 3 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-upload3]" value="1" <?php if ( isset($foxtool_options['tool-upload3']) && 1 == $foxtool_options['tool-upload3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable theme updates', 'foxtool'); ?></label>
	</p>
	<p>
	<!-- tool off upload 4 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-upload4]" value="1" <?php if ( isset($foxtool_options['tool-upload4']) && 1 == $foxtool_options['tool-upload4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable plugin updates', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can disable the automatic update feature of WordPress', 'foxtool'); ?></p>	
	
  <h3><i class="fa-regular fa-gear"></i> <?php _e('Management tool', 'foxtool') ?></h3>
	<!-- tool manager 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-mana1]" value="1" <?php if ( isset($foxtool_options['tool-mana1']) && 1 == $foxtool_options['tool-mana1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Redirect 404 to homepage', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature allows you to redirect non-existing links (404 errors) to the homepage', 'foxtool'); ?></p>
	
	<!-- tool manager 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-mana2]" value="1" <?php if ( isset($foxtool_options['tool-mana2']) && 1 == $foxtool_options['tool-mana2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disallow text copying and access to DevTools', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This function prevents users from copying text, accessing right-click options, and accessing DevTools', 'foxtool'); ?></p>
	
	<!-- tool manager 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-mana3]" value="1" <?php if ( isset($foxtool_options['tool-mana3']) && 1 == $foxtool_options['tool-mana3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Classic Editor in category description', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature allows you to add the Classic Editor to the category description box when editing posts or products', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-eye-slash"></i> <?php _e('Hide the tools you want', 'foxtool') ?></h3>
	<!-- tool hiden 1 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden1]" value="1" <?php if ( isset($foxtool_options['tool-hiden1']) && 1 == $foxtool_options['tool-hiden1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Dashboard', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 2 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden2]" value="1" <?php if ( isset($foxtool_options['tool-hiden2']) && 1 == $foxtool_options['tool-hiden2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Posts', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 3 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden3]" value="1" <?php if ( isset($foxtool_options['tool-hiden3']) && 1 == $foxtool_options['tool-hiden3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Pages', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 4 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden4]" value="1" <?php if ( isset($foxtool_options['tool-hiden4']) && 1 == $foxtool_options['tool-hiden4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Feedback', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 5 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden5]" value="1" <?php if ( isset($foxtool_options['tool-hiden5']) && 1 == $foxtool_options['tool-hiden5'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Media', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 6 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden6]" value="1" <?php if ( isset($foxtool_options['tool-hiden6']) && 1 == $foxtool_options['tool-hiden6'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Appearance', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 7 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden7]" value="1" <?php if ( isset($foxtool_options['tool-hiden7']) && 1 == $foxtool_options['tool-hiden7'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Plugins', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 8 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden8]" value="1" <?php if ( isset($foxtool_options['tool-hiden8']) && 1 == $foxtool_options['tool-hiden8'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Users', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 9 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden9]" value="1" <?php if ( isset($foxtool_options['tool-hiden9']) && 1 == $foxtool_options['tool-hiden9'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Tools', 'foxtool'); ?></label>
	</p>
	<!-- tool hiden 10 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-hiden10]" value="1" <?php if ( isset($foxtool_options['tool-hiden10']) && 1 == $foxtool_options['tool-hiden10'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Settings', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you find the tools above unnecessary, you can hide them to make the WP admin interface cleaner. This function only hides them without blocking access to their links', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-bug"></i> <?php _e('Maintenance mode for developers', 'foxtool') ?></h3>
	<!-- tool bao tri -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[tool-dev1]" value="1" <?php if ( isset($foxtool_options['tool-dev1']) && 1 == $foxtool_options['tool-dev1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable maintenance mode', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('All links on your website will redirect to the maintenance page, and only logged-in admin accounts can view the content', 'foxtool'); ?></p>
</div>	