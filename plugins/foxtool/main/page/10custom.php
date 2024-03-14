<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('CUSTOM', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check10" data-target="play10" type="checkbox" name="foxtool_settings[custom]" value="1" <?php if ( isset($foxtool_options['custom']) && 1 == $foxtool_options['custom'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play10" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-screwdriver-wrench"></i> <?php _e('Change login link', 'foxtool') ?></h3>
	<!-- thay doi link dăng nhap 1 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-chan1]" value="1" <?php if ( isset($foxtool_options['custom-chan1']) && 1 == $foxtool_options['custom-chan1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable for use', 'foxtool'); ?></label>
	</p>
	<p>
	<input class="ft-input-big" name="foxtool_settings[custom-chan11]" type="text" value="<?php if(!empty($foxtool_options['custom-chan11'])){echo sanitize_text_field($foxtool_options['custom-chan11']);} ?>" placeholder="<?php _e('Enter a suffix', 'fox'); ?>" />
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature and enter the login link suffix', 'foxtool'); ?><br>
	<?php _e('Example: domain.com/wp-admin where wp-admin is the suffix', 'foxtool'); ?><br>
	<b><?php if(!empty($foxtool_options['custom-chan11'])){ echo __('Login link:', 'foxtool') .' '. home_url('/'. $foxtool_options['custom-chan11']);} ?></b>
	</p>

  <h3><i class="fa-regular fa-swatchbook"></i> <?php _e('Custom WordPress login page', 'foxtool') ?></h3>
	<!-- set quyen truy cap 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-ad1]" value="1" <?php if ( isset($foxtool_options['custom-ad1']) && 1 == $foxtool_options['custom-ad1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable custom functionality', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature to be able to use the customizations below', 'foxtool'); ?></p>
	
	<h4><?php _e('Select the displayed logo', 'foxtool') ?></h4>
	<p style="display:flex;">
	<input id="ft-add2" class="ft-input-big" name="foxtool_settings[custom-logo1]" type="text" value="<?php if(!empty($foxtool_options['custom-logo1'])){echo sanitize_text_field($foxtool_options['custom-logo1']);} ?>" placeholder="<?php _e('Add the image logo link here', 'fox'); ?>" />
	<button class="ft-selec" data-input-id="ft-add2"><?php _e('Select image', 'foxtool'); ?></button>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('The standard size for the logo is 280x80 PX', 'foxtool'); ?></p>
	
	<h4><?php _e('Customize background', 'foxtool') ?></h4>
	
	<p>
	<?php $styles = array('None', 'Auto', 'Color', 'Upload'); ?>
	<select name="foxtool_settings[custom-bg1]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['custom-bg1']) && $foxtool_options['custom-bg1'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('None: default not selected, Auto: background image automatically changes each time the page is loaded, Color: change the color you want, Upload: upload the background image you want', 'foxtool'); ?></p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-bg11]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-bg11'])){echo $foxtool_options['custom-bg11'];} ?>"/>
	<label class="ft-right-text"><?php _e('Background color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;">
	<input id="ft-add3" class="ft-input-big" name="foxtool_settings[custom-bg12]" type="text" value="<?php if(!empty($foxtool_options['custom-bg12'])){echo sanitize_text_field($foxtool_options['custom-bg12']);} ?>" placeholder="<?php _e('Add background image link', 'fox'); ?>" />
	<button class="ft-selec" data-input-id="ft-add3"><?php _e('Select image', 'foxtool'); ?></button>
	</p>
	
	<h4><?php _e('Customize elements on the page', 'foxtool') ?></h4>
	
	<!-- tuy bien bang nhap -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-main1]" value="1" <?php if ( isset($foxtool_options['custom-main1']) && 1 == $foxtool_options['custom-main1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable element customization', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can further customize your login page by enabling this feature', 'foxtool'); ?></p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main11]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main11'])){echo sanitize_text_field($foxtool_options['custom-main11']);} ?>"/>
	<label class="ft-right-text"><?php _e('Background color of the input form', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main12]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main12'])){echo sanitize_text_field($foxtool_options['custom-main12']);} ?>"/>
	<label class="ft-right-text"><?php _e('Input / select box color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main13]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main13'])){echo sanitize_text_field($foxtool_options['custom-main13']);} ?>"/>
	<label class="ft-right-text"><?php _e('Text color of the input / select box', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main14]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main14'])){echo sanitize_text_field($foxtool_options['custom-main14']);} ?>"/>
	<label class="ft-right-text"><?php _e('Button color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main15]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main15'])){echo sanitize_text_field($foxtool_options['custom-main15']);} ?>"/>
	<label class="ft-right-text"><?php _e('Button text color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main16]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main16'])){echo sanitize_text_field($foxtool_options['custom-main16']);} ?>"/>
	<label class="ft-right-text"><?php _e('Display text color', 'foxtool'); ?></label>
	</p>
	
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[custom-main17]" type="text" data-coloris value="<?php if(!empty($foxtool_options['custom-main17'])){echo sanitize_text_field($foxtool_options['custom-main17']);} ?>"/>
	<label class="ft-right-text"><?php _e('Link display color', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[custom-main18]" min="1" max="20" value="<?php if(!empty($foxtool_options['custom-main18'])){echo sanitize_text_field($foxtool_options['custom-main18']);} else { echo sanitize_text_field('7');} ?>" class="ftslide" data-index="9">
	<span><?php _e('Border radius', 'foxtool'); ?> <span id="demo9"></span> PX</span>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-main19]" value="1" <?php if ( isset($foxtool_options['custom-main19']) && 1 == $foxtool_options['custom-main19'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide back link', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-main20]" value="1" <?php if ( isset($foxtool_options['custom-main20']) && 1 == $foxtool_options['custom-main20'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide language options', 'foxtool'); ?></label>
	</p>
 
  <h3><i class="fa-brands fa-wordpress"></i> <?php _e('Change WordPress logo in the Admin bar', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-logbar1]" value="1" <?php if ( isset($foxtool_options['custom-logbar1']) && 1 == $foxtool_options['custom-logbar1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable logo display', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-logbar2]" value="1" <?php if ( isset($foxtool_options['custom-logbar2']) && 1 == $foxtool_options['custom-logbar2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable logo customization', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;">
	<input id="ft-add4" class="ft-input-big" name="foxtool_settings[custom-logbar21]" type="text" value="<?php if(!empty($foxtool_options['custom-logbar21'])){echo sanitize_text_field($foxtool_options['custom-logbar21']);} ?>" placeholder="<?php _e('Add logo link', 'fox'); ?>" />
	<button class="ft-selec" data-input-id="ft-add4"><?php _e('Select image', 'foxtool'); ?></button>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('The changing image is square, with the standard size being 100x100 pixels', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-input-text"></i> <?php _e('Modify the footer content of WP admin', 'foxtool') ?></h3>
	<!-- tuy chinh chan trang -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-foo1]" value="1" <?php if ( isset($foxtool_options['custom-foo1']) && 1 == $foxtool_options['custom-foo1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable custom footer', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want to customize the footer in the WP admin', 'foxtool'); ?></p>
	<p>
	<textarea class="ft-textarea" name="foxtool_settings[custom-foo11]" placeholder="<?php _e('Please enter the content here', 'foxtool'); ?>"><?php if(!empty($foxtool_options['custom-foo11'])){echo esc_textarea($foxtool_options['custom-foo11']);} ?></textarea>
	</p>
  
  <h3><i class="fa-regular fa-window"></i> <?php _e('Customize dashboard widgets', 'foxtool') ?></h3>
	
	<h4><?php _e('Disable unused widgets', 'foxtool') ?></h4>
	<!-- tuy chinh bang tin -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home1]" value="1" <?php if ( isset($foxtool_options['custom-home1']) && 1 == $foxtool_options['custom-home1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable statistics widget', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home2]" value="1" <?php if ( isset($foxtool_options['custom-home2']) && 1 == $foxtool_options['custom-home2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable WordPress info widget', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home3]" value="1" <?php if ( isset($foxtool_options['custom-home3']) && 1 == $foxtool_options['custom-home3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable quick draft widget', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home4]" value="1" <?php if ( isset($foxtool_options['custom-home4']) && 1 == $foxtool_options['custom-home4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable recent posts widget', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home5]" value="1" <?php if ( isset($foxtool_options['custom-home5']) && 1 == $foxtool_options['custom-home5'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable welcome widget', 'foxtool'); ?></label>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-home6]" value="1" <?php if ( isset($foxtool_options['custom-home6']) && 1 == $foxtool_options['custom-home6'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable health widget', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can disable default widgets on the dashboard that you dont use', 'foxtool'); ?></p>
	
	<h4><?php _e('Your custom widget', 'foxtool') ?></h4>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[custom-wid1]" value="1" <?php if ( isset($foxtool_options['custom-wid1']) && 1 == $foxtool_options['custom-wid1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable custom widget', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can create your widget by activating it and entering content into the box below', 'foxtool'); ?></p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Widget title', 'foxtool') ?>" name="foxtool_settings[custom-wid11]" type="text" value="<?php if(!empty($foxtool_options['custom-wid11'])){echo sanitize_text_field($foxtool_options['custom-wid11']);} ?>"/>
	</p>
	<p>
	<textarea style="height:150px;" class="ft-textarea" name="foxtool_settings[custom-wid12]" placeholder="<?php _e('Enter widget content here', 'foxtool'); ?>"><?php if(!empty($foxtool_options['custom-wid12'])){echo esc_textarea($foxtool_options['custom-wid12']);} ?></textarea>
	</p>
</div>	