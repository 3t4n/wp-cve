<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('CHAT', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check12" data-target="play12" type="checkbox" name="foxtool_settings[chat]" value="1" <?php if ( isset($foxtool_options['chat']) && 1 == $foxtool_options['chat'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play12" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-message-lines"></i> <?php _e('Create a chat feature for users', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[chat-nut1]" value="1" <?php if ( isset($foxtool_options['chat-nut1']) && 1 == $foxtool_options['chat-nut1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable chat button', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable the chat button and configure the content below for use', 'foxtool'); ?></p>
	<?php 
	for ($i = 1; $i <= 10; $i++) { ?>
	<div class="ft-button-grid">
	<?php $styles = array('None', 'Phone', 'SMS', 'Messenger', 'Telegram', 'Zalo', 'Whatsapp', 'Mail', 'Maps'); ?>
	<select name="foxtool_settings[chat-nut1<?php echo $i; ?>]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['chat-nut1'. $i]) && $foxtool_options['chat-nut1'. $i] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	<div class="ft-button-grid-in">
	<input class="ft-input-big" placeholder="<?php _e('Enter button name', 'foxtool'); ?>" type="text" name="foxtool_settings[chat-nut2<?php echo $i; ?>]" value="<?php if(!empty($foxtool_options['chat-nut2'. $i])){echo sanitize_text_field($foxtool_options['chat-nut2'. $i]);} ?>" />
	<input class="ft-input-big" placeholder="<?php _e('Enter contact', 'foxtool'); ?>" type="text" name="foxtool_settings[chat-nut3<?php echo $i; ?>]" value="<?php if(!empty($foxtool_options['chat-nut3'. $i])){echo sanitize_text_field($foxtool_options['chat-nut3'. $i]);} ?>" />
	</div>
	</div>
	<?php } ?>
	<h4><?php _e('Customize chat button', 'foxtool'); ?></h4>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[chat-nut-new]" value="1" <?php if ( isset($foxtool_options['chat-nut-new']) && 1 == $foxtool_options['chat-nut-new'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Open in a new tab', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[chat-nut-color]" type="text" data-coloris value="<?php if(!empty($foxtool_options['chat-nut-color'])){echo $foxtool_options['chat-nut-color'];} ?>"/>
	<label class="ft-right-text"><?php _e('Select button color', 'foxtool'); ?></label>
	</p>
	<p>
	<?php $styles = array('Left', 'Right'); ?>
	<select style="width:120px;" name="foxtool_settings[chat-nut-mar]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['chat-nut-mar']) && $foxtool_options['chat-nut-mar'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	<label class="ft-right-text"><?php _e('Button position', 'foxtool'); ?></label>
	</p>
	<p>
	<?php $styles = array('Icon1', 'Icon2', 'Icon3', 'Icon4', 'Icon5'); ?>
	<select style="width:120px;" name="foxtool_settings[chat-nut-ico]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['chat-nut-ico']) && $foxtool_options['chat-nut-ico'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	<label class="ft-right-text"><?php _e('Select icon', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[chat-nut-bot]" min="10" max="300" value="<?php if(!empty($foxtool_options['chat-nut-bot'])){echo sanitize_text_field($foxtool_options['chat-nut-bot']);} else { echo sanitize_text_field('10');} ?>" class="ftslide" data-index="1">
	<span><?php _e('Spacing below', 'foxtool'); ?> <span id="demo1"></span> PX</span>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[chat-nut-lr]" min="10" max="100" value="<?php if(!empty($foxtool_options['chat-nut-lr'])){echo sanitize_text_field($foxtool_options['chat-nut-lr']);} else { echo sanitize_text_field('10');} ?>" class="ftslide" data-index="2">
	<span><?php _e('Border distance', 'foxtool'); ?> <span id="demo2"></span> PX</span>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[chat-nut-op]" min="0" max="1" step="0.1" value="<?php if(!empty($foxtool_options['chat-nut-op'])){echo sanitize_text_field($foxtool_options['chat-nut-op']);} else { echo sanitize_text_field('1');} ?>" class="ftslide" data-index="3">
	<span><?php _e('Transparency level', 'foxtool'); ?> <span id="demo3"></span></span>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[chat-nut-rus]" min="1" max="50" value="<?php if(!empty($foxtool_options['chat-nut-rus'])){echo sanitize_text_field($foxtool_options['chat-nut-rus']);} else { echo sanitize_text_field('50');} ?>" class="ftslide" data-index="4">
	<span><?php _e('Border radius', 'foxtool'); ?> <span id="demo4"></span> PX</span>
	</p>
	
  <h3><i class="fa-regular fa-message-lines"></i> <?php _e('Tawk.to', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[chat-tawk1]" value="1" <?php if ( isset($foxtool_options['chat-tawk1']) && 1 == $foxtool_options['chat-tawk1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Tawk.to', 'foxtool'); ?></label>
	</p>
	<p>
	<textarea style="height:200px;" class="ft-code-textarea" name="foxtool_settings[chat-tawk11]" placeholder="<?php _e('Enter Tawk.to code', 'foxtool'); ?>"><?php if(!empty($foxtool_options['chat-tawk11'])){echo esc_textarea($foxtool_options['chat-tawk11']);} ?></textarea>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable Tawk.to and add its code to use it', 'foxtool'); ?></p>
</div>		