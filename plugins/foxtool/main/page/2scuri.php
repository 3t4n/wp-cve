<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('SECURITY', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check2" data-target="play2" type="checkbox" name="foxtool_settings[scuri]" value="1" <?php if ( isset($foxtool_options['scuri']) && 1 == $foxtool_options['scuri'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play2" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-badge-check"></i> <?php _e('Enhance website security', 'foxtool') ?></h3>
	<!-- scuri off 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off1]" value="1" <?php if ( isset($foxtool_options['scuri-off1']) && 1 == $foxtool_options['scuri-off1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable REST API', 'foxtool'); ?></label>
	<p class="ft-note ft-note-red"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using REST API, it recommended to disable it for security purposes', 'foxtool'); ?></p>
	<!-- scuri off 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off2]" value="1" <?php if ( isset($foxtool_options['scuri-off2']) && 1 == $foxtool_options['scuri-off2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable XML RPC', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using XML RPC, it recommended to disable it for security purposes', 'foxtool'); ?></p>
	<!-- scuri off 3 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off3]" value="1" <?php if ( isset($foxtool_options['scuri-off3']) && 1 == $foxtool_options['scuri-off3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable Wp-Embed', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using Wp-Embed, it recommended to disable it for security purposes', 'foxtool'); ?></p>
	<!-- scuri off 4 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off4]" value="1" <?php if ( isset($foxtool_options['scuri-off4']) && 1 == $foxtool_options['scuri-off4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable X-Pingback', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using X-Pingback, it recommended to disable it for security purposes', 'foxtool'); ?></p>
	<!-- scuri off 5 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off5]" value="1" <?php if ( isset($foxtool_options['scuri-off5']) && 1 == $foxtool_options['scuri-off5'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove unnecessary header information', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Remove unnecessary header information if desired', 'foxtool'); ?></p>
	<!-- scuri off 6 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-off6]" value="1" <?php if ( isset($foxtool_options['scuri-off6']) && 1 == $foxtool_options['scuri-off6'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable other data sources', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Disable unnecessary data sources', 'foxtool'); ?></p>


  <h3><i class="fa-regular fa-badge-check"></i> <?php _e('Filter uploaded files', 'foxtool') ?></h3>
	<!-- scuri off 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-up1]" value="1" <?php if ( isset($foxtool_options['scuri-up1']) && 1 == $foxtool_options['scuri-up1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable blocking uploads of non-image files', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature will block uploads of all files that are not image formats, from media, plugins, themes, etc', 'foxtool'); ?></p>

  <h3><i class="fa-regular fa-badge-check"></i> <?php _e('Remove version', 'foxtool') ?></h3>
	<!-- scuri ver off 1 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-verof1]" value="1" <?php if ( isset($foxtool_options['scuri-verof1']) && 1 == $foxtool_options['scuri-verof1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove version from JS and CSS', 'foxtool'); ?></label>
	</p>
	<!-- scuri ver off 2 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-verof2]" value="1" <?php if ( isset($foxtool_options['scuri-verof2']) && 1 == $foxtool_options['scuri-verof2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove WordPress version', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Correct, hiding the version of resources such as JS, CSS, and WordPress is a common security measure to prevent hackers from exploiting known vulnerabilities in older versions', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-badge-check"></i> <?php _e('Secure access data', 'foxtool') ?></h3>
	<!-- SQL injection -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[scuri-sql1]" value="1" <?php if ( isset($foxtool_options['scuri-sql1']) && 1 == $foxtool_options['scuri-sql1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Prevent SQL injection, cross-site scripting (XSS)', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature helps protect the website against attacks such as SQL injection, cross-site scripting (XSS)', 'foxtool'); ?></p>
</div>	