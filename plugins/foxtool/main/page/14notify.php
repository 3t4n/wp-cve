<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('NOTIFY', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check14" data-target="play14" type="checkbox" name="foxtool_settings[notify]" value="1" <?php if ( isset($foxtool_options['notify']) && 1 == $foxtool_options['notify'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play14" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-shield-halved"></i> <?php _e('Browser ad-block notification', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[notify-block1]" value="1" <?php if ( isset($foxtool_options['notify-block1']) && 1 == $foxtool_options['notify-block1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable ad-block detection', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[notify-block11]" value="1" <?php if ( isset($foxtool_options['notify-block11']) && 1 == $foxtool_options['notify-block11'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Only notify, do not block access', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[notify-block-c1]" type="text" data-coloris value="<?php if(!empty($foxtool_options['notify-block-c1'])){echo sanitize_text_field($foxtool_options['notify-block-c1']);} ?>"/>
	<label class="ft-right-text"><?php _e('Select button color', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[notify-block-c2]" type="text" data-coloris value="<?php if(!empty($foxtool_options['notify-block-c2'])){echo sanitize_text_field($foxtool_options['notify-block-c2']);} ?>"/>
	<label class="ft-right-text"><?php _e('Select button border color', 'foxtool'); ?></label>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Enter title', 'foxtool') ?>" name="foxtool_settings[notify-block12]" type="text" value="<?php if(!empty($foxtool_options['notify-block12'])){echo sanitize_text_field($foxtool_options['notify-block12']);} ?>"/>
	</p>
	<p>
	<textarea style="height:150px;" class="ft-code-textarea" name="foxtool_settings[notify-block13]" placeholder="<?php _e('Enter content here', 'foxtool'); ?>"><?php if(!empty($foxtool_options['notify-block13'])){echo esc_textarea($foxtool_options['notify-block13']);} ?></textarea>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enter the title and content you want to display when ad-blocker is detected', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-bells"></i> <?php _e('Notification at the top of the page', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[notify-notis1]" value="1" <?php if ( isset($foxtool_options['notify-notis1']) && 1 == $foxtool_options['notify-notis1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable notification', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[notify-notis-c1]" type="text" data-coloris value="<?php if(!empty($foxtool_options['notify-notis-c1'])){echo sanitize_text_field($foxtool_options['notify-notis-c1']);} ?>"/>
	<label class="ft-right-text"><?php _e('Select background color', 'foxtool'); ?></label>
	</p>
	<p>
	<textarea style="height:150px;" class="ft-code-textarea" name="foxtool_settings[notify-notis11]" placeholder="<?php _e('Enter content here', 'foxtool'); ?>"><?php if(!empty($foxtool_options['notify-notis11'])){echo esc_textarea($foxtool_options['notify-notis11']);} ?></textarea>
	</p>
</div>